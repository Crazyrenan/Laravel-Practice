<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PembelianController;
use App\Http\Controllers\Api\JoinController;
use App\Http\Controllers\api\userController;
use App\Http\Controllers\reportController;

use function Pest\Laravel\get;

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

// Report by Vendor
Route::get('/report/pembelian/vendor', [userController::class, 'reportByVendor'])->name('report.vendor');
// Report by Project
Route::get('/report/pembelian/project', [userController::class, 'reportByProject'])->name('report.project');
// Report by Month
Route::get('/report/pembelian/month', [userController::class, 'reportByMonth'])->name('report.month');
// Report by Category
Route::get('/report/pembelian/category', [userController::class, 'reportByCategory'])->name('report.category');
// Report by Request
Route::get('/report/pembelian/request', [userController::class, 'reportByRequest'])->name('report.request');

Route::get('/report/data', [userController::class, 'getReportData'])->name('report.data');

Route::get('/vendor-spending', [reportController::class, 'totalSpendingByVendor']);

Route::get('/vendor-spending/{vendor_id}', [reportController::class, 'monthlySpendingByVendor']);

Route::get('/vendor-spending/{vendor_id}/{month}', [reportController::class, 'getMonthlyDetail']);

Route::get('/quantity-by-category', [reportController::class, 'getQuantityByCategory']);

Route::get('/monthly-quantity-category', [ReportController::class, 'getQuantityByCategoryPerMonth']);

Route::get('/item-details-by-month-category', [ReportController::class, 'getItemDetailsByMonthAndCategory']);


Route::get('/expected-delivery-date', [reportController::class, 'getPurchaseReport']);

Route::get('/purchase-report', [reportController::class, 'purchaseReportVendor']);



Route::post('/pembelian/delete', [userController::class, 'deletePembelian']);





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

