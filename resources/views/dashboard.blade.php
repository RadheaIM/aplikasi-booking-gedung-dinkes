<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Gedung Dinas Kesehatan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- === TOMBOL ADMIN DENGAN NOTIFIKASI DAN LAPORAN === --}}
                    @if (Auth::user()->name === 'Admin Dinas' || Auth::user()->id === 1)
                        <div class="mb-8 p-4 bg-primary-50 border-l-4 border-primary-500 rounded-md flex justify-between items-center">
                            
                            {{-- Container untuk menampung kedua tombol --}}
                            <div class="flex space-x-3">
                                
                                {{-- Tombol 1: Lihat Permintaan (Primary Button, Badge Merah) --}}
                                <a href="{{ route('booking.list') }}" 
                                   class="inline-flex items-center px-4 py-3 bg-primary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 relative">
                                    
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v.01M12 16v.01" />
                                    </svg>
                                    Lihat Permintaan Booking Masuk (Admin)
                                    
                                    {{-- BADGE JUMLAH PENDING COUNT --}}
                                    @if (isset($pendingCount) && $pendingCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ $pendingCount }}
                                    </span>
                                    @endif
                                </a>
                                
                                {{-- Tombol 2: Laporan Bulanan (Secondary Button) --}}
                                <a href="{{ route('report.monthly') }}" 
                                   class="inline-flex items-center px-4 py-3 bg-secondary-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-secondary-700 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Laporan Bulanan
                                </a>
                            </div>

                        </div>
                    @endif
                    {{-- ======================================================== --}}

                    {{-- === NOTIFIKASI SUKSES/ERROR === --}}
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    {{-- === ELEMEN KALENDER HTML BARU === --}}
                    <div class="mb-10 p-4 border rounded-lg bg-gray-50">
                        <h3 class="text-xl font-bold mb-4">Jadwal Gedung Terisi (Kalender Ketersediaan)</h3>
                        <div id='full_calendar_events'></div>
                    </div>
                    {{-- =================================== --}}

                    <h3 class="text-xl font-bold mb-4">Pilih Gedung untuk Booking:</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($gedungs as $gedung)
                            <div class="bg-gray-50 border p-4 rounded-lg shadow-md">
                                <h4 class="text-lg font-semibold text-primary-700">{{ $gedung->nama_gedung }}</h4>
                                
                                {{-- BARIS KAPASITAS DAN FASILITAS DIHAPUS DI SINI --}}
                                {{-- <p class="text-sm mt-1">Kapasitas: **{{ $gedung->kapasitas }} orang**</p> --}}
                                {{-- <p class="text-sm text-gray-600 mt-2">{{ $gedung->fasilitas }}</p> --}}

                                <form method="POST" action="{{ route('booking.store') }}" class="mt-4">
                                    @csrf
                                    <input type="hidden" name="gedung_id" value="{{ $gedung->id }}">

                                    <label for="kegiatan_{{ $gedung->id }}" class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                                    <input type="text" id="kegiatan_{{ $gedung->id }}" name="nama_kegiatan" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                                    {{-- INPUT WAKTU MULAI --}}
                                    <label for="mulai_{{ $gedung->id }}" class="block text-sm font-medium text-gray-700 mt-3">Waktu Mulai</label>
                                    <input type="datetime-local" id="mulai_{{ $gedung->id }}" name="waktu_mulai" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                                    {{-- INPUT WAKTU SELESAI --}}
                                    <label for="selesai_{{ $gedung->id }}" class="block text-sm font-medium text-gray-700 mt-3">Waktu Selesai</label>
                                    <input type="datetime-local" id="selesai_{{ $gedung->id }}" name="waktu_selesai" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">

                                    {{-- PERBAIKAN WARNA TOMBOL --}}
                                    <button type="submit" class="mt-4 w-full bg-primary-600 text-white py-2 rounded-md hover:bg-primary-700 transition duration-150">
                                        Booking Sekarang
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- SCRIPT INISIALISASI FULLCALENDAR (Tidak ada perubahan) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('full_calendar_events');
        
        if (calendarEl && typeof FullCalendar !== 'undefined') {
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'id', // Menggunakan bahasa Indonesia
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: "{{ route('calendar.events') }}", 
                editable: false,
            });
            calendar.render();
        }
    });
</script>
@endpush