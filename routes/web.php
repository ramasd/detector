<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', 'ProjectController@index')->name('home');

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false
]);

Route::group(['middleware' => 'auth'], function() {
    Route::resource('users', 'UserController');
    Route::resource('projects', 'ProjectController');
    Route::resource('logs', 'LogController');
    Route::get('users/loginas/{id}', 'UserController@loginAs')->name('users.loginAs');
});

Route::get('projects/check/{hash}', 'ProjectController@checkProjects')->name('projects.check');

Route::get('logout', 'Auth\LoginController@logout');
