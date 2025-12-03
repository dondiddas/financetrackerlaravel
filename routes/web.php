<?php

use App\Http\Controllers\UpcomingBillsController;
use App\Http\Controllers\AllowanceController;
use App\Http\Controllers\DailyLimitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// Dashboard route
// Protected app routes
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportsController;

Route::middleware('auth')->group(function () {
    // Settings
    Route::get('/settings', [\App\Http\Controllers\UserProfileController::class, 'showSettings'])->name('settings');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/recent-transactions', [DashboardController::class, 'recentTransactionsJson'])->name('recent.transactions');

    // Expenses
    Route::get('/expenses', [ExpensesController::class, 'index'])->name('expenses');
    Route::post('/expenses/add-daily', [ExpensesController::class, 'addDaily'])->name('expenses.addDaily');
    Route::post('/expenses/add-limit',[DailyLimitController::class,'addDailyLimit'])->name('expenses.addDailyLimit');

    // Allowance
    Route::post('/allowance/add-allowance',[AllowanceController::class,'addAllowances'])->name('allowance.addallowance');

    // Bills
    Route::get('/bills/{id}', [UpcomingBillsController::class, 'getBill'])->name('bills.get');
    Route::get('/bills', [UpcomingBillsController::class, 'index'])->name('bills.index');
    Route::post('/bills/{id}/pay', [UpcomingBillsController::class, 'markPaid'])->name('bills.pay');
    Route::put('/bills/{id}/description', [UpcomingBillsController::class, 'updateDescription'])->name('bills.updateDescription');
    Route::post('/upcoming-bills/store', [UpcomingBillsController::class, 'store'])->name('upcoming-bills.store');

    // Transactions
    Route::get('/transactions', [ExpensesController::class, 'index'])->name('transactions.index');

    // Budgets
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportsController::class, 'exportCsv'])->name('reports.export');

    // Profile update
    Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');

    // Logout route
    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Logout route (simple implementation) and profile update
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::put('/profile', [\App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');


