<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ViolationCategoryController as AdminViolationCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BK\CounselingController as BKCounselingController;
use App\Http\Controllers\BK\DashboardController as BKDashboardController;
use App\Http\Controllers\BK\StudentTrackController as BKStudentTrackController;
use App\Http\Controllers\BK\ValidationController as BKValidationController;
use App\Http\Controllers\BK\ViolationController as BKViolationController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\MyReportController as GuruMyReportController;
use App\Http\Controllers\Guru\StudentController as GuruStudentController;
use App\Http\Controllers\Guru\ViolationController as GuruViolationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman Awal
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'bk' => redirect()->route('bk.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            default => redirect()->route('login'),
        };
    }

    return redirect()->route('login');
});

// Autentikasi (NIP & Password - Tanpa Register Publik)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Ubah Password & Profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/information', [ProfileController::class, 'updateProfile'])->name('profile.information.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
});

// ==========================================
// 1. ROLE: ADMIN (Super User)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Pengguna
    Route::resource('users', AdminUserController::class);
    Route::post('/users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');

    // Master Data Kelas & Jurusan
    Route::get('/classes', [SchoolClassController::class, 'index'])->name('classes.index');
    Route::post('/majors', [SchoolClassController::class, 'storeMajor'])->name('majors.store');
    Route::delete('/majors/{major}', [SchoolClassController::class, 'destroyMajor'])->name('majors.destroy');
    Route::post('/classes', [SchoolClassController::class, 'storeClass'])->name('classes.store');
    Route::delete('/classes/{class}', [SchoolClassController::class, 'destroyClass'])->name('classes.destroy');

    // Master Data Siswa
    Route::resource('students', AdminStudentController::class);

    // Master Kategori Pelanggaran & Bobot Poin
    Route::resource('violation-categories', AdminViolationCategoryController::class)->except(['show']);

    // Laporan Global
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

// ==========================================
// 2. ROLE: GURU BK (Validator & Konselor)
// ==========================================
Route::middleware(['auth', 'role:bk'])->prefix('bk')->name('bk.')->group(function () {
    Route::get('/dashboard', [BKDashboardController::class, 'index'])->name('dashboard');

    // Validasi Laporan Masuk dari Guru Biasa
    Route::get('/validations', [BKValidationController::class, 'index'])->name('validations.index');
    Route::post('/validations/{violation}', [BKValidationController::class, 'process'])->name('validations.process');

    // Input Pelanggaran Langsung (Buku Kasus)
    Route::get('/violations/create', [BKViolationController::class, 'create'])->name('violations.create');
    Route::post('/violations', [BKViolationController::class, 'store'])->name('violations.store');

    // Data & Rekam Jejak Siswa
    Route::get('/students', [BKStudentTrackController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [BKStudentTrackController::class, 'show'])->name('students.show');

    // Bimbingan Konseling
    Route::resource('counseling', BKCounselingController::class)->only(['index', 'create', 'store', 'show']);
});

// ==========================================
// 3. ROLE: GURU BIASA (Wali Kelas / Guru Mapel)
// ==========================================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');

    // Input Pelanggaran (Status 'Pending')
    Route::get('/violations/create', [GuruViolationController::class, 'create'])->name('violations.create');
    Route::post('/violations', [GuruViolationController::class, 'store'])->name('violations.store');

    // Riwayat Laporan Saya
    Route::get('/violations/my-reports', [GuruMyReportController::class, 'index'])->name('violations.my-reports');

    // Data Siswa (Histori Pelanggaran Read Only)
    Route::get('/students', [GuruStudentController::class, 'index'])->name('students.index');
    Route::get('/students/{student}', [GuruStudentController::class, 'show'])->name('students.show');
});
