<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

final class SyncBooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'books:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步書籍清單';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $guzzle = new Client;

        $i = 22613;

        $template = 'https://www.bookwalker.com.tw/product/%d';

        $file = storage_path('books/%d.html');

        while (true) {
            $url = sprintf($template, $i);

            $response = $guzzle->get($url, [
                'allow_redirects' => false,
                'http_errors' => false,
            ]);

            if ($response->getStatusCode() !== 200) {
                $this->error(sprintf('同步書籍資料失敗：%d', $i));

                return;
            }

            $content = $response->getBody()->getContents();

            if (Str::contains($content, 'HTTP 404 您所瀏覽的網頁')) {
                $this->comment(sprintf('書籍 %d 不存在', $i));
            } else {
                file_put_contents(sprintf($file, $i), $content);

                $this->info(sprintf('%s 已同步至：%d ...', now()->toDateTimeString(), $i));
            }

            ++$i;

            usleep(mt_rand(350000, 550000));
        }
    }
}
