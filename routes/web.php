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

Route::get('/pay/{shortname}', 'BunqTabRequestsController@handle')->name('bunqtab.handle');
Route::get('/payed', 'BunqTabRequestsController@payed')->name('bunqtab.handle');

Route::get('/paymentrequest/{token}', 'PaymentRequestController@handle')->name('paymentrequest.handle');
Route::get('/paymentrequest/{token}/return', 'PaymentRequestController@returned')->name('paymentrequest.returned');
Route::post('/paymentrequest/{bankaccount}/webhook', 'PaymentRequestController@webhook')->name('paymentrequest.webhook');
Route::get('/accounts/{bankAccount}/invoices', 'BankPaymentsController@bunqinvoice')->name('account.payments.bunqinvoice');

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/home');
    } else {
        return redirect('/login');
    }
});


Auth::routes();

Route::group(['middleware' => ['auth']], function() {
       
    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/login');
    });
    Route::get('/home', 'HomeController@index')->name('home');


    //Route::get('/users', ['middleware' => ['permission:users'], 'uses' => 'UserController@index'])->name('users.index');
    Route::get('/users', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@index'])->name('users.index');
    Route::get('/users/data', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@data'])->name('users.data');
    Route::get('/user/new', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@create'])->name('user.create');
    Route::post('/user/new', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@store'])->name('user.store');
    Route::get('/user/{user}', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@edit'])->name('user.edit');
    Route::post('/user/{user}', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@update'])->name('user.update');
    Route::delete('/user/{user}', ['middleware' => ['permission:user-management'], 'uses' => 'UserController@destroy'])->name('user.delete');

    Route::get('/roles', ['middleware' => ['permission:role-management'], 'uses' => 'RoleController@index'])->name('roles.index');
    Route::get('/roles/data', ['middleware' => ['permission:role-management'], 'uses' =>'RoleController@data'])->name('roles.data');
    Route::get('/role/new',['middleware' => ['permission:role-management'], 'uses' =>'RoleController@create'])->name('role.create');
    Route::post('/role/new', ['middleware' => ['permission:role-management'], 'uses' =>'RoleController@store'])->name('role.store');
    Route::get('/role/{role}', ['middleware' => ['permission:role-management'], 'uses' =>'RoleController@edit'])->name('role.edit');
    Route::post('/role/{role}', ['middleware' => ['permission:role-management'], 'uses' =>'RoleController@update'])->name('role.update');
    Route::delete('/role/{role}', ['middleware' => ['permission:role-management'], 'uses' =>'RoleController@destroy'])->name('role.delete');
    
    Route::get('/permissions', ['middleware' => ['permission:permission-management'], 'uses' => 'PermissionController@index'])->name('permissions.index');
    Route::get('/permissions/data', ['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@data'])->name('permissions.data');
    Route::get('/permission/new',['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@create'])->name('permission.create');
    Route::post('/permission/new', ['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@store'])->name('permission.store');
    Route::get('/permission/{permission}', ['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@edit'])->name('permission.edit');
    Route::post('/permission/{permission}', ['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@update'])->name('permission.update');
    Route::delete('/permission/{permission}', ['middleware' => ['permission:permission-management'], 'uses' =>'PermissionController@destroy'])->name('permission.delete');
    
    Route::get('/settings',['middleware' => ['permission:settings-management'], 'uses' => 'SettingController@index'])->name('settings.index');
    Route::get('/settings/data', ['middleware' => ['permission:settings-management'], 'uses' =>'SettingController@data'])->name('settings.data');
    Route::get('/setting/new', ['middleware' => ['permission:settings-management'],'uses' => 'SettingController@create'])->name('setting.create');
    Route::post('/setting/new', ['middleware' => ['permission:settings-management'], 'uses' =>'SettingController@store'])->name('setting.store');
    Route::get('/setting/{setting}', ['middleware' => ['permission:settings-management'],'uses' => 'SettingController@edit'])->name('setting.edit');
    Route::post('/setting/{setting}', ['middleware' => ['permission:settings-management'],'uses' => 'SettingController@update'])->name('setting.update');
    Route::delete('/setting/{setting}', ['middleware' => ['permission:settings-management'], 'uses' =>'SettingController@destroy'])->name('setting.delete');

    Route::get('/teams', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@index'])->name('teams.index');
    Route::get('/teams/data', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@data'])->name('teams.data');
    Route::get('/team/new', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@create'])->name('team.create');
    Route::post('/team/new', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@store'])->name('team.store');
    Route::get('/team/{team}', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@edit'])->name('team.edit');
    Route::post('/team/{team}', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@update'])->name('team.update');
    Route::delete('/team/{team}', ['middleware' => ['permission:team-management'], 'uses' => 'TeamController@destroy'])->name('team.delete');

    Route::get('/members/list', ['middleware' => ['permission:member-listing'], 'uses' => 'MemberController@index'])->name('members.index');
    Route::get('/members/data', ['middleware' => ['permission:member-listing'], 'uses' => 'MemberController@data'])->name('members.data');
    Route::get('/member/{member}', ['middleware' => ['permission:member-listing'], 'uses' => 'MemberController@show'])->name('member.show');
    Route::post('/member/{member}', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@update'])->name('member.update');
    Route::delete('/member/{member}', ['middleware' => ['permission:member-listing'], 'uses' => 'MemberController@destroy'])->name('member.delete');

    Route::get('/importmembers', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@import'])->name('members.import');
    Route::post('/importmembers', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@upload'])->name('members.upload');
    Route::post('/members/action', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@action'])->name('members.action');

    Route::get('/members/clubloten', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@clubloten'])->name('members.clubloten');
    Route::post('/members/clubloten', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@clublotenImport'])->name('members.clubloten.upload');
    //Route::post('/members/action', ['middleware' => ['permission:member-management'], 'uses' => 'MemberController@action'])->name('members.action');


    Route::get('/accounts', ['middleware' => ['permission:account-listing'], 'uses' => 'BankAccountController@index'])->name('accounts.index');
    Route::get('/accounts/{bankAccount}', ['middleware' => ['permission:account-listing'], 'uses' => 'BankPaymentsController@index'])->name('account.payments.index');
    Route::get('/accounts/{bankAccount}/data', ['middleware' => ['permission:account-listing'], 'uses' => 'BankPaymentsController@data'])->name('account.payments.data');
    Route::post('/accounts/{bankAccount}/data', ['middleware' => ['permission:account-listing'], 'uses' => 'BankPaymentsController@update'])->name('account.payments.update');

    Route::get('/accounts/requests/{bankAccount}', ['middleware' => ['permission:account-listing'], 'uses' => 'BankRequestsController@index'])->name('account.requests.index');
    Route::get('/accounts/requests/{bankAccount}/data', ['middleware' => ['permission:account-listing'], 'uses' => 'BankRequestsController@data'])->name('account.requests.data');
    Route::post('/accounts/requests/{bankAccount}/data', ['middleware' => ['permission:account-listing'], 'uses' => 'BankRequestsController@update'])->name('account.requests.update');

    Route::get('/cash', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@index'])->name('cash.index');
    Route::get('/cash/{team}', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@edit'])->name('cash.edit');
    Route::get('/cash/{team}/data', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@data'])->name('cash.data');
    Route::post('/cash/{team}/data', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@update'])->name('cash.update');
    Route::post('/cash/{team}/store', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@store'])->name('cash.store');
    Route::delete('/cash/{team}/{cash}', ['middleware' => ['permission:cash-management'], 'uses' => 'CashController@destroy'])->name('cash.delete');

    Route::get('/money/purposes', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@index'])->name('moneypurpose.index');
    Route::get('/money/purposes/data', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@data'])->name('moneypurpose.data');
    Route::get('/money/purposes/new', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@create'])->name('moneypurpose.create');
    Route::post('/money/purposes/new', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@store'])->name('moneypurpose.store');
    Route::get('/money/purposes/{MoneyPurpose}', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@edit'])->name('moneypurpose.show');
    Route::post('/money/purposes/{MoneyPurpose}', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@store'])->name('moneypurpose.update');
    Route::delete('/money/purposes/{MoneyPurpose}', ['middleware' => ['permission:money-purpose-management'], 'uses' => 'MoneyPurposeController@destroy'])->name('moneypurpose.delete');
    
    Route::get('/money/items', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@index'])->name('moneyitem.index');
    Route::get('/money/items/data', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@data'])->name('moneyitem.data');
    Route::get('/money/items/new', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@create'])->name('moneyitem.create');
    Route::post('/money/items/new', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@store'])->name('moneyitem.store');
    Route::get('/money/items/{MoneyItem}', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@edit'])->name('moneyitem.show');
    Route::post('/money/items/{MoneyItem}', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@store'])->name('moneyitem.update');
    Route::delete('/money/items/{MoneyItem}', ['middleware' => ['permission:money-items-management'], 'uses' => 'MoneyItemController@destroy'])->name('moneyitem.delete');
    
    Route::get('/seasons', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@index'])->name('season.index');
    Route::get('/seasons/data', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@data'])->name('season.data');
    Route::get('/seasons/new', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@create'])->name('season.create');
    Route::post('/seasons/new', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@store'])->name('season.store');
    Route::get('/seasons/{season}', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@edit'])->name('season.show');
    Route::post('/seasons/{season}', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@store'])->name('season.update');
    Route::delete('/seasons/{season}', ['middleware' => ['permission:season-management'], 'uses' => 'SeasonController@destroy'])->name('season.delete');

    Route::get('/bunqtab', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@index'])->name('bunqtabs.index');
    Route::get('/bunqtab/data', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@data'])->name('bunqtabs.data');
    Route::get('/bunqtab/new', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@create'])->name('bunqtabs.create');
    Route::post('/bunqtab/new', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@store'])->name('bunqtabs.store');
    Route::get('/bunqtab/{tab}', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@edit'])->name('bunqtabs.edit');
    Route::post('/bunqtab/{tab}', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@update'])->name('bunqtabs.update');
    Route::delete('/bunqtab/{tab}', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@destroy'])->name('bunqtabs.delete');
    Route::get('/bunqtab/{tab}/qrcode/{shortname}', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'BunqTabRequestsController@qrcode'])->name('bunqtabs.qrcode');

    Route::get('/internalpayments', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'InternalPayments@index'])->name('internalpayments.index');
    Route::post('/internalpayments', ['middleware' => ['permission:bunqtab-payment-requests'], 'uses' => 'InternalPayments@store'])->name('internalpayments.store');


});


