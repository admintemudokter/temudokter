<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\RevenueHistoryController;
use App\Http\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Doctor\AuthController as DoctorAuthController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\ConsultationController as DoctorConsultationController;
use App\Http\Controllers\Patient\ConsultationController as PatientConsultationController;
use App\Http\Controllers\Patient\PaymentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// PUBLIC — Landing Page & History
// =============================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/riwayat', [HomeController::class, 'historyForm'])->name('history.form');
Route::post('/riwayat', [HomeController::class, 'checkHistory'])->name('history.check')->middleware('throttle:10,1');
Route::get('/riwayat/{id}', [HomeController::class, 'showHistory'])->name('history.show')->middleware('signed');

// Document Verification Route
Route::get('/verify/{type}/{invoice}', [App\Http\Controllers\VerificationController::class, 'show'])->name('verify.document')->middleware('throttle:30,1');

// =============================================================================
// PATIENT FLOW (no auth required)
// =============================================================================
Route::prefix('konsultasi')->name('patient.')->group(function () {
    Route::get('/mulai', [PatientConsultationController::class, 'create'])->name('create');
    Route::post('/mulai', [PatientConsultationController::class, 'store'])->name('store')->middleware('throttle:3,1');
    
    // Survei Kepuasan
    Route::get('/survey/{token}', [PatientConsultationController::class, 'survey'])->name('survey');
    Route::post('/survey/{token}', [PatientConsultationController::class, 'storeSurvey'])->name('survey.store')->middleware('throttle:3,1');

    // Homecare
    Route::get('/homecare/mulai', [PatientConsultationController::class, 'createHomecare'])->name('homecare.create');
    Route::post('/homecare/mulai', [PatientConsultationController::class, 'storeHomecare'])->name('homecare.store')->middleware('throttle:3,1');
    Route::get('/invoice/{invoice}', [PatientConsultationController::class, 'invoice'])->name('invoice');
    Route::post('/invoice/{invoice}/payment', [PaymentController::class, 'select'])->name('payment.select');
    Route::post('/invoice/{invoice}/payment/refresh', [PaymentController::class, 'refresh'])->name('payment.refresh');
    Route::post('/invoice/{invoice}/proof', [PaymentController::class, 'uploadProof'])->name('payment.proof');
    Route::get('/menunggu/{token}', [PatientConsultationController::class, 'waiting'])->name('waiting');

    // View PDF Patient Route
    Route::get('/files/{type}', [App\Http\Controllers\FileController::class, 'show'])->name('file.show')->middleware('signed');

    Route::get('/selesai/{token}', [PatientConsultationController::class, 'summary'])->name('summary');
    Route::get('/ruang/{id}', [PatientConsultationController::class, 'room'])->name('room')->middleware('signed');
});

// Polling API for patient
Route::prefix('api/pasien')->name('api.patient.')->middleware('throttle:60,1')->group(function () {
    Route::get('/status/{token}', [PatientConsultationController::class, 'pollStatus'])->name('status');
    Route::get('/pesan/{id}', [PatientConsultationController::class, 'pollMessages'])->name('messages');
    Route::post('/pesan/{id}', [PatientConsultationController::class, 'sendMessage'])->name('send');
});

Route::get('/api/homecare/slots', [\App\Http\Controllers\Patient\HomecareScheduleController::class, 'slots'])->name('api.homecare.slots');

