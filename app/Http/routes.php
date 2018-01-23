<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Auth\AuthController;


Route::get('/', function ()
{
    return view('user.login');
})->name('home');


Route::group(['prefix' => 'user'], function()
{
    Route::get('/sign-up', [
        'uses' => 'UserController@getSignUp',
        'as' => 'user.sign-up'
    ]);

    Route::post('/sign-up', [
        'uses' => 'UserController@postSignUp',
        'as' => 'user.sign-up'
    ]);

    Route::get('/login', [
        'uses' => 'UserController@getLogin',
        'as' => 'user.login'
    ]);

    Route::post('/login', [
        'uses' => 'UserController@postLogin',
        'as' => 'user.login'
    ]);

    Route::get('/logout', [
        'uses' => 'UserController@getLogout',
        'as' => 'user.logout'
    ]);

    Route::get('/dashboard', [
        'uses' => 'PostController@getDashboard',
        'as' => 'user.dashboard',
        'middleware' => 'auth'
    ]);

    Route::get('/account', [
        'uses' => 'UserController@getAccount',
        'as' => 'user.account'
    ]);

    Route::post('/upateaccount', [
        'uses' => 'UserController@postSaveAccount',
        'as' => 'account.save'
    ]);

    Route::get('/userimage/{filename}', [
        'uses' => 'UserController@getUserImage',
        'as' => 'account.image'
    ]);
});

Route::post('/createpost', [
    'uses' => 'PostController@postCreatePost',
    'as' => 'post.create',
    'middleware' => 'auth'
]);

Route::get('/delete-post/{post_id}', [
    'uses' => 'PostController@getDeletePost',
    'as' => 'post.delete',
    'middleware' => 'auth'
]);

Route::post('/edit', [
    'uses' => 'PostController@postEditPost',
    'as' => 'edit'
]);

Route::post('/like', [
    'uses' => 'PostController@postLikePost',
    'as' => 'like'
]);

Route::get('facebook', function ()
{
    return view('facebookAuth');
});

Route::get('/auth/facebook', 'Auth\AuthController@redirectToFacebook');
Route::get('/auth/facebook/callback', 'Auth\AuthController@handleFacebookCallback');


Route::get('google', function ()
{
    return view('googleAuth');
});

Route::get('/auth/google', 'Auth\AuthController@redirectToGoogle');
Route::get('/auth/google/callback', 'Auth\AuthController@handleGoogleCallback');