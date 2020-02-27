<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Watson\Sitemap\Sitemap;

class SitemapController extends Controller
{
    /**
     * Sitemap instance.
     *
     * @var Sitemap
     */
    protected $sitemap;

    /**
     * SitemapController constructor.
     */
    public function __construct()
    {
        $this->sitemap = app('sitemap');
    }

    /**
     * Sitemap response.
     *
     * @return Response
     */
    public function index(): Response
    {
        foreach (config('bookwalker.creators') as $type) {
            $creators = DB::table('creators')
                ->where($type, '=', true)
                ->count();

            $creators = intval(ceil($creators / 5000));

            for ($i = 0; $i < $creators; ++$i) {
                $this->sitemap->addSitemap(
                    route(
                        sprintf('sitemap.%s', Str::slug($type)),
                        ['idx' => $i]
                    )
                );
            }
        }

        return $this->sitemap->renderSitemapIndex();
    }

    /**
     * Author sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function author(int $idx): Response
    {
        return $this->creator('author', $idx);
    }

    /**
     * Writer sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function writer(int $idx): Response
    {
        return $this->creator('writer', $idx);
    }

    /**
     * Character Designer sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function characterDesigner(int $idx): Response
    {
        return $this->creator('character_designer', $idx);
    }

    /**
     * Illustrator sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function illustrator(int $idx): Response
    {
        return $this->creator('illustrator', $idx);
    }

    /**
     * Cartoonist sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function translator(int $idx): Response
    {
        return $this->creator('translator', $idx);
    }

    /**
     * Cartoonist sitemap.
     *
     * @param int $idx
     *
     * @return Response
     */
    public function cartoonist(int $idx): Response
    {
        return $this->creator('cartoonist', $idx);
    }

    /**
     * Generated sitemap response.
     *
     * @param string $type
     * @param int $idx
     *
     * @return Response
     */
    protected function creator(string $type, int $idx): Response
    {
        $creators = DB::table('creators')
            ->where($type, '=', true)
            ->orderBy('id')
            ->skip($idx * 5000)
            ->take(5000)
            ->get(['name']);

        abort_if($creators->isEmpty(), 404);

        foreach ($creators as $creator) {
            if (!empty($creator->name)) {
                $this->sitemap->addSitemap(
                    route(Str::slug($type), ['name' => $creator->name])
                );
            }
        }

        return $this->sitemap->index();
    }
}
