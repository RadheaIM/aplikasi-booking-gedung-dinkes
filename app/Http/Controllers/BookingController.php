<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gedung;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class BookingController extends Controller
{
    // Menampilkan halaman Dashboard dengan daftar Gedung
    public function index()
    {
        $gedungs = Gedung::all();
        
        // === LOGIKA PENDING COUNT UNTUK BADGE ADMIN ===
        $pendingCount = 0;
        // Hanya hitung jika user sudah login dan memiliki role Admin
        if (Auth::check() && (Auth::user()->name === 'Admin Dinas' || Auth::user()->id === 1)) {
             $pendingCount = Booking::where('status', 'pending')->count();
        }
        // ===================================
        
        return view('dashboard', compact('gedungs', 'pendingCount')); // Kirimkan $pendingCount
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
    // FUNGSI UNTUK ADMIN (LIST DAN UPDATE STATUS)
    // ----------------------------------------------------------------

    public function list()
    {
        $bookings = Booking::with(['user', 'gedung'])->orderBy('created_at', 'desc')->get();
        return view('booking.list', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);
        
        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->route('booking.list')->with('success', 'Status booking berhasil diperbarui.');
    }
    
    // ----------------------------------------------------------------
    // FUNGSI PDF GENERATOR
    // ----------------------------------------------------------------

    public function generatePdf(Booking $booking)
    {
        if ($booking->status !== 'approved') {
            return redirect()->route('booking.list')->with('error', 'Surat hanya bisa dicetak jika status sudah disetujui.');
        }

        $pdf = Pdf::loadView('booking.pdf_surat', compact('booking'));
        
        $tanggal_format = \Carbon\Carbon::parse($booking->waktu_mulai)->format('Ymd');
        $filename = 'SURAT_IZIN_' . $booking->gedung->nama_gedung . '_' . $tanggal_format . '.pdf';

        return $pdf->download($filename);
    }
    
    // ----------------------------------------------------------------
    // FUNGSI KALENDER EVENTS (Endpoint API)
    // ----------------------------------------------------------------

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
    
    // ----------------------------------------------------------------
    // FUNGSI BARU: LAPORAN REKAPITULASI BULANAN
    // ----------------------------------------------------------------
    
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $bookings = Booking::with('gedung', 'user')
            ->whereYear('waktu_mulai', $year)
            ->whereMonth('waktu_mulai', $month)
            ->orderBy('waktu_mulai', 'asc')
            ->get();
            
        $summary = [
            'total' => $bookings->count(),
            'approved' => $bookings->where('status', 'approved')->count(),
            'pending' => $bookings->where('status', 'pending')->count(),
            'rejected' => $bookings->where('status', 'rejected')->count(),
        ];

        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        $years = range(date('Y') - 3, date('Y') + 1);

        return view('report.monthly', compact('bookings', 'summary', 'months', 'years', 'month', 'year'));
    }
}