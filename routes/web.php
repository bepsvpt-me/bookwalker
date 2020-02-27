<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::name('safe-browse')
    ->get('/safe-browse/{bid}')
    ->uses('SafeBrowseController@cover')
    ->where('bid', '\d+');

Route::name('search')
    ->get('/search')
    ->uses('SearchController@index');

Route::name('author')
    ->get('/authors/{name}')
    ->uses('CreatorController@author');

Route::name('writer')
    ->get('/writers/{name}')
    ->uses('CreatorController@writer');

Route::name('character-designer')
    ->get('/character-designers/{name}')
    ->uses('CreatorController@characterDesigner');

Route::name('illustrator')
    ->get('/illustrators/{name}')
    ->uses('CreatorController@illustrator');

Route::name('translator')
    ->get('/translators/{name}')
    ->uses('CreatorController@translator');

Route::name('cartoonist')
    ->get('/cartoonists/{name}')
    ->uses('CreatorController@cartoonist');

Route::name('home')
    ->get('/')
    ->uses('HomeController@index');

Route::get('sitemap.xml')
    ->uses('SitemapController@index');

Route::prefix('sitemap')
    ->name('sitemap.')
    ->group(function () {
        foreach (config('bookwalker.creators') as $type) {
            $type = Str::slug($type);

            Route::name($type)
                ->get(sprintf('%s-{idx}.xml', $type))
                ->uses(sprintf('SitemapController@%s', Str::camel($type)))
                ->where('idx', '\d+');;
        }
    });
