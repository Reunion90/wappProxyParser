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

Route::get('/', 'IndexController@fnIndex');

Route::post('/settings/save', 'IndexController@fnSaveSettings');

Route::post('/processes/create/google', 'IndexController@fnCreateGoogleProcess');
Route::post('/processes/create/site', 'IndexController@fnCreateSiteProcess');
Route::post('/processes/kill', 'IndexController@fnKillProcess');

Route::post('/proxies/delete', 'IndexController@fnProxyDelete');

Route::get('/proxies/work', 'IndexController@fnShowWorkProxies');

Route::get('/processes', 'IndexController@fnShowProcesses');
Route::get('/pages', 'IndexController@fnShowPages');
Route::get('/proxies', 'IndexController@fnShowProxies');
Route::get('/settings', 'IndexController@fnShowSettings');

Route::resources([

]);	