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
Route::get('/register', function () { return view('register'); });
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'bank-category'], function() {
    Route::get('all', 'BankCategoryController@index');

    Route::get('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
    Route::post('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');

    Route::put('update/{id}', 'BankCategoryController@update');
    Route::delete('delete/{id}', 'BankCategoryController@delete');
}); 


Auth::routes();



