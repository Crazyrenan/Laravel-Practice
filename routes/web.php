<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\api\userController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('home', function () {
    return view('home');
});

Route::get('/status', function () {
    return view('status');
});

Route::get('/vendor', function () {
    return view('vendor');
});

Route::get('/order', function () {
    return view('order');
});

Route::get('/product', function () {
    return view('product');
});

Route::get('/status2', function () {
    return view('status2');
});

Route::get('/product2', function () {
    return view('product2');
});

Route::get('/order2', function () {
    return view('order2');
});

Route::get('/vendor2', function () {
    return view('vendor2');
});

Route::get('/vendor_chart', function () {
    return view('vendor_chart');
});

Route::get('/profit', function () {
    return view('profit');
});

Route::get('/profitcategory', function () {
    return view('profit_category');
});

Route::get('/vendorjoin', function () {
    return view('vendor_join');
});

Route::get('/vendormaster', function () {
    return view('Master.vendormaster');
});

Route::get('/requestmaster', function () {
    return view('Master.requestmaster');
});

Route::get('/projectmaster', function () {
    return view('Master.projectmaster');
});

Route::get('/pembelianmaster', function () {
    return view('Master.pembelianmaster');
});

Route::get('/pembelian-search', function () {
    return view('pembelian_search'); // New blade file
});



Route::get('/pembelian-master', [userController::class, 'pembelianMasterView']);

Route::post('/pembelian/delete', [userController::class, 'deletePembelian']);

Route::get('/pembelianmaster', function () {
    $vendors = DB::table('vendors')->get();
    $projects = DB::table('projects')->get();
    $requests = DB::table('request')->get();

    return view('Master.pembelianmaster', compact('vendors', 'projects', 'requests'));
});


Route::get('/testing', [PurchaseController::class, 'index']);
