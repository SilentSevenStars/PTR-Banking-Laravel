<?php

use App\Http\Controllers\Admin\AdminLoanController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportAnalysisController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\Socialite\ProviderCallbackController;
use App\Http\Controllers\Socialite\ProviderRedirectController;
use App\Http\Controllers\SocialiteController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings/email', [SettingController::class, 'changeEmail']);
    Route::post('/settings/password', [SettingController::class, 'changePassword']);
    Route::post('/settings/disconnect/{provider}', [SettingController::class, 'disconnect']);
});

Route::middleware(['auth', 'admin'])->group(function(){
    Route::get('/admin/', [PageController::class, 'admin'])->name('admin.dashboard');

    Route::get('/admin/loan', [AdminLoanController::class, 'index'])->name('admin.loans');
    Route::get('/admin/loan/list', [AdminLoanController::class, 'list'])->name('admin.loans.list');
    Route::post('/admin/loan/update-status', [AdminLoanController::class, 'updateStatus'])->name('admin.loans.updateStatus');
});
Route::middleware(['auth', 'user'])->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('dashboard');
    Route::post('/transaction/create', [TransactionController::class, 'create'])->name('dashboard.tract');
    Route::get('/user/chart', [UserController::class, 'getDashboardChart']);
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/fetch', [TransactionController::class, 'fetch'])->name('transactions.fetch');
    Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    Route::get('/transactions/receipt/{id}', [TransactionController::class, 'viewReceipt'])->name('transactions.receipt.view');
    Route::get('/transactions/receipt/{id}/download', [TransactionController::class, 'downloadReceipt'])->name('transactions.receipt.download');
    Route::get('/transactions/receipt/{id}/view', [TransactionController::class, 'showReceipt'])->name('transactions.receipt.page');
    Route::get('/report-analysis', [App\Http\Controllers\ReportAnalysisController::class, 'index'])->name('report.analysis');
    Route::get('/report-analysis/export/{type}', [App\Http\Controllers\ReportAnalysisController::class, 'export'])->name('report.analysis.export');
    Route::get('/loan', [LoanController::class, 'index'])->name('loan.index');
    Route::get('/loan/list', [LoanController::class, 'list'])->name('loan.list');
    Route::post('/loan/store', [LoanController::class, 'store'])->name('loan.store');
});

Route::get('/auth/{provider}/redirect', ProviderRedirectController::class)->name('auth.redirect');
Route::get('/auth/{provider}/callback', ProviderCallbackController::class)->name('auth.callback');
require __DIR__.'/auth.php';
