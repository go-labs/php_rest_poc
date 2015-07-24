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

Route::group(array('prefix' => 'api/v1'), function()
{
    Route::resource('post', 'PostController', ['except' => ['edit', 'create']]);
    Route::resource('tag', 'TagController', ['except' => ['edit', 'create']]);
    Route::post('post/{post}/tags', 'PostController@add_tags');
});

DB::listen(
    function ($sql, $bindings, $time) {
        // echo $sql . "\n";
        //  $sql - select * from `ncv_users` where `ncv_users`.`id` = ? limit 1
        //  $bindings - [5]
        //  $time(in milliseconds) - 0.38
    }
);