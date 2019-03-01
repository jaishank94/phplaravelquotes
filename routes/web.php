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
    return redirect('/home');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::post('/postCreate', 'HomeController@postCreatePost')->name('postCreate');

Route::get('/postDelete/{id}', 'HomeController@getDeletePost')->name('postDelete');

Route::post('/edit', 'HomeController@getPostEdit')->name('edit');

Route::post('/updateProfile', 'HomeController@updateProfile')->name('updateProfile');

Route::get('/updateProfile', 'HomeController@updateProfileView')->name('updateProfileView');

Route::get('/userimage/{filename}', 'HomeController@getUserImage')->name('userimage');

Route::post('/likePost', 'HomeController@getPostLike')->name('likePost');

Route::post('/unLikePost', 'HomeController@getPostunLike')->name('unLikePost');
