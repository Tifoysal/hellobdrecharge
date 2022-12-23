<?php

//Route::get('/', function () {
//    $data = \App\Models\Package::with('operator_name')->where('status','active')->get();
//    return view('frontend.home', compact('data'));
//})->name('home');

Route::get('/', 'HomeController@home')->name('home');

Route::get('/docs', function () {
    return view('documentation.index');
});

//Auth::routes();
Route::get('/api/get-rate/{id}', 'Admin\RequestController@getRate')->name('get.rate');
//Route::get('/login', 'Admin\UsersController@login')->name('login');
Route::post('/login', 'Admin\UsersController@doLogin')->name('do.login');

Route::group(['middleware' => 'auth', 'namespace' => 'Admin'], function () {
    Route::get('/admin', 'HomeController@index')->name('dashboard');
    Route::get('/logout', 'UsersController@logout')->name('logout');

    //for all
    Route::get('profile', 'ProfileController@profile')->name('profile');
    Route::put('profile', 'ProfileController@updateProfile')->name('profile.update');
    Route::put('update-password/{id}', 'ProfileController@updatePassword')->name('profile.update.password');
    Route::put('update-pin/{id}', 'ProfileController@updatePin')->name('profile.update.pin');

    //recharge
    Route::get('recharge/show/{id}', 'RechargeController@show')->name('recharge.show');
    Route::get('/recharge/list', 'RechargeController@list')->name('recharge.list');


    Route::get('/request/show/{id}', 'RequestController@requestDetails')->name('request.show');
    // request only for user
    Route::group(['middleware' => 'user', 'prefix' => 'user'], function () {
        //request
        Route::get('/request/list', 'RequestController@index')->name('user.request.index');
//        Route::get('/request/show/{id}', 'RequestController@requestDetails')->name('request.show');
        Route::get('/request/create', 'RequestController@create')->name('user.request.create');
        Route::post('/request/store/{type}', 'RequestController@store')->name('user.request.store');

        //data recharge
        Route::get('/request/data/list', 'RequestController@dataIndex')->name('user.request.data.index');
        Route::get('/request/data/create', 'RequestController@dataCreate')->name('user.request.data.create');
        Route::get('/request/data/select-type/{operator}','RequestController@selectType')->name('user.request.data.selectType');
        Route::get('/request/data/get-package/{type}','RequestController@getPackages')->name('user.request.data.getpackages');
        //recharge request by -user reseller
        Route::get('/recharge/form', 'RechargeController@recharge')->name('user.recharge.form');
        Route::post('/recharge_post/{type}', 'RechargeController@rechargePost')->name('user.recharge.post');

        //mobile banking by user
//        Route::get('/request/mBanking/list', 'RequestController@mBankingIndex')->name('user.mBanking.index');
//        Route::get('/request/mBanking/create', 'RequestController@mBankingCreate')->name('user.mBanking.create');
//        Route::post('/request/mBanking/store/{type}', 'RequestController@store')->name('user.mBanking.store');

        //account history
        Route::get('account/list', 'AccountController@index')->name('user.account');
        Route::any('account/search', 'AccountController@search')->name('account.search');

    });

    //mobile banking only for reseller
    Route::group(['middleware' => 'reseller', 'prefix' => 'seller'], function () {

        //top up
        Route::get('/request/list', 'RequestController@index')->name('seller.request.index');
        //Route::get('/request/show/{id}', 'RequestController@requestDetails')->name('request.show');
        Route::get('/request/create', 'RequestController@create')->name('seller.request.create');
        Route::post('/request/store/{type}', 'RequestController@store')->name('seller.request.store');

        //data recharge or internet
        Route::get('/request/data/list', 'RequestController@dataIndex')->name('seller.request.data.index');
        Route::get('/request/data/create', 'RequestController@dataCreate')->name('seller.request.data.create');
        Route::get('/request/data/select-type/{operator}','RequestController@selectType')->name('seller.request.data.selectType');
        Route::get('/request/data/get-package/{type}','RequestController@getPackages')->name('seller.request.data.getpackages');

        //mobile wallet
        Route::get('/request/mBanking/list', 'RequestController@mBankingIndex')->name('seller.mBanking.index');
        Route::get('/request/mBanking/create', 'RequestController@mBankingCreate')->name('seller.mBanking.create');
        Route::post('/request/mBanking/store/{type}', 'RequestController@store')->name('seller.mBanking.store');
        Route::get('/request/mBanking/{id}', 'RequestController@mBankingEdit')->name('seller.mBanking.edit');

        //recharge request by -user reseller
        Route::get('/recharge/form', 'RechargeController@recharge')->name('seller.recharge.form');
        Route::post('/recharge_post/{type}', 'RechargeController@rechargePost')->name('seller.recharge.post');

        //account
        Route::get('account/list', 'AccountController@index')->name('seller.account');
        Route::any('account/search', 'AccountController@search')->name('seller.account.search');
        Route::any('account/search', 'ConversionController@store')->name('rate.store');
    });

    Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
        //request
        Route::get('/request/list', 'RequestController@index')->name('admin.request.index')->middleware('admin');

        Route::get('/request/resend/{id}', 'RequestController@resend')->name('request.resend');
        Route::get('/request/update/{id}', 'RequestController@requestUpdateForm')->name('request.edit');
        Route::put('/request/update/{id}', 'RequestController@requestUpdate')->name('request.update');

        //Data recharge
        Route::get('/request/data/list', 'RequestController@dataIndex')->name('admin.request.data.index');
//        Route::get('/request/data/create', 'RequestController@dataCreate')->name('request.data.create');

        //Mobile Banking
        Route::get('/request/mBanking/list', 'RequestController@mBankingIndex')->name('admin.mBanking.index');
        Route::get('/request/mBanking/{id}', 'RequestController@mBankingEdit')->name('admin.mBanking.edit');
        Route::put('/request/mBanking/{id}', 'RequestController@mBankingUpdate')->name('admin.mBanking.update');

//    Route::group(['middleware' => 'admin'], function () {

        //user management

        Route::get('users/data', 'UsersController@data')->name('users.data');
        Route::get('users/{id}/edit', 'UsersController@edit')->name('admin.edit');
        Route::any('users/add_balance/{id}', 'UsersController@addBalance')->name('users.add_balance');

        //account history
        Route::get('account/list', 'AccountController@index')->name('account');
        Route::any('account/search', 'AccountController@Search');

        //package
        Route::get('package/list', 'PackageController@index')->name('package.list');
        Route::get('package/create', 'PackageController@createForm')->name('package.create');
        Route::post('package/create', 'PackageController@create')->name('package.create');
        Route::get('package/edit/{id}', 'PackageController@edit')->name('package.edit');
        Route::put('package/update/{id}', 'PackageController@update')->name('package.update');

        //purchase account setting
        Route::get('settings/purchase/account/list', 'PurchaseAccountController@index')->name('purchase.account.list');
        Route::get('settings/purchase/account/create', 'PurchaseAccountController@createForm')->name('purchase.account.create');
        Route::post('settings/purchase/account/create', 'PurchaseAccountController@create')->name('purchase.account.create');
        Route::get('settings/purchase/account/edit/{id}', 'PurchaseAccountController@edit')->name('purchase.account.edit');
        Route::put('settings/purchase/account/update/{id}', 'PurchaseAccountController@update')->name('purchase.account.update');

        //operator
        Route::get('operator/list', 'OperatorController@index')->name('operator.list');
        Route::get('operator/create', 'OperatorController@createForm')->name('operator.create');
        Route::post('operator/create', 'OperatorController@create')->name('operator.create');
        Route::get('operator/edit/{id}', 'OperatorController@edit')->name('operator.edit');
        Route::put('operator/update/{id}', 'OperatorController@update')->name('operator.update');

        //recharge-manage by Admin
        Route::post('recharge/edit', 'RechargeController@edit')->name('recharge.edit');
        Route::get('recharge/cancel/{id}', 'RechargeController@cancel')->name('recharge.cancel');


        Route::post('inbox/assign', 'InboxController@assign_sms')->name('assignSMS');
        Route::get('inbox', 'InboxController@index')->name('inbox.index');
        Route::get('inbox/data', 'InboxController@data')->name('inbox.data');
        Route::get('inbox/search', 'InboxController@search')->name('inbox.search');


        Route::get('operators/data', 'OperatorsController@data')->name('operators.data');
        Route::any('operators/add_balance/{id}',
            'OperatorsController@addBalance')->name('operators.add_balance');
        Route::resource('operators', 'OperatorsController');

        Route::post('update', 'TransactionsController@Update')->name('status.update');
        Route::get('transactions/allTnxdata',
            'TransactionsController@allTnxdata')->name('transactions.allTnxdata');
        Route::get('transactions/{id}', 'TransactionsController@show')->name('transactions.show');
        Route::resource('transactions', 'TransactionsController');

        Route::get('reports/balancemgmt', 'ReportsController@balanceMgmt')->name('reports.balanceMgmt');

//    });

        Route::get('user/balance/transfer', 'AccountController@balanceTransfer')->name('balance.transfer');
        Route::post('user/balance/transfer',
            'AccountController@balanceTransferPost')->name('balance.transfer_post');
//        Route::get('admin', 'AdminController@index')->name('admin');

        Route::group(['prefix'=>'settings'],function (){
            Route::get('edit', 'SettingController@edit')->name('settings');
            Route::put('update', 'SettingController@update')->name('settings.update');
            Route::resource('sender', 'SenderController');
            //service settings
            Route::resource('service','ServicesController');
        });

        Route::group(['prefix'=>'type'],function (){
            Route::get('/', 'TypeController@index')->name('type.index');
            Route::get('create', 'TypeController@create')->name('type.create');
            Route::post('store', 'TypeController@store')->name('type.store');
            Route::get('edit', 'TypeController@edit')->name('type.edit');
            Route::put('update', 'TypeController@update')->name('type.update');
        });


        Route::get('reports', 'ReportsController@index')->name('reports.index');
        Route::get('reports/data', 'ReportsController@data')->name('reports.data');

        Route::resource('admin/users', 'UsersController');
        //user edit
        Route::put('update-password/{id}', 'UsersController@updatePassword')->name('update.password');
        Route::put('update-pin/{id}', 'UsersController@updatePin')->name('update.pin');

        Route::resource('sendrequest', 'RequestController');
        Route::get('sendrequest/data', 'TransactionsController@data')->name('sendrequest.data');



    });

});
