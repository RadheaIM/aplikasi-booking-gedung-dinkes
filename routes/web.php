<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController; 
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar Auth::check() bisa digunakan di route '/'

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. 
|
*/

// PERBAIKAN: Mengarahkan dari root '/' langsung ke login atau dashboard
Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard'); // Jika sudah login, langsung ke Dashboard
    }
    return redirect('/login'); // Jika belum login, arahkan ke halaman Login
});

// Grup Rute yang HANYA BISA DIAKSES SETELAH LOGIN
Route::middleware(['auth'])->group(function () {
    
    // Rute USER: Dashboard dan Proses Booking
    Route::get('/dashboard', [BookingController::class, 'index'])->name('dashboard');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Rute ADMIN: Daftar Booking dan Aksi
    Route::get('/bookings', [BookingController::class, 'list'])->name('booking.list');
    Route::post('/bookings/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
    Route::get('/bookings/{booking}/pdf', [BookingController::class, 'generatePdf'])->name('booking.pdf');
    
    // Rute API: Kalender Ketersediaan (dipanggil oleh JavaScript)
    Route::get('/calendar-events', [BookingController::class, 'calendarEvents'])->name('calendar.events');
    
    // === RUTE BARU: LAPORAN REKAPITULASI BULANAN ===
    Route::get('/report/monthly', [BookingController::class, 'monthlyReport'])->name('report.monthly');

});

require __DIR__.'/auth.php'; // Baris ini wajib ada di paling bawah (untuk Login/Register)