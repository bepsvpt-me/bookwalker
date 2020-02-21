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
    ->get('/authors/{name}')
    ->uses('CreatorController@author');

Route::name('writer')
    ->get('/writers/{name}')
    ->uses('CreatorController@writer');

Route::name('characterDesigner')
    ->get('/character-designers/{name}')
    ->uses('CreatorController@characterDesigner');

Route::name('illustrator')
    ->get('/illustrators/{name}')
    ->uses('CreatorController@illustrator');

Route::name('translator')
    ->get('/translators/{name}')
    ->uses('CreatorController@translator');

Route::name('home')
    ->get('/')
    ->uses('HomeController@index');
