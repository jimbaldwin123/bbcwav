<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    return view('welcome');

    return view('bbc');
});

Route::get('/wav', 'SourceController@getWavs');
Route::get('/wavconcat', 'SourceController@wavConcatWrap');
Route::get('/wavsec', 'SourceController@getWavSec');
Route::get('/wavsave', 'SourceController@getWavSave');