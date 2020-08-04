<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('admin.home');
});

Route::prefix('admin')->group(function () {
    Auth::routes();
    Route::middleware('auth')->group(function () {
        Route::get('/home', 'HomeController@index')->name('admin.home');
        Route::resource('/classes', 'SClassController', ['as' => 'admin'])->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::resource('/sections', 'SectionController', ['as' => 'admin'])->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::resource('/students', 'StudentController', ['as' => 'admin'])->only([
            'index', 'store', 'update', 'destroy'
        ]);
        Route::post('/students/{user_id}/assign', 'StudentController@assign')->name('admin.students.assign');
        Route::post('/students/{user_id}/payment', 'StudentController@payment')->name('admin.students.payment');
        Route::post('/students/{student_payment_id}/invoice', 'StudentController@invoice')->name('admin.students.invoice');
    });
});
