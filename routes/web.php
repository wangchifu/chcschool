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
})->name('index');


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

//使用者可用
Route::group(['middleware' => 'auth'],function(){

});

//管理者可用
Route::group(['middleware' => 'admin'],function(){
    Route::get('/admin', 'HomeController@admin')->name('admin');
});
