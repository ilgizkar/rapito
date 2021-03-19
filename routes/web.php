<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/vkAuth', 'HomeController@vkAuth')->name('vkAuth');
Route::get('/vkAuthToken', 'HomeController@vkAuthToken');
Route::get('/dashboard', 'HomeController@index');
Route::get('/getUser', 'HomeController@getUser');

Route::post('/searchGroups', 'HomeController@searchGroups');
Route::post('/allCities', 'HomeController@allCities');


Route::get('/a', 'HomeController@a');
Route::get('/b', 'HomeController@searchAllUsersInGroups');

Route::get('/dashboard/{any}', 'HomeController@index');
