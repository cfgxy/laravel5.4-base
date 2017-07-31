<?php


Route::post('login', ['uses' => 'AuthController@login', 'as' => 'login']);
Route::get('logout', ['uses' => 'AuthController@logout', 'as' => 'logout']);


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::get('user_info', ['uses' => 'AuthController@loginInfo', 'as' => 'user_info']);
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:admin'], function() {

    Route::resource('users/data', 'Users\ResourceController', ['as' => 'users']);
    Route::get('users/profile', ['uses' => 'Users\MyController@profile', 'as' => 'profile']);
    Route::put('users/profile', ['uses' => 'Users\MyController@updateProfile', 'as' => 'profile']);
    Route::post('users/profile/avatar', ['uses' => 'Users\MyController@uploadAvatar', 'as' => 'profile.avatar']);


    Route::resource('news/data', 'News\ResourceController', ['as' => 'news']);
});

\App\Lib\SiteUtils::exportVueRoutes(module_path('admin', 'vue/pages'), 'admin', ['role:admin']);