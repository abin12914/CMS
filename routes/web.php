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
        //course
        Route::resource('course', 'CourseController');

        //batch
        Route::resource('batch', 'BatchController');

        //student
        Route::resource('student', 'StudentController');

        //address
        Route::resource('address', 'AddressController');

        //authority
        Route::resource('authority', 'AuthorityController');

        //certificate
        Route::get('/certificate/issue', 'CertificateController@issue')->name('certificate.issue');
        Route::resource('certificate', 'CertificateController');

        //certification
        Route::resource('certification', 'CertificationController');

        //ajax urls
        Route::group(['middleware' => 'is.ajax'], function () {
            Route::post('/ajax/student/details', 'StudentController@getStudentDetails')->name('ajax.student.details');
        });
    });
});
