<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PembelianController;
use App\Http\Controllers\Api\JoinController;
use App\Http\Controllers\api\userController;

Route::get('/pembelian/pembelians', [JoinController::class, 'showPembelian']);
Route::get('/pembelian', [JoinController::class, 'pembelianView']);
Route::get('/projects/project', [JoinController::class, 'showProjects']);
Route::get('/vendors/columns', [JoinController::class, 'getVendorColumns']);



Route::get('/pembelian-master', [userController::class, 'pembelianMasterView']);
Route::get('/project-master', [userController::class, 'projectMasterView']);
Route::get('/request-master', [userController::class, 'requestMasterView']);
Route::get('/vendor-master', [userController::class, 'vendorMasterView']);
Route::post('/pembelian/insert', [userController::class, 'insertpembelian']);



Route::get('/projects/columns', [JoinController::class, 'getProjectColumns']);
Route::get('/projects/search', [JoinController::class, 'searchProjects']);
Route::post('/projects/insert', [JoinController::class, 'insert']);
Route::delete('/projects/delete/{id}', [JoinController::class, 'deleteProject']);
Route::put('/projects/update/{id}', [JoinController::class, 'updateProject']);


Route::get('/vendors/search', [JoinController::class, 'searchVendors']);
Route::get('/vendors/columns', [JoinController::class, 'getAllVendors']);
Route::post('/vendors/insert', [JoinController::class, 'insertVendor']);
Route::delete('/vendors/delete/{id}', [JoinController::class, 'deleteVendor']);
Route::put('/vendors/update/{id}', [JoinController::class, 'updateVendor']);


Route::get('/requests/columns', [JoinController::class, 'getRequestColumns']);
Route::get('/requests/search', [JoinController::class, 'searchRequest']);
Route::post('/request/insert', [JoinController::class, 'insertrequest']);
Route::delete('/requests/delete/{id}', [JoinController::class, 'deleteRequest']);
Route::put('/requests/update/{id}', [JoinController::class, 'updateRequest']);






Route::get('/pembelian/MostOrderMonth', [PembelianController::class, 'mostOrdersMonth']);

Route::get('/pembelian/Vendor', [PembelianController::class, 'TotalVendor']);

Route::get('/pembelian/Selisih', [PembelianController::class, 'selisihbeli']);

Route::get('/pembelian/barang', [PembelianController::class, 'totalprojectproduct']);

Route::get('/pembelian/status2', [PembelianController::class, 'status2']);

Route::get('/pembelian/status2/detail/{status}', [PembelianController::class, 'getStatusDetail']);

Route::get('/pembelian/ordermonth2', [PembelianController::class, 'OrderMonth2']);

Route::get('/pembelian/month2/detail/{month}', [PembelianController::class, 'DetailsByMonth']);

Route::get('/pembelian/vendor2', [PembelianController::class, 'TotalVendor2']);

Route::get('/pembelian/vendor2/detail/{vendor_id}/{month?}', [PembelianController::class, 'DetailByVendor']);

Route::get('/pembelian/vendor-by-month/{month?}', [PembelianController::class, 'TotalVendorByMonth']);

Route::get('/pembelian/selisih2', [PembelianController::class, 'selisih2']);

Route::get('/pembelian/product-per-product', [PembelianController::class, 'ProfitByProduct']);

Route::get('/pembelian/product-profit-detail/{item_name}', [PembelianController::class, 'ProductProfitDetail']);

Route::get('/pembelian/vendor-by-range', [PembelianController::class, 'VendorByRange']);

Route::get('/pembelian/profitcategory/{category}', [PembelianController::class, 'ProfitByCategory']);

Route::get('/pembelian/categories', [PembelianController::class, 'getCategories']);

Route::get('/pembelian/getitemname/{category}', [PembelianController::class, 'getitemsname']);

Route::get('/join/vendors', [JoinController::class, 'getAllVendors']);

Route::get('/join/detail/{vendor_id}', [JoinController::class, 'detailAll']);

Route::get('/pembelian/columns', [JoinController::class, 'getPembelianColumns']);

Route::get('/pembelian/search', [JoinController::class, 'searchPembelian']);

