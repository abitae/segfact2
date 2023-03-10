<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//   return view('welcome');
// });

Route::redirect('/', 'login');

Auth::routes(["register" => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth'] ], function () {

  Route::post('/asign-permission-to-role', 'RolePermissionController@asignPermissionToRol');


  Route::view('/banks', 'backend.banks')->name('banks');
  Route::get('/list-banks', 'BankController@list');
  Route::post('/bank-store', 'BankController@store');
  Route::post('/bank-update', 'BankController@update');

  Route::view('/customers', 'backend.customers')->name('customers');
  Route::get('/list-customers','CustomerController@listCustomers');
  Route::get('/customer-data','CustomerController@customerData');
  Route::post('/customer-store','CustomerController@store');
  Route::post('/customer-update','CustomerController@update');

  Route::view('/contacts', 'backend.contacts')->name('contacts');
  Route::get('/list-contacts','ContactController@listContacts');
  Route::get('/contact-data','ContactController@contactData');
  Route::get('/get-contact-by-nro-phone','ContactController@getContactByNroNPhone');
  Route::post('/contact-store','ContactController@store');
  Route::post('/contact-update','ContactController@update');

  Route::get('/dashboard','DashboardController@index')->name('dashboard');

  Route::get('document-states', 'GeneralController@getDocumentStates');

  Route::view('/enterprises', 'backend.enterprises')->name('enterprises');
  Route::get('/list-enterprises','EnterpriseController@listEnterprises');
  Route::post('/enterprise-store','EnterpriseController@store');
  Route::post('/enterprise-update','EnterpriseController@update');
  Route::get('/get-branches-according-enterprise','EnterpriseController@getBranches');

  Route::post('/generate-documents', 'SaleController@generateDocuments');

  Route::get('history/{nroComprobante}', 'OperationVoucherTrackingController@history');

  Route::view('/licenses', 'backend.licenses')->name('licenses');
  Route::get('/list-licenses','LicenseController@listLicenses');
  Route::post('/license-store','LicenseController@store');
  Route::post('/license-update','LicenseController@update');

  Route::view('/profile', 'backend.profile')->name('profile');
  Route::get('/profile-data', 'UserController@profileData');
  Route::post('/profile-update', 'UserController@updateProfile');

  Route::get('/report','ReportController@index')->name('report');

  Route::view('/roles','backend.roles')->name('roles');
  Route::get('/list-roles', 'RolePermissionController@listRoles');
  Route::post('/role-store', 'RolePermissionController@storeRole');
  Route::view('/assing-permission', 'backend.assing-permission')->name('assing-permission');
  Route::get('/list-permissions', 'RolePermissionController@listPermissions');
  Route::post('/assing-role-to-user', 'RolePermissionController@assingRoleToUser');

  Route::view('/sale-management', 'backend.sale')->name('salesManagement');
  Route::get('/list-sale','SaleController@listSale');
  Route::get('/sale-data','SaleController@data');
  Route::get('/sale-search-synchronize','SaleController@searchToSynchronize');
  Route::post('/sale-serie-delete','SaleController@deleteSerie');
  Route::post('/sale-store','SaleController@store');
  Route::post('/sale-update','SaleController@update');
  Route::get('/sale-download-attach-document','SaleController@downloadAttachDocument');
  Route::get('/download-document','SaleController@downloadDocument');
  Route::get('/sale-export-excel','SaleController@exportExcel');
  Route::get('/sale-export-pdf','SaleController@exportPdf');

  Route::view('/tracking-receipts', 'backend.tracking-of-receipts')->name('trackingOfReceipts');
  Route::get('/list-tracking-receipts','SeguimientoComprobanteController@listTrackingReceipts');
  Route::post('/tracking-receipts-store','SeguimientoComprobanteController@store');
  Route::post('/tracking-receipts-update','SeguimientoComprobanteController@update');
  Route::post('/tracking-receipts-update-status','SeguimientoComprobanteController@updateStatus');
  Route::post('/tracking-mark-as-arranged','SeguimientoComprobanteController@markAsArranged');
  Route::get('/tracking-receipts-export-excel','SeguimientoComprobanteController@exportExcel');
  Route::get('/tracking-receipts-export-pdf','SeguimientoComprobanteController@exportPdf');
  Route::get('calculate-statistics', 'SeguimientoComprobanteController@calculateStatistics');


  Route::view('/shopping-management', 'backend.shopping')->name('shoppingManagement');
  Route::get('/list-shopping','ShoppingController@listShopping');
  Route::get('/shopping-data','ShoppingController@data');
  Route::get('/shopping-search-synchronize','ShoppingController@searchToSynchronize');
  Route::post('/shopping-serie-delete','ShoppingController@deleteSerie');
  Route::post('/shopping-store','ShoppingController@store');
  Route::post('/shopping-update','ShoppingController@update');
  Route::get('/shopping-export-excel','ShoppingController@exportExcel');
  Route::get('/shopping-export-pdf','ShoppingController@exportPdf');

  Route::view('/suppliers', 'backend.suppliers')->name('suppliers');
  Route::get('/list-suppliers','SupplierController@listSuppliers');
  Route::get('/supplier-data','SupplierController@supplierData');
  Route::post('/supplier-store','SupplierController@store');
  Route::post('/supplier-update','SupplierController@update');

  Route::view('/series', 'backend.series')->name('series');
  Route::get('/search-by-name-serie','SeriesController@searchByNameSerie');
  Route::get('/serie-search-advanced','SeriesController@searchAdvanced');
  Route::get('/list-series','SeriesController@listSeries');
  Route::post('/series-store','SeriesController@store');
  Route::post('/series-update','SeriesController@update');

  Route::get('/synchronization','SynchronizationController@index');
  Route::post('/synchronize-purchases','SynchronizationController@synchronizePurchases');

  Route::post('/synchronize-sale','SynchronizationController@synchronizeSales');

  Route::view('/users', 'backend.users')->name('users');
  Route::get('/list-users','UserController@listUsers');
  Route::get('/list-sellers','UserController@listSellers');
  Route::post('/user-store','UserController@store');
  Route::post('/user-update','UserController@update');

  Route::view('quotate', 'backend.quotate.index')->name('quotate');
  Route::view('quotate-create', 'backend.quotate.create')->name('quotate.create');
  Route::get('quotate-list', 'QuotationController@list');

  Route::get('/tipo-cambio', 'SunatController@tipoCambio');

  Route::post('/annulment-voucher', 'SeguimientoComprobanteController@annulmentVoucher');

  Route::post('/update-status',"GeneralController@updateStatus");
  Route::get('/list-all',"GeneralController@listAll");
  Route::get('/list-all-actives',"GeneralController@listAllActives");

  Route::get('/generate-document-cci', 'GeneralController@generateDocumentCci');
  Route::get('/generate-document-letter-warranty', 'GeneralController@generateDocumentLetterWarranty');
});

Route::get('/is-user-check',"GeneralController@isUserCheck");

// Route::get('storage-link', function() {
//   Artisan::call('storage:link');
//   return 'Successfull storage-link';
// });

// Route::get('cache-clear', function() {
//   Artisan::call('cache:clear');
//   return "Successfull cache-clear";
// });
