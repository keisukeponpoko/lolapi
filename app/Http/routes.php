<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/greeting', function () {
    return view('greeting');
});
Route::get('search', 'IndexController@getSearch');
Route::post('search', 'IndexController@postSearch');

Route::group(['middleware' => ['summoner']], function () {
    Route::get('/', 'IndexController@index');

    Route::get('stats/{id?}', 'IndexController@stats');
});

Route::get('champion', 'IndexController@champion');

Route::get('champion/get', 'IndexController@getAllChampion');