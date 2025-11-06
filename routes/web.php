<?php

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

