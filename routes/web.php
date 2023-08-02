<?php

use App\Http\Controllers\CaratController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JewelryController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SafeBoxController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupplierController;
use App\Models\Costumer;
use App\Models\Jewelry;
use App\Models\Price;
use App\Models\Supplier;
use App\Models\User;
use Carbon\CarbonPeriod;
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
    $jewelries_count = Jewelry::count();
    $suppliers_count = Supplier::count();
    $employees_count = User::whereNot('role', 'ADMIN')->count();
    $costumers_count = Costumer::count();

    $latest_price = Price::latest()->first();

    return Inertia::render('Dashboard', compact('jewelries_count', 'suppliers_count', 'employees_count', 'costumers_count', 'latest_price'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('service', [ServiceController::class, 'index'])->name('service.index');

    Route::resource('employees', EmployeeController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('safeboxes', SafeBoxController::class)->except('show');
    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::resource('costumers', CostumerController::class)->except('show');
    Route::resource('prices', PriceController::class)->except('show');
    Route::resource('jewelries', JewelryController::class)->except('show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
