<?php

use Illuminate\Support\Facades\Route;

Route::name('search')
    ->get('/search')
    ->uses('SearchController@index');

Route::name('safe-browse')
    ->get('/safe-browse/{bid}')
    ->uses('SafeBrowseController@cover')
    ->where('bid', '\d+');

Route::name('home')
    ->get('/')
    ->uses('HomeController@index');
