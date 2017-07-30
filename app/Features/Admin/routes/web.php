<?php

Route::get('login', ['uses' => 'AuthController@showLoginForm', 'as' => 'login']);
Route::post('login', ['uses' => 'AuthController@login', 'as' => 'login']);
Route::get('logout', ['uses' => 'AuthController@logout', 'as' => 'logout']);


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('user_info', ['uses' => 'AuthController@loginInfo', 'as' => 'user_info']);
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function() {
    Route::get('', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'index']);


    Route::get('apps', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'apps']);


    Route::get('merchants', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'merchants']);


    Route::get('tasks', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'tasks']);


    Route::get('users', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'users']);
    Route::resource('users/data', 'Users\ResourceController', ['as' => 'users']);
    Route::get('users/profile', ['uses' => 'Users\MyController@profile', 'as' => 'profile']);
    Route::put('users/profile', ['uses' => 'Users\MyController@updateProfile', 'as' => 'profile']);
    Route::post('users/profile/avatar', ['uses' => 'Users\MyController@uploadAvatar', 'as' => 'profile.avatar']);


    Route::get('payments', ['uses' => '\App\Http\Controllers\VueController@appPage', 'as' => 'payments']);


});
