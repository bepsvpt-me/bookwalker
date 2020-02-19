<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\Category;
use App\Models\Creator;
use App\Models\Publisher;
use App\Models\Series;
use App\Models\Tag;
use App\Models\Type;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Finder\SplFileInfo;

final class ImportBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '匯入書籍資料';

    /**
     * Dom crawler.
     *
     * @var Crawler
     */
    protected $crawler;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $files = File::files(storage_path('books'));

        usort($files, [$this, 'cmp']);

        foreach ($files as $file) {
            $bookwalkerId = intval(pathinfo($file->getFilename(), PATHINFO_FILENAME));

            $this->info(sprintf('Importing %d...', $bookwalkerId));

            $this->crawler = new Crawler($file->getContents());

            $type = Type::query()->firstOrCreate(['name' => $this->type()]);

            $category = Category::query()->firstOrCreate([
                'type_id' => $type->getKey(),
                'name' => $this->category(),
            ]);

            $publisher = Publisher::query()->firstOrCreate(['name' => $this->publisher()]);

            /** @var Book $book */

            $book = Book::query()->firstOrNew(['bookwalker_id' => $bookwalkerId]);

            $book->fill([
                'name' => $this->name(),
                'slogan' => $this->slogan(),
                'description' => $this->description(),
                'price' => $this->price(),
                'pages' => $this->pages(),
                'published_at' => $this->publishedAt(),
            ]);

            $book->type()->associate($type);

            $book->category()->associate($category);

            if (!is_null($series = $this->series())) {
                $book->series()->associate(
                    Series::query()->firstOrCreate([
                        'type_id' => $type->getKey(),
                        'name' => $series,
                    ])
                );
            }

            $book->publisher()->associate($publisher);

            $book->save();

            $creators = [
                'authors' => '作者：',
                'writers' => '原作：',
                'illustrators' => '插畫：',
                'translators' => '譯者：',
                'characterDesigners' => '角色設定：',
                'cartoonists' => '漫畫：',
            ];

            foreach ($creators as $key => $text) {
                $ids = array_map(function (string $name) {
                    return Creator::query()->firstOrCreate(['name' => $name])->getKey();
                }, $this->creator($text));

                $book->{$key}()->sync($ids);
            }

            if (!empty($tags = $this->tags())) {
                $temps = array_map(function (string $tag) {
                    return Tag::query()->firstOrCreate(['name' => $tag])->getKey();
                }, $tags);

                $book->tags()->sync($temps);
            }
        }
    }

    /**
     * Get book name.
     *
     * @return string
     */
    protected function name(): string
    {
        return trim(Str::before($this->title(), ' - '));
    }

    /**
     * Get book slogan.
     *
     * @return string|null
     */
    protected function slogan(): ?string
    {
        $slogan = $this->crawler
            ->filter('div.bookdata div.booktitle h3.mar0pad0')
            ->first()
            ->text();

        return empty($slogan) ? null : $slogan;
    }

    /**
     * Get book description.
     *
     * @return string
     */
    protected function description(): string
    {
        $content = $this->crawler
            ->filter('meta[name="description"]')
            ->first()
            ->attr('content');

        return trim(Str::afterLast($content, ' - '));
    }

    /**
     * Get book type.
     *
     * @return string
     */
    protected function type(): string
    {
        return trim($this->breadcrumbs()->eq(1)->text());
    }

    /**
     * Get book category.
     *
     * @return string
     */
    protected function category(): string
    {
        return trim($this->breadcrumbs()->eq(2)->text());
    }

    /**
     * Get book series.
     *
     * @return string|null
     */
    protected function series(): ?string
    {
        $breadcrumbs = $this->breadcrumbs();

        if ($breadcrumbs->count() !== 5) {
            return null;
        }

        return trim($breadcrumbs->eq(3)->text());
    }

    /**
     * Get book tags.
     *
     * @return array
     */
    protected function tags(): array
    {
        $tags = [];

        $node = $this->crawler
            ->filter('.bookinfo_more ul')
            ->filterXPath('//span[text()="類型標籤："]');

        if ($node->count() > 0) {
            $content = $node->closest('li')->text();

            $text = trim(Str::after($content, '類型標籤：'));

            $tags = explode(' / ', $text);
        }

        $customs = ['已完結', '贈品'];

        foreach ($customs as $custom) {
            $count = $this->crawler
                ->filter(sprintf('img[title="%s"]', $custom))
                ->count();

            if ($count > 0) {
                $tags[] = $custom;
            }
        }

        return array_unique($tags);
    }

    /**
     * Get book price.
     *
     * @return int
     */
    protected function price(): int
    {
        $content = $this->crawler
            ->filter('script[type="application/ld+json"]')
            ->first()
            ->text();

        return intval(json_decode($content)->offers->price);
    }

    /**
     * Get book pages.
     *
     * @return int|null
     */
    protected function pages(): ?int
    {
        $content = $this->crawler
            ->filter('div.bookinfo_more')
            ->first()
            ->text();

        if (!Str::contains($content, '頁數：')) {
            return 0;
        }

        $temp = Str::after($content, '頁數：');

        return intval(trim(Str::before($temp, '頁')));
    }

    /**
     * Get book publisher.
     *
     * @return string
     */
    protected function publisher(): string
    {
        return $this->crawler
            ->filter('div.bookinfo_more a[itemprop="url"]')
            ->first()
            ->text();
    }

    /**
     * Get book published date.
     *
     * @return Carbon
     */
    protected function publishedAt(): Carbon
    {
        if ($this->name() === '立體書不可思議') {
            return Carbon::parse('2015-08-19');
        }

        $text = $this->crawler
            ->filter('span[itemprop="publish_date"]')
            ->first()
            ->closest('li')
            ->text();

        $date = str_replace(
            ['年', '月', '日', ' '],
            ['-', '-', '', ''],
            Str::after($text, '發售日：')
        );

        if (preg_match('/\d{4}-\d{2}-\d{2}/', $date) !== 1) {
            dd($this->name());
        }

        return Carbon::parse($date);
    }

    /**
     * Get specific creator.
     *
     * @param string $target
     *
     * @return array
     */
    protected function creator(string $target): array
    {
        $node = $this->crawler
            ->filter('dl.writer_data')
            ->filterXPath(sprintf('//dt[text()="%s"]', $target));

        if ($node->count() === 0) {
            return [];
        }

        $names = $node->nextAll()->text();

        if (!Str::contains($names, '、')) {
            return [$names];
        }

        return explode('、', Str::before($names, '...等'));
    }

    /**
     * Get dom title meta.
     *
     * @return string
     */
    protected function title(): string
    {
        return $this->crawler
            ->filter('meta[name="title"]')
            ->first()
            ->attr('content');
    }

    /**
     * Get breadcrumbs.
     *
     * @return Crawler
     */
    protected function breadcrumbs(): Crawler
    {
        return $this->crawler->filter('#breadcrumb_list li');
    }

    /**
     * usort compare function.
     *
     * @param SplFileInfo $a
     * @param SplFileInfo $b
     *
     * @return int
     */
    protected function cmp(SplFileInfo $a, SplFileInfo $b): int
    {
        $an = pathinfo($a->getFilename(), PATHINFO_FILENAME);

        $bn = pathinfo($b->getFilename(), PATHINFO_FILENAME);

        return intval($an) <=> intval($bn);
    }
}
