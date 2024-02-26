<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JewelryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SafeBoxController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Setting\InvoiceController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SupplierController;
use App\Models\Costumer;
use App\Models\Jewelry;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return to_route('dashboard');
});

Route::get('/dashboard', function () {
    $jewelriesCount = Jewelry::count();
    $suppliersCount = Supplier::count();
    $employeesCount = User::whereNot('role', 'ADMIN')->count();
    $costumersCount = Costumer::count();

    return Inertia::render(
        'Dashboard',
        compact('jewelriesCount', 'suppliersCount', 'employeesCount', 'costumersCount')
    );
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Sales
    Route::resource('sales', SaleController::class);
    Route::get('sales/{sale}/print', [SaleController::class, 'print'])->name('sales.print');

    // Service
    Route::resource('services', ServiceController::class);
    Route::get('services/{service}/print', [ServiceController::class, 'print'])->name('services.print');

    // Orders
    Route::resource('orders', OrderController::class);
    Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');

    // Admin
    Route::middleware('can:ADMIN')->group(function () {
        Route::resource('employees', EmployeeController::class)->except('show');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('safeboxes', SafeBoxController::class)->except('show');
        Route::resource('suppliers', SupplierController::class)->except('show');
        Route::resource('costumers', CostumerController::class)->except('show');
        Route::resource('prices', PriceController::class)->except('show');
        Route::resource('jewelries', JewelryController::class)->except('show');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Report
    Route::get('/report', [ReportController::class, 'index'])->name('report.index');
    Route::get('/report/result', [ReportController::class, 'result'])->name('report.result');

    // Setting
    Route::get('/setting', [SettingController::class, 'index'])->name('setting');
    Route::post('/setting/invoice', InvoiceController::class)->name('setting.invoice.update');
});

require __DIR__ . '/auth.php';
