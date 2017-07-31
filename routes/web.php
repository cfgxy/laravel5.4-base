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

Route::group(['as' => 'site.'], function () {
    Route::get('', ['uses' => 'SiteController@index', 'as' => 'index']);
});

\App\Lib\SiteUtils::exportTemplateRoutes(public_path('web'));