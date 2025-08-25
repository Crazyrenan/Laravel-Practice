<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\api\userController;
use App\Http\Controllers\reportController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\MenuSubmenuController;
use SebastianBergmann\CodeCoverage\Report\Xml\Report;

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


Route::get('/reportvendor', function () {
    return view('Reports.spendingReport');
});

Route::get('/categoryreport', function () {
    return view('Reports.categoryreport');
});

Route::get('/exportreport', function () {
    $projects = DB::table('projects')->select('id', 'name')->get();
    
    return view('Reports.ExportReport', compact('projects'));
});

Route::get('/reported', [userController::class, 'reporting']);






// Show the menus page (Blade view)
Route::get('/menus', [MenuController::class, 'view'])->name('menus.view');

// Get menus data in JSON (for DataTables / AJAX)
Route::get('/menus/data', [MenuController::class, 'index'])->name('menus.index');

// CRUD actions
Route::post('/menus/store', [MenuController::class, 'store'])->name('menus.store');
Route::post('/menus/update/{id}', [MenuController::class, 'update'])->name('menus.update');
Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');



// Show the submenus page (Blade view)
Route::get('/submenus', [SubMenuController::class, 'view'])->name('submenus.view');

// Get submenus data in JSON (for DataTables / AJAX)
Route::get('/submenus/data', [SubMenuController::class, 'index'])->name('submenus.index');

// CRUD actions
Route::post('/submenus/store', [SubMenuController::class, 'store'])->name('submenus.store');
Route::post('/submenus/update/{id}', [SubMenuController::class, 'update'])->name('submenus.update');
Route::delete('/submenus/{id}', [SubMenuController::class, 'destroy'])->name('submenus.destroy');




// Merged menus + submenus page
Route::get('/menus-submenus', [MenuSubmenuController::class, 'viewMerged'])->name('menus_submenus.view');


//import data
Route::get('/reports/download-template', [ReportController::class, 'downloadPembelianTemplate'])->name('reports.downloadPembelianTemplate');
Route::post('/reports/import-pembelian', [ReportController::class, 'importPembelian'])->name('reports.importPembelian');
Route::post('/purchase-orders/update-import', [ReportController::class, 'updatePembelian'])->name('reports.updatePembelian');

// Purchase Controller


Route::get('/pembelian-master', [userController::class, 'pembelianMasterView']);

Route::post('/pembelian/delete', [userController::class, 'deletePembelian']);



Route::get('/testing', [PurchaseController::class, 'index']);
