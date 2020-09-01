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

Auth::routes();

Route::name('home')->get('/', function () {return view('auth/login');});
Route::get('/cadastrar', function () {return view('auth/register');});
Route::post('/cadastrar', 'HomeController@register');

Route::get('/event/{eventId}/{eventStart}/{eventName}', 'EventController@page')->where('eventId', '[0-9]+');
Route::post('/event/{eventId}/page', 'EventController@page');

Route::group(['prefix' => 'sync'], function () {
    Route::get('/customer', 'SynchronizerController@customerSync');
});

Route::group(['prefix' => 'cron'], function () {
    Route::get('/credit-card-invoices', 'CronController@creditCardInvoices');
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/home', function () {return view('home');});

    Route::group(['prefix' => 'profile'], function () {
        Route::name('My Profile')->get('/', 'ProfileController@index');
        Route::post('/', 'ProfileController@index');
    });

    Route::group(['prefix' => 'bank-category'], function () {
        Route::get('/', 'BankCategoryController@index');
        Route::get('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
        Route::post('create/{main_parent_category_id}/{parent_category_id?}', 'BankCategoryController@create');
        Route::post('min-create/{main_parent_category_id}', 'BankCategoryController@createMin');
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
            Route::post('/delete/{id}', 'StoreProductController@delete');
            Route::post('/add-subproduct', 'StoreProductController@addSubproduct');
        });
        Route::group(['prefix' => 'product-attributes'], function () {
            Route::post('/create', 'StoreProductAttributeController@create');
        });
    });

    Route::group(['prefix' => 'customer'], function () {
        Route::name('Cliente')->get('/', 'CustomerController@index');
        Route::post('/', 'CustomerController@index');
        Route::name('Incluir')->get('/create', 'CustomerController@create');
        Route::post('/create', 'CustomerController@create');
        Route::name('Alterar')->get('/update/{id}', 'CustomerController@update');
        Route::post('/update/{id}', 'CustomerController@update');
        Route::post('/delete/{id}', 'CustomerController@delete');
        Route::post('/min-create', 'CustomerController@createMin');
    });

    Route::group(['prefix' => 'supplier'], function () {
        Route::name('Fornecedor')->get('/', 'SupplierController@index');
        Route::post('/', 'SupplierController@index');
        Route::name('Incluir')->get('/create', 'SupplierController@create');
        Route::post('/create', 'SupplierController@create');
        Route::name('Alterar')->get('/update/{id}', 'SupplierController@update');
        Route::post('/update/{id}', 'SupplierController@update');
        Route::post('/delete/{id}', 'SupplierController@delete');
        Route::post('/min-create', 'SupplierController@createMin');
    });

    Route::group(['prefix' => 'person'], function () {
        Route::get('/add-contact', 'PersonController@addContact');
        Route::get('/add-address', 'PersonController@addAddress');
        Route::post('/del-contact/{id}', 'PersonController@delContact');
        Route::post('/del-address/{id}', 'PersonController@delAddress');
    });

    Route::group(['prefix' => 'sale'], function () {
        Route::get('/', 'SaleController@index');
        Route::post('/', 'SaleController@index');
        Route::get('/create', 'SaleController@create');
        Route::post('/create', 'SaleController@create');
        Route::get('/search-item', 'SaleController@searchItem');
        Route::get('/search-customer', 'SaleController@searchCustomer');
        Route::get('/ask/{id}', 'SaleController@ask');
        Route::get('/coupon/{id}', 'SaleController@coupon');
        Route::get('/print/{id}', 'SaleController@print');
        Route::get('/view/{id}', 'SaleController@view');
        Route::get('/update/{id}', 'SaleController@update');
        Route::post('/update/{id}', 'SaleController@update');
        Route::post('/cancel/{id}', 'SaleController@cancel');
        Route::post('/delete/{id}', 'SaleController@delete');
        Route::get('/copy/{id}', 'SaleController@copy');

        Route::get('/create/{token}', 'SaleController@createTemp');
        Route::post('/add-temp-item', 'SaleController@addTempItem');
        Route::post('/del-temp-item', 'SaleController@delTempItem');
        Route::post('/del-temp/{token}', 'SaleController@delTemp');
    });

    Route::group(['prefix' => 'bank-account'], function () {
        Route::get('/', 'BankAccountController@index');
        Route::post('/', 'BankAccountController@index');
        Route::get('/create', 'BankAccountController@create');
        Route::post('/create', 'BankAccountController@create');
        Route::get('/update/{id}', 'BankAccountController@update');
        Route::post('/update/{id}', 'BankAccountController@update');
        Route::post('/delete/{id}', 'BankAccountController@delete');
    });

    Route::group(['prefix' => 'bank-transaction'], function () {
        Route::get('/', 'BankTransactionController@index');
        Route::post('/', 'BankTransactionController@index');
        Route::get('/delete/{id}', 'BankTransactionController@delete');
        Route::post('/set-paid/{id}', 'BankTransactionController@setPaid');
        Route::post('/get-total-by-main-category', 'BankTransactionController@getTotalByMainCategory');
        Route::get('/partial-pay/{id}', 'BankTransactionController@partialPay');
        Route::post('/partial-pay/{id}', 'BankTransactionController@partialPay');

        Route::group(['prefix' => 'pay'], function () {
            Route::get('/', ['uses' => 'BankTransactionController@index', 'main_category_id' => 2]);
            Route::post('/', ['uses' => 'BankTransactionController@index', 'main_category_id' => 2]);
            Route::get('/create', ['uses' => 'BankTransactionController@create', 'main_category_id' => 2]);
            Route::post('/create', ['uses' => 'BankTransactionController@create', 'main_category_id' => 2]);
            Route::get('/update/{id}', ['uses' => 'BankTransactionController@update', 'main_category_id' => 2]);
            Route::post('/update/{id}', ['uses' => 'BankTransactionController@update', 'main_category_id' => 2]);
            Route::post('/delete/{id}', ['uses' => 'BankTransactionController@delete', 'main_category_id' => 2]);
        });

        Route::group(['prefix' => 'receive'], function () {
            Route::get('/', ['uses' => 'BankTransactionController@index', 'main_category_id' => 3]);
            Route::post('/', ['uses' => 'BankTransactionController@index', 'main_category_id' => 3]);
            Route::get('/create', ['uses' => 'BankTransactionController@create', 'main_category_id' => 3]);
            Route::post('/create', ['uses' => 'BankTransactionController@create', 'main_category_id' => 3]);
            Route::get('/update/{id}', ['uses' => 'BankTransactionController@update', 'main_category_id' => 3]);
            Route::post('/update/{id}', ['uses' => 'BankTransactionController@update', 'main_category_id' => 3]);
            Route::post('/delete/{id}', ['uses' => 'BankTransactionController@delete', 'main_category_id' => 3]);
        });

        Route::group(['prefix' => 'transfer'], function () {
            Route::get('/', ['uses' => 'BankTransactionTransferController@index']);
            Route::post('/', ['uses' => 'BankTransactionTransferController@index']);
            Route::get('/create', ['uses' => 'BankTransactionTransferController@create']);
            Route::post('/create', ['uses' => 'BankTransactionTransferController@create']);
            Route::get('/update/{id}', ['uses' => 'BankTransactionTransferController@update']);
            Route::post('/update/{id}', ['uses' => 'BankTransactionTransferController@update']);
            Route::post('/delete/{id}', ['uses' => 'BankTransactionTransferController@delete']);
        });
    });

    Route::group(['prefix' => 'reports'], function () {
        Route::get('/cash-flow', 'ReportController@cashFlow');
        Route::post('/cash-flow', 'ReportController@cashFlow');
        Route::get('/cash-flow/detail', 'ReportController@cashFlowDetail');
    });

    Route::group(['prefix' => 'payment-tax'], function () {
        Route::get('/', 'PaymentTaxController@index');
        Route::post('/', 'PaymentTaxController@index');
        Route::get('/create/{payment_method_id}', 'PaymentTaxController@create');
        Route::post('/create/{payment_method_id}', 'PaymentTaxController@create');
        Route::get('/update/{id}', 'PaymentTaxController@update');
        Route::post('/update/{id}', 'PaymentTaxController@update');
        Route::post('/delete/{id}', 'PaymentTaxController@delete');
    });

    Route::group(['prefix' => 'credit-card'], function () {
        Route::get('/', 'CreditCardController@index');
        Route::post('/', 'CreditCardController@index');
        Route::get('/create', 'CreditCardController@create');
        Route::post('/create', 'CreditCardController@create');
        Route::get('/update/{id}', 'CreditCardController@update');
        Route::post('/update/{id}', 'CreditCardController@update');
        Route::post('/delete/{id}', 'CreditCardController@delete');
    });

    Route::group(['prefix' => 'credit-card/{credit_card_id}'], function () {
        Route::get('/', 'CreditCardTransactionController@index');
        Route::post('/', 'CreditCardTransactionController@index');
        Route::get('/create', 'CreditCardTransactionController@create');
        Route::post('/create', 'CreditCardTransactionController@create');
        Route::get('/update/{id}', 'CreditCardTransactionController@update');
        Route::post('/update/{id}', 'CreditCardTransactionController@update');
        Route::post('/delete/{id}', 'CreditCardTransactionController@delete');
        Route::post('/get-total', 'CreditCardTransactionController@getTotal');
        Route::post('/due-invoice-dates', 'CreditCardTransactionController@dueInvoiceDates');
    });

});
