<?php

namespace App\Console\Commands;

use App\Models\Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;

final class DownloadBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '下載書籍資料';

    /**
     * Guzzle http client instance.
     *
     * @var Client
     */
    protected $guzzle;

    /**
     * Threshold for download end.
     *
     * @var int
     */
    protected $notFoundThreshold = 10;

    /**
     * DownloadBooks constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->guzzle = new Client([
            'allow_redirects' => false,
            'base_uri' => 'https://www.bookwalker.com.tw',
            'http_errors' => false,
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $bookId = $this->startId();

        $_404 = 0;

        while (true) {
            $response = $this->guzzle->get($this->url($bookId));

            if ($response->getStatusCode() !== 200) {
                $this->failed($bookId, $response);

                return;
            }

            $content = $response->getBody()->getContents();

            if (!Str::contains($content, 'HTTP 404 您所瀏覽的網頁')) {
                $this->save($bookId, $content);

                $this->info(
                    sprintf('%s 已下載 %d', $this->now(), $bookId)
                );

                $_404 = 0;
            } else {
                $this->comment(
                    sprintf('%s 書籍編號 %d 不存在', $this->now(), $bookId)
                );

                ++$_404;

                if ($_404 > $this->notFoundThreshold) {
                    $bookId -= ($this->notFoundThreshold + 1);

                    $this->end($bookId);

                    break;
                }
            }

            ++$bookId;

            usleep(mt_rand(1000000, 1500000));
        }

        $this->info(sprintf('書籍資料下載成功，最新書籍編號：%d', $bookId));
    }

    /**
     * Get target book url.
     *
     * @param int $bookId
     *
     * @return string
     */
    protected function url(int $bookId): string
    {
        return sprintf('/product/%d', $bookId);
    }

    /**
     * Save webpage content.
     *
     * @param int $bookId
     * @param string $content
     *
     * @return int
     */
    protected function save(int $bookId, string $content): int
    {
        $path = sprintf('books/%d.html', $bookId);

        return file_put_contents(
            storage_path($path),
            $content,
            LOCK_EX
        );
    }

    /**
     * Get start book id.
     * 
     * @return int
     */
    protected function startId(): int
    {
        $config = Config::query()->find('books.downloaded.id');

        if (is_null($config)) {
            return 1;
        }

        return intval($config->value) + 1;
    }

    /**
     * Save downloaded id.
     *
     * @param int $bookId
     *
     * @return Config
     */
    protected function end(int $bookId): Config
    {
        return Config::query()->updateOrCreate(
            ['key' => 'books.downloaded.id'],
            ['value' => $bookId]
        );
    }

    /**
     * Handle download failed.
     *
     * @param int $bookId
     * @param ResponseInterface $response
     *
     * @return void
     */
    protected function failed(int $bookId, ResponseInterface $response): void
    {
        $this->error(sprintf('書籍資料下載失敗：%d', $bookId));

        Log::error('books.download.failed', [
            'book_id' => $bookId,
            'http_code' => $response->getStatusCode(),
            'downloaded_at' => $this->now(),
        ]);
    }

    /**
     * Get current date time with million seconds.
     *
     * @return string
     */
    protected function now(): string
    {
        return now()->format('m-d H:i:s.v');
    }
}
