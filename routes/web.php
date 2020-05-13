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

Route::any('test', 'TestController@index');

Route::get('/', function () {
    return view('home.welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 后台管理员认证
Route::get('admin/login', 'Admin\LoginController@showLoginForm')->name('admin.login');
Route::post('admin/login', 'Admin\LoginController@login')->name('admin.login');
Route::post('admin/logout', 'Admin\LoginController@logout')->name('admin.logout');
