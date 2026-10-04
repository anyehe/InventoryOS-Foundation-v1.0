<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; use App\Http\Controllers\DashboardController; use App\Http\Controllers\Admin\{UserController,ApiKeyController,AuditLogController};
use App\Http\Controllers\Inventory\{ProductController,InventoryController,SetupController,StockController};
use App\Http\Controllers\Sales\SalesController;
use App\Http\Controllers\Purchasing\{SupplierController,PurchaseController};
use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Operations\{ReturnController,ExpenseController};
Route::get('/',fn()=>redirect()->route('dashboard'));
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'showLogin'])->name('login');Route::post('/login',[AuthController::class,'login'])->middleware('throttle:5,1')->name('login.attempt');});
Route::middleware('auth')->group(function(){
 Route::post('/logout',[AuthController::class,'logout'])->name('logout'); Route::get('/dashboard',[DashboardController::class,'index'])->name('dashboard');
 Route::prefix('inventory')->name('inventory.')->middleware('permission:inventory.view')->group(function(){
  Route::get('/',[InventoryController::class,'index'])->name('index'); Route::get('/movements',[InventoryController::class,'movements'])->name('movements');
  Route::get('/products',[ProductController::class,'index'])->name('products.index'); Route::get('/products/create',[ProductController::class,'create'])->middleware('permission:inventory.manage')->name('products.create'); Route::post('/products',[ProductController::class,'store'])->middleware('permission:inventory.manage')->name('products.store'); Route::get('/products/{product}/edit',[ProductController::class,'edit'])->middleware('permission:inventory.manage')->name('products.edit'); Route::patch('/products/{product}',[ProductController::class,'update'])->middleware('permission:inventory.manage')->name('products.update'); Route::delete('/products/{product}',[ProductController::class,'destroy'])->middleware('permission:inventory.manage')->name('products.destroy');
  Route::post('/adjust',[StockController::class,'adjust'])->middleware('permission:inventory.manage')->name('adjust'); Route::post('/transfer',[StockController::class,'transfer'])->middleware('permission:inventory.manage')->name('transfer');
  Route::get('/categories',[SetupController::class,'categories'])->middleware('permission:inventory.manage')->name('categories'); Route::post('/categories',[SetupController::class,'storeCategory'])->middleware('permission:inventory.manage')->name('categories.store');
  Route::get('/brands',[SetupController::class,'brands'])->middleware('permission:inventory.manage')->name('brands'); Route::post('/brands',[SetupController::class,'storeBrand'])->middleware('permission:inventory.manage')->name('brands.store');
  Route::get('/units',[SetupController::class,'units'])->middleware('permission:inventory.manage')->name('units'); Route::post('/units',[SetupController::class,'storeUnit'])->middleware('permission:inventory.manage')->name('units.store');
  Route::get('/warehouses',[SetupController::class,'warehouses'])->middleware('permission:inventory.manage')->name('warehouses'); Route::post('/warehouses',[SetupController::class,'storeWarehouse'])->middleware('permission:inventory.manage')->name('warehouses.store');
 });
 Route::prefix('purchases')->name('purchases.')->middleware('permission:purchases.view')->group(function(){
  Route::get('/',[PurchaseController::class,'index'])->name('index');
  Route::get('/create',[PurchaseController::class,'create'])->middleware('permission:purchases.manage')->name('create');
  Route::post('/',[PurchaseController::class,'store'])->middleware('permission:purchases.manage')->name('store');
  Route::get('/suppliers',[SupplierController::class,'index'])->middleware('permission:purchases.manage')->name('suppliers.index');
  Route::post('/suppliers',[SupplierController::class,'store'])->middleware('permission:purchases.manage')->name('suppliers.store');
  Route::get('/{purchase}',[PurchaseController::class,'show'])->name('show');
  Route::post('/{purchase}/order',[PurchaseController::class,'order'])->middleware('permission:purchases.manage')->name('order');
  Route::post('/{purchase}/receive',[PurchaseController::class,'receive'])->middleware('permission:purchases.manage')->name('receive');
  Route::post('/{purchase}/return',[PurchaseController::class,'returnStock'])->middleware('permission:purchases.manage')->name('return');
 });
 Route::prefix('reports')->name('reports.')->middleware('permission:reports.view')->group(function(){Route::get('/',[ReportController::class,'index'])->name('index');Route::get('/sales.csv',[ReportController::class,'salesCsv'])->middleware('permission:reports.export')->name('sales.csv');});
 Route::prefix('customers')->name('customers.')->middleware('permission:customers.view')->group(function(){
  Route::get('/',[CustomerController::class,'index'])->name('index');
  Route::post('/',[CustomerController::class,'store'])->middleware('permission:customers.manage')->name('store');
  Route::patch('/{customer}',[CustomerController::class,'update'])->middleware('permission:customers.manage')->name('update');
 });
 Route::prefix('operations')->name('operations.')->group(function(){
  Route::prefix('returns')->name('returns.')->middleware('permission:returns.view')->group(function(){
   Route::get('/',[ReturnController::class,'index'])->name('index');
   Route::get('/sale/{sale}/create',[ReturnController::class,'create'])->middleware('permission:returns.manage')->name('create');
   Route::post('/sale/{sale}',[ReturnController::class,'store'])->middleware('permission:returns.manage')->name('store');
  });
  Route::prefix('expenses')->name('expenses.')->middleware('permission:expenses.view')->group(function(){
   Route::get('/',[ExpenseController::class,'index'])->name('index');
   Route::post('/',[ExpenseController::class,'store'])->middleware('permission:expenses.manage')->name('store');
   Route::post('/categories',[ExpenseController::class,'storeCategory'])->middleware('permission:expenses.manage')->name('categories.store');
  });
 });
 Route::prefix('sales')->name('sales.')->middleware('permission:sales.view')->group(function(){ Route::get('/',[SalesController::class,'index'])->name('index'); Route::get('/pos',[SalesController::class,'pos'])->middleware('permission:sales.manage')->name('pos'); Route::post('/',[SalesController::class,'store'])->middleware('permission:sales.manage')->name('store'); Route::get('/{sale}',[SalesController::class,'show'])->name('show'); });
 Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function(){Route::get('/users',[UserController::class,'index'])->name('users.index')->middleware('permission:users.manage');Route::post('/users',[UserController::class,'store'])->name('users.store')->middleware('permission:users.manage');Route::patch('/users/{user}/role',[UserController::class,'updateRole'])->name('users.role')->middleware('permission:users.manage');Route::get('/api-keys',[ApiKeyController::class,'index'])->name('api-keys.index')->middleware('permission:api_keys.manage');Route::post('/api-keys',[ApiKeyController::class,'store'])->name('api-keys.store')->middleware('permission:api_keys.manage');Route::delete('/api-keys/{apiKey}',[ApiKeyController::class,'revoke'])->name('api-keys.revoke')->middleware('permission:api_keys.manage');Route::get('/audit',[AuditLogController::class,'index'])->name('audit.index')->middleware('permission:audit.view');});
});
