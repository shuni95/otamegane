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

$router->get('/home', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

$router->group(['middleware' => 'auth'], function ($router) {
    $router->get('/sources', ['as' => 'sources.index' ,'uses' => 'SourceController@index']);
    $router->get('/sources/new', ['as' => 'sources.add_form' ,'uses' => 'SourceController@create']);
    $router->post('/sources', ['as' => 'sources.add' ,'uses' => 'SourceController@store']);
    $router->get('/sources/{id}/add_manga', ['as' => 'sources.add_manga_form', 'uses' => 'SourceMangaController@create']);
    $router->post('/sources/{id}/add_manga', ['as' => 'sources.add_manga', 'uses' => 'SourceMangaController@store']);

    $router->get('/mangas', ['as' => 'mangas.index' ,'uses' => 'MangaController@index']);
    $router->get('/mangas/new', ['as' => 'mangas.add_form' ,'uses' => 'MangaController@create']);
    $router->post('/mangas', ['as' => 'mangas.add' ,'uses' => 'MangaController@store']);
    $router->get('/mangas/show/{id}', ['as' => 'mangas.show' ,'uses' => 'MangaController@show']);
    $router->get('/mangas/edit/{id}', ['as' => 'mangas.edit_form', 'uses' => 'MangaController@edit']);
    $router->post('/mangas/edit/{id}', ['as' => 'mangas.update', 'uses' => 'MangaController@update']);

    $router->get('/telegram_chats', ['as' => 'telegram_chats.index', 'uses' => 'TelegramChatController@index']);
    $router->get('/telegram_chats/{id}/subscriptions', ['as' => 'telegram_chats.subscriptions', 'uses' => 'TelegramChatController@subscriptions']);

    $router->get('/suggestions', ['as' => 'suggestions.index', 'uses' => 'SuggestionController@index']);
});

$router->post(env('TELEGRAM_BOT_TOKEN').'/webhook', ['as' => 'handling_commands', 'uses' =>'HandlingCommandController@handle']);
