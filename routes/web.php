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
Route::namespace('Admin')
    ->middleware(['auth'])
    ->prefix('admin')
    ->group(function (){
    Route::get('/', 'DashboardController@index')->name('admin.index');
    Route::resource('/category', 'CategoryController');
    Route::resource('/products','ProductController');
});

Route::namespace('Front')
    ->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::view('/', 'pages.main');
    Route::get('/collection', 'PageController@index');
    Route::get('/collection/detail', 'PageController@detail')->name('detail');
    Route::match(['get','post'],'/collection/category', 'PageController@category')->name('category');
    Route::view('/cart', 'pages.cart');
});

