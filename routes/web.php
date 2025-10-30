<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('hakkimda', [PageController::class, 'about'])->name('about');
Route::prefix('hizmetlerim')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('services.index');
    Route::get('{service}', [ServiceController::class, 'show'])->name('services.show');
});
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blogs.index');
    Route::get('{blog}', [BlogController::class, 'show'])->name('blogs.show');
});
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');
Route::get('iletisim', [PageController::class, 'contact'])->name('contact');
Route::get('randevu-al', [AppointmentController::class, 'index'])->name('appointments.index');
Route::post('randevu-al', [AppointmentController::class, 'store'])->name('appointments.store');
Route::get('/appointment-slots/by-date', [AppointmentController::class, 'getByDate'])
    ->name('appointment-slots.by-date');

Route::post('submit-message', ContactMessageController::class)->name('submit-message');

