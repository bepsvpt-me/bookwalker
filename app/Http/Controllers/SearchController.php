<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Rny\ZhConverter\ZhConverter;
use ScoutElastic\Builders\SearchBuilder;

final class SearchController extends Controller
{
    /**
     * Search result.
     *
     * @param Request $request
     *
     * @return RedirectResponse|View
     */
    public function index(Request $request)
    {
        $keyword = trim($request->query('keyword', ''));

        if (empty($keyword)) {
            return redirect()->route('home');
        }

        $keyword = ZhConverter::zh2hans($keyword);

        $builder = Book::search($keyword);

        $queries = $request->query();

        $filters = [
            'authors', 'writers', 'illustrators', 'translators',
            'types', 'categories', 'publishers', 'tags',
        ];

        foreach ($filters as $filter) {
            if (!empty($queries[$filter])) {
                $this->{$filter}($builder, $queries[$filter]);
            }
        }

        return view('search', [
            'all' => $this->all($keyword),
            'books' => $builder->paginate()->appends('query', null),
        ]);
    }

    /**
     * Get keyword first 1,000 search result.
     *
     * @param string $keyword
     *
     * @return Collection
     */
    protected function all(string $keyword): Collection
    {
        $key = sprintf('search-%s', md5($keyword));

        $ttl = 2 * 60; // 2 minutes

        return Cache::remember($key, $ttl, function () use ($keyword) {
            return Book::search($keyword)
                ->take(300)
                ->select(['id', 'type_id', 'category_id', 'publisher_id'])
                ->get();
        });
    }

    /**
     * Filter book type.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function types(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('type', $value);
    }

    /**
     * Filter book category.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function categories(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('category', $value);
    }

    /**
     * Filter book author.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function authors(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('authors', $value);
    }

    /**
     * Filter book writer.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function writers(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('writers', $value);
    }

    /**
     * Filter book illustrator.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function illustrators(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('illustrators', $value);
    }

    /**
     * Filter book translator.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function translators(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('translators', $value);
    }

    /**
     * Filter book publisher.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function publishers(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('publisher', $value);
    }

    /**
     * Filter book tag.
     *
     * @param SearchBuilder $builder
     * @param array<string> $value
     *
     * @return void
     */
    protected function tags(SearchBuilder $builder, array $value): void
    {
        $builder->whereIn('tags', $value);
    }
}
