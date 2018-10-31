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

Route::get('/', function () {
    return view('welcome');
});

//under construction
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::group(['middleware' => 'auth.check'], function () {
    //common routes
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/user/profile', 'HomeController@profileView')->name('user.profile');
    Route::post('/user/profile', 'HomeController@profileUpdate')->name('user.profile.action');

    //user routes
    Route::group(['middleware' => ['user.role:0,1,2']], function () {
        //branch
        Route::resource('branch', 'BranchController');

        //product
        Route::resource('product', 'ProductController');

        //student
        Route::resource('student', 'StudentController');

        //staff
        Route::resource('employee', 'EmployeeController');

        //purchases
        Route::get('/purchase/{id}/invoice', 'PurchaseController@invoice')->name('purchase.invoice');
        Route::resource('purchase', 'PurchaseController');

        //sales
        Route::get('/sale/{id}/invoice', 'SaleController@invoice')->name('sale.invoice');
        Route::resource('sale', 'SaleController');

        //expenses
        Route::resource('expense', 'ExpenseController');

        //vouchers
        Route::resource('voucher', 'VoucherController');

        //reports
        Route::get('reports/student-statement', 'ReportController@studentStatement')->name('report.student-statement');
        Route::get('reports/credit-list', 'ReportController@creditList')->name('report.credit.list');

        //ajax urls
        Route::group(['middleware' => 'is.ajax'], function () {
            Route::get('/ajax/student/details/{id}', 'StudentController@getDetails')->name('ajax.student.details');
            Route::get('/ajax/last/sale', 'SaleController@getLastSale')->name('ajax.lastsale.bybranch');
        });
    });
});
