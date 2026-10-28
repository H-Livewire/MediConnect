<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'sendMessage'])->middleware('throttle:6,1')->name('contact.send');
Route::get('/afya-smart', [PageController::class, 'afyaSmart'])->name('afya-smart');

Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');
Route::get('/doctors/{doctor}/slots', [DoctorController::class, 'slots'])->name('doctors.slots');

Route::get('/hospitals', [HospitalController::class, 'index'])->name('hospitals.index');
Route::get('/hospitals/{hospital}', [HospitalController::class, 'show'])->name('hospitals.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->middleware('throttle:10,1');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::middleware('role:patient')->group(function () {
        Route::get('/book', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/book', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment:reference}/confirmation', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
        Route::patch('/appointments/{appointment:reference}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    Route::patch('/appointments/{appointment:reference}/status', [AppointmentController::class, 'updateStatus'])
        ->middleware('role:doctor,hospital_admin,super_admin')
        ->name('appointments.status');
});
