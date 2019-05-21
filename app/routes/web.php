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
Route::get('/', function () {return view('auth/login');});
Route::get('/cadastrar', function () {return view('auth/register');});
Route::post('/cadastrar', 'HomeController@register');

Route::group(['prefix' => 'bank-category'], function () {
    Route::get('/', 'BankCategoryController@index');

    Route::get('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
    Route::post('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
    Route::get('update/{id}', 'BankCategoryController@update');
    Route::post('update/{id}', 'BankCategoryController@update');
    Route::post('delete/{id}', 'BankCategoryController@delete');
});

Route::group(['prefix' => 'event'], function () {
    Route::get('/', 'EventController@index');
    Route::get('create', 'EventController@create');
    Route::post('create', 'EventController@create');
    Route::get('update/{id}', 'EventController@update');
    Route::post('update/{id}', 'EventController@update');
    Route::post('delete/{id}', 'EventController@delete');
    Route::get('load', 'EventController@load');
    Route::get('/{id}/guests', 'EventController@guests');
    Route::get('/{eventId}', 'EventController@guest');
    Route::post('/{eventId}/guest/actions', 'EventController@actions');

    Route::group(['prefix' => 'guest'], function () {
        Route::get('/{eventId}/add', 'EventController@addGuest');
        Route::post('/{eventId}/create', 'EventController@storeGuest');
        Route::post('/delete/{id}', 'EventController@adelGuest');
    });
});

Route::group(['prefix' => 'guest'], function () {
    Route::get('/{tokenEmail}', 'EventGuestController@index');
    Route::post('/{tokenEmail}/send-invite', 'EventGuestController@sendInvite');
    Route::post('/{tokenEmail}/change-status/{status}', 'EventGuestController@changeStatus');
    Route::get('/{tokenEmail}/invoice', 'EventGuestController@invoice');
});

Auth::routes();
