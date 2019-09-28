<?php
use Illuminate\Http\Request;
Route::group([
	'prefix' => 'user'
], function () {
	Route::post('login', 'UserController@login');
	Route::post('signup', 'UserController@signup');
	Route::post('admin-signup', 'UserController@adminSignup');

	Route::group([
		'middleware' => 'auth:api'
	], function() {
		Route::get('logout', 'UserController@logout');
		Route::get('user', 'UserController@user');
		Route::post('update', 'UserController@update');
		Route::get('users', 'UserController@users');
		Route::get('sm', 'UserController@sm');
	});
});