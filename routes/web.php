<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', 'App\Http\Controllers\AdminController@Login')->name('Login');
Route::get('/logout', 'App\Http\Controllers\AdminController@logout')->name('logout');
Route::get('/dashboard', 'App\Http\Controllers\AdminController@dashboard')->name('dashboard');
Route::get('/dashboard/mastermerchant', 'App\Http\Controllers\AdminController@MasterMerchant')->name('MasterMerchant');
Route::post('/dashboard/mastermenu', 'App\Http\Controllers\AdminController@MasterMenu')->name('MasterMenu');
Route::post('/dashboard/kategori', 'App\Http\Controllers\AdminController@MasterKategori')->name('MasterKategori');

Route::post('/postlogin', 'App\Http\Controllers\AdminController@postlogin');
Route::post('/postmerchant', 'App\Http\Controllers\AdminController@postmerchant');
Route::post('/postkategori', 'App\Http\Controllers\AdminController@postkategori');
Route::post('/postmenu', 'App\Http\Controllers\AdminController@postmenu');

Route::post('/dashboard/detailtransaksi', 'App\Http\Controllers\TransactionController@DetailTransaksi')->name('DetailTransaksi');
Route::post('/dashboard/detailtransaksimerchant', 'App\Http\Controllers\TransactionController@ReportMerchantTransaction')->name('DetailTransaksiMerchant');
Route::get('/reportTransaction', 'App\Http\Controllers\TransactionController@ReportTransaction')->name('ReportTransaction');
Route::get("/dashboard/reportTransaction", function(){
    return view('sb-admin-2/mastertransaksi');
 });
