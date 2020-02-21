<?php

use Illuminate\Support\Facades\Route;

Route::name('safe-browse')
    ->get('/safe-browse/{bid}')
    ->uses('SafeBrowseController@cover')
    ->where('bid', '\d+');

Route::name('search')
    ->get('/search')
    ->uses('SearchController@index');

Route::name('author')
    ->get('/authors/{author}')
    ->uses('CreatorController@author');

Route::name('writer')
    ->get('/writers/{writer}')
    ->uses('CreatorController@writer');

Route::name('characterDesigner')
    ->get('/character-designers/{characterDesigner}')
    ->uses('CreatorController@characterDesigner');

Route::name('illustrator')
    ->get('/illustrators/{illustrator}')
    ->uses('CreatorController@illustrator');

Route::name('translator')
    ->get('/translators/{translator}')
    ->uses('CreatorController@translator');

Route::name('home')
    ->get('/')
    ->uses('HomeController@index');