// =============================================================================
// ADMIN
// =============================================================================
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest-only
    Route::middleware('guest:admin')->group(function () {
        Route::get('/portal', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/portal', [AdminAuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
    });

    // Authenticated
    Route::middleware('auth.admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/api/activity', [AdminDashboardController::class, 'activityFeed'])->name('api.activity');
        
        // Laporan Pendapatan
        Route::get('/pendapatan', [RevenueController::class, 'index'])->name('revenue.index');
        Route::get('/pendapatan/export/{format}', [RevenueController::class, 'export'])->name('revenue.export');
        Route::delete('/pendapatan/reset', [RevenueController::class, 'reset'])->name('revenue.reset');
        
        // Riwayat Pendapatan
        Route::get('/pendapatan/riwayat', [RevenueHistoryController::class, 'index'])->name('revenue.history.index');
        Route::get('/pendapatan/riwayat/export/csv', [RevenueHistoryController::class, 'exportCsv'])->name('revenue.history.export_csv');
        Route::get('/pendapatan/riwayat/export/pdf', [RevenueHistoryController::class, 'exportPdf'])->name('revenue.history.export_pdf');

        // Pengaturan Harga & Diskon
        Route::get('/pengaturan/harga', [\App\Http\Controllers\Admin\SettingController::class, 'pricing'])->name('settings.pricing');
        Route::put('/pengaturan/harga', [\App\Http\Controllers\Admin\SettingController::class, 'updatePricing'])->name('settings.pricing.update');

        // Payment Verification
        Route::prefix('pembayaran')->name('payment.')->group(function () {
            Route::get('/', [PaymentVerificationController::class, 'index'])->name('index');
            Route::get('/{proof}', [PaymentVerificationController::class, 'show'])->name('show');
            Route::delete('/consultation/{consultation}', [PaymentVerificationController::class, 'destroyConsultation'])->name('destroy_consultation');
            Route::post('/{proof}/approve', [PaymentVerificationController::class, 'approve'])->name('approve');
            Route::post('/{proof}/reject', [PaymentVerificationController::class, 'reject'])->name('reject');
            Route::post('/{proof}/reupload', [PaymentVerificationController::class, 'requestReupload'])->name('reupload');
        });

        // Homecare Schedule Management
        Route::prefix('homecare-schedule')->name('homecare.schedule.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\HomecareScheduleController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\HomecareScheduleController::class, 'store'])->name('store');
            Route::delete('/{block}', [\App\Http\Controllers\Admin\HomecareScheduleController::class, 'destroy'])->name('destroy');
        });

        // Consultation Management
        Route::prefix('konsultasi')->name('consultation.')->group(function () {
            Route::get('/', [AdminConsultationController::class, 'index'])->name('index');
            Route::get('/{consultation}', [AdminConsultationController::class, 'show'])->name('show');
            Route::post('/{consultation}/assign', [AdminConsultationController::class, 'assign'])->name('assign');
            Route::post('/{consultation}/close', [AdminConsultationController::class, 'forceClose'])->name('close');
        });

        // Admin Profile
        Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');

        // Doctor Management
        Route::prefix('dokter')->name('doctor.')->group(function () {
            Route::get('/', [AdminDoctorController::class, 'index'])->name('index');
            Route::get('/baru', [AdminDoctorController::class, 'create'])->name('create');
            Route::post('/', [AdminDoctorController::class, 'store'])->name('store');
            Route::get('/{doctor}/edit', [AdminDoctorController::class, 'edit'])->name('edit');
            Route::put('/{doctor}', [AdminDoctorController::class, 'update'])->name('update');
            Route::delete('/{doctor}', [AdminDoctorController::class, 'destroy'])->name('destroy');
        });

        // Medicine Management
        Route::prefix('obat')->name('medicine.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MedicineController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MedicineController::class, 'store'])->name('store');
            Route::put('/{medicine}', [\App\Http\Controllers\Admin\MedicineController::class, 'update'])->name('update');
            Route::delete('/{medicine}', [\App\Http\Controllers\Admin\MedicineController::class, 'destroy'])->name('destroy');
        });
    });
});

// =============================================================================
// DOCTOR
// =============================================================================
Route::prefix('doctor')->name('doctor.')->group(function () {
    // Guest-only
    Route::middleware('guest:doctor')->group(function () {
        Route::get('/portal', [DoctorAuthController::class, 'showLogin'])->name('login');
        Route::post('/portal', [DoctorAuthController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
    });

    // Authenticated
    Route::middleware('auth.doctor')->group(function () {
        Route::post('/logout', [DoctorAuthController::class, 'logout'])->name('logout');

        Route::get('/dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
        Route::post('/status', [DoctorDashboardController::class, 'toggleStatus'])->name('status');

        // Consultation
        Route::prefix('konsultasi')->name('consultation.')->group(function () {
            Route::get('/{consultation}', [DoctorConsultationController::class, 'show'])->name('show');
            Route::post('/{consultation}/end', [DoctorConsultationController::class, 'end'])->name('end');
            Route::post('/{consultation}/prescription', [DoctorConsultationController::class, 'uploadPrescription'])->name('prescription');
            Route::post('/{consultation}/sick-leave', [DoctorConsultationController::class, 'uploadSickLeave'])->name('sick_leave');
            Route::post('/{consultation}/report', [DoctorConsultationController::class, 'uploadHomecareReport'])->name('report');
        });

        // Polling API for doctor
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/pesan/{id}', [DoctorConsultationController::class, 'pollMessages'])->name('messages');
            Route::post('/pesan/{id}', [DoctorConsultationController::class, 'sendMessage'])->name('send');
        });
    });
});

// =============================================================================
// PRIVATE FILE ACCESS (signed URLs)
// =============================================================================
Route::prefix('files')->name('files.')->group(function () {
    Route::get('/prescription/{prescription}', [FileController::class, 'prescription'])->name('prescription')->middleware('signed');
    Route::get('/sick-leave/{sick_leave}', [FileController::class, 'sickLeave'])->name('sick_leave')->middleware('signed');
    Route::get('/receipt/{transaction}', [FileController::class, 'receipt'])->name('receipt')->middleware('signed');
    Route::get('/payment-proof/{proof}', [FileController::class, 'paymentProof'])->name('proof')->middleware(['signed', 'auth.admin']);
    Route::get('/medical/{patient}/{field}', [FileController::class, 'medicalFile'])->name('medical')->middleware('signed');
    Route::get('/attachment/{message}', [FileController::class, 'chatAttachment'])->name('attachment')->middleware('signed');
});

// =============================================================================
// PUBLIC VERIFICATION
// =============================================================================
Route::get('/verifikasi/resep/{invoice}', [\App\Http\Controllers\PrescriptionVerificationController::class, 'show'])->name('verify.prescription');
