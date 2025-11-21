<?php

use App\Http\Controllers\UpcomingBillsController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\DailyLimitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

// Expenses route
Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses');
Route::post('/expenses/add-daily', [ExpensesController::class, 'addDaily'])->name('expenses.addDaily');
Route::post('/expenses/add-limit',[DailyLimitController::class,'addDailyLimit'])->name('expenses.addDailyLimit');

Route::post('/allowance/add-allowance',[AllowanceController::class,'addAllowances'])->name('allowance.addallowance');

Route::get('/bills/{id}', [UpcomingBillsController::class, 'getBill'])->name('bills.get');
Route::put('/bills/{id}/description', [UpcomingBillsController::class, 'updateDescription'])->name('bills.updateDescription');
Route::post('/upcoming-bills/store', [UpcomingBillsController::class, 'store'])->name('upcoming-bills.store');


