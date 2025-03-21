<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SmtpSettingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Contacts (Using resource route with additional custom routes)
    Route::resource('contacts', ContactController::class)->except(['show']); // Exclude show if not needed
    Route::get('/contacts/{contact}/email', [ContactController::class, 'email'])->name('contacts.email');
    Route::post('/contacts/send-email', [ContactController::class, 'sendEmail'])->name('contacts.sendEmail');
    Route::post('/contacts/bulk-send', [ContactController::class, 'bulkSend'])->name('contacts.bulkSend');
    Route::get('/contacts/trashed', [ContactController::class, 'trashed'])->name('contacts.trashed');
    Route::post('/contacts/{id}/restore', [ContactController::class, 'restore'])->name('contacts.restore');
    Route::delete('/contacts/{id}/force-delete', [ContactController::class, 'forceDelete'])->name('contacts.forceDelete');

    // SMTP Settings
    Route::resource('smtp_settings', SmtpSettingController::class);
});

require __DIR__.'/auth.php';
