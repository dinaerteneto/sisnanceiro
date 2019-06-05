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

Route::get('/event/{eventId}/{eventStart}/{eventName}', 'EventController@page')->where('eventId', '[0-9]+');
Route::post('/event/{eventId}/page', 'EventController@page');

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/home', function () {return view('home');});

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
        Route::get('/{tokenEmail}/payment-with-money', 'EventGuestController@paymentWithMoney');
    });

    Route::group(['prefix' => 'store'], function () {
        Route::group(['prefix' => 'product-category'], function () {
            Route::post('/create', 'StoreProductCategoryController@create');
        });
        Route::group(['prefix' => 'product-brand'], function () {
            Route::post('/create', 'StoreProductBrandController@create');
        });
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', 'StoreProductController@index');
            Route::post('/', 'StoreProductController@index');
            Route::get('/create', 'StoreProductController@create');
            Route::post('/create', 'StoreProductController@create');
            Route::get('/update/{id}', 'StoreProductController@update');
            Route::post('/update/{id}', 'StoreProductController@update');
            Route::post('/add-subproduct', 'StoreProductController@addSubproduct');
        });
        Route::group(['prefix' => 'product-attributes'], function () {
            Route::post('/create', 'StoreProductAttributeController@create');
        });
    });
});

Auth::routes();
