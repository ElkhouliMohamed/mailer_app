<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\EmailLogController;
use App\Http\Controllers\BulkEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/contacts/bulk-send', [BulkEmailController::class, 'sendBulkEmails'])->name('contacts.bulkSend');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('categories', CategoryController::class);
    Route::resource('contacts', ContactController::class)->except(['show']);
    Route::get('/contacts/{contact}/email', [ContactController::class, 'email'])->name('contacts.email');
    Route::post('/contacts/send-email', [ContactController::class, 'sendEmail'])->name('contacts.sendEmail');
    Route::get('/contacts/trashed', [ContactController::class, 'trashed'])->name('contacts.trashed');
    Route::post('/contacts/{id}/restore', [ContactController::class, 'restore'])->name('contacts.restore');
    Route::delete('/contacts/{id}/force-delete', [ContactController::class, 'forceDelete'])->name('contacts.forceDelete');

    Route::resource('smtp_settings', SmtpSettingController::class);

    Route::get('/email-logs', [EmailLogController::class, 'index'])->name('email-logs.index');
    Route::get('/email-logs/{emailLog}', [EmailLogController::class, 'show'])->name('email-logs.show');
    Route::delete('/email-logs/{emailLog}', [EmailLogController::class, 'destroy'])->name('email-logs.destroy');
    Route::post('/email-logs/{id}/restore', [EmailLogController::class, 'restore'])->name('email-logs.restore');
});

require __DIR__.'/auth.php';
