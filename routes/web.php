<?php

use App\Http\Controllers\Admin\BalanceController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ExpenseCategoryController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseReturnController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SaleReturnController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\CustomerReceiptController;
use App\Http\Controllers\Feature\ScanController;
use App\Http\Controllers\SupplierReceiptController;
use App\Services\Admin\SupplierService;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->controller(SessionController::class)->prefix('auth')->name('auth.')->group(function () {
    Route::get('login', 'login')->name('login');
    Route::post('login', 'handleLogin')->name('login.handle');
    Route::get('register', 'register')->name('register');
    Route::post('register', 'handleRegister')->name('register.handle');
});

Route::middleware('auth')->get('/', function () {
    return view('pages.admin.brand.index');
})->name('home');

Route::post('scan', [ScanController::class, 'scan'])->name('scan')->middleware('auth');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    Route::get('', function () {})->name('dashboard');

    /**
     * Brands resource.
     */
    Route::controller(BrandController::class)->prefix('brand')->name('brand.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
    /**
     * Products resource.
     */
    Route::controller(ProductController::class)->prefix('product')->name('product.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Suppliers resource.
     */
    Route::controller(SupplierService::class)->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Customer resource.
     */
    Route::controller(CustomerController::class)->prefix('customer')->name('customer.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Purchase resource.
     */
    Route::controller(PurchaseController::class)->prefix('purchase')->name('purchase.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Purchase return resource.
     */
    Route::controller(PurchaseReturnController::class)->prefix('purchase_return')->name('purchase_return.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Sale resource.
     */
    Route::controller(SaleController::class)->prefix('sale')->name('sale.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Sale return resource.
     */
    Route::controller(SaleReturnController::class)->prefix('sale_return')->name('sale_return.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });


    /**
     * Supplier Balance
     */
    Route::prefix('balance')->name('balance.')->controller(BalanceController::class)->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/edit/{id}', 'edit')->name('edit');   // fixed
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Ledger board
     */
    Route::prefix('ledger')->name('ledger.')->controller(LedgerController::class)->group(function () {
        Route::get('', 'index')->name('index');
    });

    /**
     * Expense Categories resource.
     */
    Route::controller(ExpenseCategoryController::class)->prefix('expense_category')->name('expense_category.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Expense  resource.
     */
    Route::controller(ExpenseController::class)->prefix('expense')->name('expense.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     *Customer Receipt  resource.
     */
    Route::controller(CustomerReceiptController::class)->prefix('receipt')->name('receipt.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });

    /**
     * Supplier receipt  resource.
     */
    Route::controller(SupplierReceiptController::class)->prefix('supplier_receipt')->name('supplier_receipt.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/delete', 'destroy')->name('destroy');
    });
});
