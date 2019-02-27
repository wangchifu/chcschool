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
Route::get('/','HomeController@index')->name('index');

//Auth::routes();
#登入
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('login', 'Auth\LoginController@login');
Route::post('login', 'Auth\MLoginController@auth')->name('auth');

#登出
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

#註冊
//Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//Route::post('register', 'Auth\RegisterController@register')->name('register.post');

#忘記密碼
//Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//Route::post('password/reset', 'Auth\ResetPasswordController@reset');

//gsuite登入
Route::get('glogin', 'Auth\GLoginController@showLoginForm')->name('glogin');
Route::post('glogin', 'Auth\GLoginController@auth')->name('gauth');

Route::get('/home', 'HomeController@index')->name('home');


//公告系統
Route::get('posts' , 'PostsController@index')->name('posts.index');
Route::get('posts/{post}' , 'PostsController@show')->where('post', '[0-9]+')->name('posts.show');
Route::match(['post','get'],'posts/search/{search?}' , 'PostsController@search')->name('posts.search');
Route::get('posts/{job_title}/job_title' , 'PostsController@job_title')->name('posts.job_title');


//登入的使用者可用
Route::group(['middleware' => 'auth'],function(){
//結束模擬
    Route::get('sims/impersonate_leave', 'SimulationController@impersonate_leave')->name('sims.impersonate_leave');
});

//行政人員可用
Route::group(['middleware' => 'exec'],function(){
    Route::get('posts/create' , 'PostsController@create')->name('posts.create');
    Route::post('posts' , 'PostsController@store')->name('posts.store');
    Route::get('posts/{post}/edit' , 'PostsController@edit')->name('posts.edit');
    Route::patch('posts/{post}' , 'PostsController@update')->name('posts.update');
    Route::delete('posts/{post}', 'PostsController@destroy')->name('posts.destroy');
    //刪標題圖片
    Route::get('posts/{post}/delete_title_image' , 'PostsController@delete_title_image')->name('posts.delete_title_image');
    //刪檔案
    Route::get('posts/{post}/delete_file/{filename}' , 'PostsController@delete_file')->name('posts.delete_file');

});

//管理者可用
Route::group(['middleware' => 'admin'],function(){
    //更改密碼
    Route::get('edit_password','HomeController@edit_password')->name('edit_password');
    Route::patch('update_password','HomeController@update_password')->name('update_password');
    //模擬登入
    Route::get('sims/{user}/impersonate', 'SimulationController@impersonate')->name('sims.impersonate');
    //網站管理
    Route::get('setups','SetupController@index')->name('setups.index');
    Route::post('setups/add_logo', 'SetupController@add_logo')->name('setups.add_logo');
    //Route::post('setups/add_img', 'SetupController@add_img')->name('setups.add_img');
    Route::post('setups/add_imgs', 'SetupController@add_imgs')->name('setups.add_imgs');
    Route::get('setups/{folder}/del_img/{filename}', 'SetupController@del_img')->name('setups.del_img');
    Route::get('setups/photo','SetupController@photo')->name('setups.photo');
    Route::patch('setups/{setup}/photo/update_title_image','SetupController@update_title_image')->name('setups.update_title_image');
    //Route::patch('setups/{setup}', 'SetupController@update')->where('setup', '[0-9]+')->name('setups.update');
    Route::patch('setups/{setup}/nav_color', 'SetupController@nav_color')->where('setup', '[0-9]+')->name('setups.nav_color');
    Route::get('setups/nav_default/', 'SetupController@nav_default')->name('setups.nav_default');
    Route::patch('setups/{setup}/text', 'SetupController@text')->name('setups.text');
    Route::get('setups/col','SetupController@col')->name('setups.col');
    Route::post('setups/add_col', 'SetupController@add_col')->name('setups.add_col');
    Route::get('setups/{setup_col}/edit_col','SetupController@edit_col')->name('setups.edit_col');
    Route::patch('setups/{setup_col}/update_col','SetupController@update_col')->name('setups.update_col');
    Route::delete('setups/{setup_col}/delete_col','SetupController@delete_col')->name('setups.delete_col');

    //使用者管理
    Route::get('users', 'UsersController@index')->name('users.index');
    Route::get('users/leave', 'UsersController@leave')->name('users.leave');
    Route::get('users/{user}', 'UsersController@edit')->name('users.edit');
    Route::patch('users/{user}/update', 'UsersController@update')->name('users.update');
});
