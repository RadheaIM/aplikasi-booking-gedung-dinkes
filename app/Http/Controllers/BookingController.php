<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan halaman Dashboard dengan daftar Gedung
    public function index()
    {
        $gedungs = Gedung::all();
        
        // === LOGIKA PENDING COUNT UNTUK BADGE ADMIN ===
        $pendingCount = 0;
        if (Auth::check() && (Auth::user()->name === 'Admin Dinas' || Auth::user()->id === 1)) {
             $pendingCount = Booking::where('status', 'pending')->count();
        }
        
        // === DATA UNTUK JADWAL DINAMIS (PENGGANTI KALENDER) ===
        $approvedBookings = Booking::with('gedung')
                            ->where('status', 'approved')
                            // Hanya ambil jadwal yang belum berakhir
                            ->where('waktu_selesai', '>', Carbon::now()) 
                            ->orderBy('waktu_mulai', 'asc')
                            ->limit(10) // 10 jadwal terdekat
                            ->get();
        // ===================================
        
        return view('dashboard', compact('gedungs', 'pendingCount', 'approvedBookings'));
    }

    // Menyimpan data booking dari formulir (dengan Validasi Jam/Waktu)
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar dan Waktu
        $request->validate([
            'gedung_id' => 'required|exists:gedungs,id',
            'nama_kegiatan' => 'required',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);

        // 2. LOGIKA UTAMA: PENCEGAHAN DOUBLE BOOKING DENGAN WAKTU OVERLAP
        $existingBooking = Booking::where('gedung_id', $request->gedung_id)
            ->where('status', '!=', 'rejected') 
            ->where(function ($query) use ($request) {
                $query->where('waktu_mulai', '<', $request->waktu_selesai)
                      ->where('waktu_selesai', '>', $request->waktu_mulai);
            })
            ->exists();

        // Jika ditemukan booking yang mengunci tanggal/jam
        if ($existingBooking) {
            return redirect()->route('dashboard')->with('error', '❌ Gedung ini sudah dibooking pada rentang waktu tersebut. Silakan pilih waktu lain.');
        }
        
        // 3. Simpan ke database (jika validasi lolos)
        Booking::create([
            'user_id' => Auth::id(),
            'gedung_id' => $request->gedung_id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'status' => 'pending',
        ]);

        // 4. Kembali ke halaman dashboard dengan pesan sukses
        return redirect()->route('dashboard')->with('success', '✅ Booking berhasil diajukan! Menunggu persetujuan.');
    }

    // ----------------------------------------------------------------
    // FUNGSI ADMIN: DAFTAR, UPDATE, DAN HAPUS
    // ----------------------------------------------------------------
    public function list() 
    {
        $bookings = Booking::with(['user', 'gedung'])->orderBy('created_at', 'desc')->get();
        return view('booking.list', compact('bookings'));
    }
    
    public function updateStatus(Request $request, Booking $booking) 
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $booking->update(['status' => $request->status]);
        return redirect()->route('booking.list')->with('success', 'Status booking berhasil diperbarui.');
    }
    
    // === METHOD BARU: MENGHAPUS BOOKING ===
    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('booking.list')->with('success', '✅ Booking berhasil dihapus dari sistem.');
    }

    // ----------------------------------------------------------------
    // FUNGSI LAIN (TIDAK LAGI DIGUNAKAN UNTUK FITUR UTAMA)
    // ----------------------------------------------------------------
    
    // Method ini dipertahankan tetapi hanya me-redirect karena tombol cetak dihapus
    public function generatePdf(Booking $booking) 
    {
         return redirect()->route('booking.list')->with('error', 'Fitur cetak PDF sudah dihapus dari sistem.');
    }
    
    public function calendarEvents(Request $request) 
    {
        $bookings = Booking::where('status', 'approved')->get();
        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => $booking->gedung->nama_gedung . ' - TERISI',
                'start' => $booking->waktu_mulai,
                'end' => $booking->waktu_selesai,
                'backgroundColor' => '#DC3545', 
                'borderColor' => '#DC3545',
                'allDay' => false,
            ];
        }
        return response()->json($events);
    }
    
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        $bookings = Booking::with('gedung', 'user')->whereYear('waktu_mulai', $year)->whereMonth('waktu_mulai', $month)->orderBy('waktu_mulai', 'asc')->get();
        $summary = [
            'total' => $bookings->count(), 'approved' => $bookings->where('status', 'approved')->count(),
            'pending' => $bookings->where('status', 'pending')->count(), 'rejected' => $bookings->where('status', 'rejected')->count(),
        ];
        $months = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni','07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
        $years = range(date('Y') - 3, date('Y') + 1);
        return view('report.monthly', compact('bookings', 'summary', 'months', 'years', 'month', 'year'));
    }
}