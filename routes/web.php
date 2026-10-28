<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ListingController as AdminListingController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Admin\HospitalApprovalController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Hospital\AppointmentController as HospitalAppointmentController;
use App\Http\Controllers\Hospital\DashboardController as HospitalDashboardController;
use App\Http\Controllers\Hospital\DoctorController as HospitalDoctorController;
use App\Http\Controllers\Hospital\ProfileController as HospitalProfileController;
use App\Http\Controllers\Hospital\RegistrationController as HospitalRegistrationController;
use App\Http\Controllers\Hospital\SubscriptionController as HospitalSubscriptionController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Livewire\Auth\Access;
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
    Route::get('/login', Access::class)->name('login');
    Route::get('/register', Access::class)->name('register');
    Route::get('/forgot-password', Access::class)->name('password.request');
    Route::get('/register-hospital', [HospitalRegistrationController::class, 'create'])->name('hospital.register');
    Route::post('/register-hospital', [HospitalRegistrationController::class, 'store'])->middleware('throttle:6,1')->name('hospital.register.store');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/notifications/{id}', [NotificationController::class, 'open'])->name('notifications.open');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    Route::middleware('role:hospital_admin')->prefix('hospital')->name('hospital.')->group(function () {
        Route::get('/profile', [HospitalProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [HospitalProfileController::class, 'update'])->name('profile.update');
        Route::get('/subscription', HospitalSubscriptionController::class)->name('subscription');

        Route::middleware('hospital.access')->group(function () {
            Route::get('/', HospitalDashboardController::class)->name('dashboard');
            Route::get('/appointments', [HospitalAppointmentController::class, 'index'])->name('appointments');
            Route::get('/doctors', [HospitalDoctorController::class, 'index'])->name('doctors.index');
            Route::get('/doctors/create', [HospitalDoctorController::class, 'create'])->name('doctors.create');
            Route::post('/doctors', [HospitalDoctorController::class, 'store'])->name('doctors.store');
            Route::get('/doctors/{doctor}/edit', [HospitalDoctorController::class, 'edit'])->name('doctors.edit');
            Route::put('/doctors/{doctor}', [HospitalDoctorController::class, 'update'])->name('doctors.update');
            Route::patch('/doctors/{doctor}/toggle', [HospitalDoctorController::class, 'toggle'])->name('doctors.toggle');
            Route::patch('/doctors/{doctor}/away', [HospitalDoctorController::class, 'away'])->name('doctors.away');
        });
    });

    Route::middleware('role:patient')->group(function () {
        Route::get('/book', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/book', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/appointments/{appointment:reference}/confirmation', [AppointmentController::class, 'confirmation'])->name('appointments.confirmation');
        Route::post('/doctors/{doctor}/rate', [DoctorController::class, 'rate'])->name('doctors.rate');
        Route::patch('/appointments/{appointment:reference}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', AdminDashboardController::class)->name('dashboard');
        Route::get('/hospitals', [AdminListingController::class, 'hospitals'])->name('hospitals');
        Route::patch('/hospitals/{hospital}/{action}', HospitalApprovalController::class)->whereIn('action', ['approve', 'suspend', 'reactivate', 'extend'])->name('hospitals.action');
        Route::get('/doctors', [AdminListingController::class, 'doctors'])->name('doctors');
        Route::get('/appointments', [AdminListingController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/export', [AdminListingController::class, 'exportAppointments'])->name('appointments.export');
        Route::get('/users', [AdminListingController::class, 'users'])->name('users');
        Route::get('/messages', [AdminListingController::class, 'messages'])->name('messages');
    });

    Route::patch('/appointments/{appointment:reference}/status', [AppointmentController::class, 'updateStatus'])
        ->middleware('role:doctor,hospital_admin,super_admin')
        ->name('appointments.status');
});
