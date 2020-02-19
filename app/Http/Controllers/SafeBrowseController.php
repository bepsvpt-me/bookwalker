<?php

namespace App\Http\Controllers;

use App\Models\Book;
use ErrorException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class SafeBrowseController extends Controller
{
    /**
     * BookWalker image.
     *
     * @param int $bid
     *
     * @return BinaryFileResponse
     */
    public function cover(int $bid): BinaryFileResponse
    {
        $this->check($bid);

        $url = sprintf('https://images.bookwalker.com.tw/upload/product/%d/zoom_big_%d.jpg', $bid, $bid);

        $hash = md5($url);

        $prefix = implode('/', array_slice(str_split($hash, '3'), 0, 2));

        $path = storage_path(sprintf('images/%s/%s.jpg', $prefix, $hash));

        return $this->response($path, $url);
    }

    /**
     * Check book exists.
     *
     * @param int $bid
     *
     * @return void
     */
    protected function check(int $bid): void
    {
        $key = sprintf('image-%d', $bid);

        $ttl = 24 * 60 * 60; // 1 day

        $exists = Cache::remember($key, $ttl, function () use ($bid) {
            return Book::query()
                ->where('bookwalker_id', '=', $bid)
                ->exists();
        });

        abort_unless($exists, 404);
    }

    /**
     * Safe Browse response.
     *
     * @param string $path
     * @param string $url
     *
     * @return BinaryFileResponse
     */
    protected function response(string $path, string $url): BinaryFileResponse
    {
        if (!File::isReadable($path)) {
            $this->fetch($url, $path);
        }

        if (request('type', 'webp') === 'webp') {
            if (File::isReadable($this->extensionToWebp($path))) {
                $path = $this->extensionToWebp($path);
            }
        }

        $cache = [
            'immutable' => true,
            'max_age' => 60 * 60 * 24 * 7,
            'public' => true,
        ];

        return response()
            ->file($path, ['content-type' => mime_content_type($path)])
            ->setCache($cache);
    }

    /**
     * Fetch and store remote file.
     *
     * @param string $url
     * @param string $path
     *
     * @return bool
     */
    protected function fetch(string $url, string $path): bool
    {
        try {
            $content = file_get_contents($url);
        } catch (ErrorException $e) {
            abort(404);
        }

        abort_unless($content, 404);

        if (!File::isDirectory(dirname($path))) {
            File::makeDirectory(dirname($path), 0755, true);
        }

        $ok = boolval(File::put($path, $content, true));

        if (!$ok) {
            return false;
        }

        return imagewebp(
            imagecreatefromjpeg($path),
            $this->extensionToWebp($path),
            60
        );
    }

    /**
     * Replace jpg extension to webp.
     *
     * @param string $path
     *
     * @return string
     */
    protected function extensionToWebp(string $path): string
    {
        if (!Str::endsWith($path, '.jpg')) {
            return $path;
        }

        $end = strrpos($path, '.jpg');

        return sprintf('%s.webp', substr($path, 0, $end));
    }
}
