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
    return view('welcome');
});

Auth::routes();

Route::get('/home', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

Route::get('/sources', ['as' => 'sources.index' ,'uses' => 'SourceController@index']);
Route::get('/sources/new', ['as' => 'sources.add_form' ,'uses' => 'SourceController@create']);
Route::post('/sources', ['as' => 'sources.add' ,'uses' => 'SourceController@store']);
