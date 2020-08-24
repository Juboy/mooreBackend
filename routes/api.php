<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/users', [
	'uses' => 'UserController@store',
]);

Route::patch('/users', [
	'uses' => 'UserController@edit',
	'middleware' => 'auth.jwt'
]);

Route::patch('/users/password', [
	'uses' => 'UserController@change_password',
	'middleware' => 'auth.jwt'
]);

Route::post('/users/login', [
	'uses' => 'UserController@login'
]);

Route::post('/users/logout', [
	'uses' => 'UserController@logout',
]);


Route::post('/token/refresh', [
	'uses' => 'UserController@refresh',
]);

Route::post('/tasks', [
	'uses' => 'TaskController@store',
	'middleware' => 'auth.jwt'
]);

Route::put('/tasks/{id}', [
	'uses' => 'TaskController@edit',
	'middleware' => 'auth.jwt'
]);

Route::delete('/tasks/{id}', [
	'uses' => 'TaskController@delete',
	'middleware' => 'auth.jwt'
]);

Route::get('/tasks', [
	'uses' => 'TaskController@index',
	'middleware' => 'auth.jwt'
]);
