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
Route::get('/', function () { return view('auth/login'); });
Route::get('/cadastrar', function () { return view('auth/register'); });
Route::post('/cadastrar', 'HomeController@register');

Route::group(['prefix' => 'bank-category'], function() {
    Route::get('/', 'BankCategoryController@index');

    Route::get('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
    Route::post('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
    Route::get('update/{id}', 'BankCategoryController@update');
    Route::post('update/{id}', 'BankCategoryController@update');
    Route::post('delete/{id}', 'BankCategoryController@delete');
}); 

Route::group(['prefix' => 'event'], function() {
    Route::get('/', 'EventController@index');
    Route::get('create', 'EventController@create');
    Route::post('create', 'EventController@create');
    Route::get('update/{id}', 'EventController@update');
    Route::post('update/{id}', 'EventController@update');
    Route::post('delete/{id}', 'EventController@delete');
    Route::get('load', 'EventController@load');
});

Auth::routes();



