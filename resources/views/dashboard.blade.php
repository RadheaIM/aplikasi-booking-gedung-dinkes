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

                    {{-- === TOMBOL ADMIN DENGAN NOTIFIKASI (Badge) === --}}
                    {{-- Tombol Laporan Bulanan ada di Navigasi Atas --}}
                    @if (Auth::user()->name === 'Admin Dinas' || Auth::user()->id === 1)
                        <div class="mb-8 p-4 bg-primary-50 border-l-4 border-primary-500 rounded-md flex justify-start items-center">
                            
                            {{-- Tombol Lihat Permintaan (Primary Button, Badge Merah) --}}
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

                        </div>
                    @endif
                    {{-- ======================================================== --}}

                    {{-- === NOTIFIKASI SUKSES/ERROR (Sudah Benar) === --}}
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
                    
                    {{-- === JADWAL DINAMIS: TABEL KETERSEDIAAN (Pengganti Kalender) === --}}
                    <div class="mb-10 p-4 border rounded-lg bg-gray-50 shadow-md">
                        <h3 class="text-xl font-bold mb-4">Jadwal Gedung Terisi (10 Jadwal Terdekat)</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Gedung</th>
                                        {{-- Kolom Kegiatan Dihapus --}}
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Waktu Mulai</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-600 uppercase">Waktu Selesai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($approvedBookings as $booking)
                                        <tr>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-red-700">
                                                {{ $booking->gedung->nama_gedung }}
                                            </td>
                                            {{-- Data Kegiatan Dihapus --}}
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                {{ \Carbon\Carbon::parse($booking->waktu_mulai)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                {{ \Carbon\Carbon::parse($booking->waktu_selesai)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-4 text-center text-sm text-gray-500">
                                                🎉 Tidak ada jadwal gedung terisi dalam waktu dekat.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- ================================================================= --}}

                    {{-- === BAGIAN BARU: COLLAPSE FORM BOOKING (Menggunakan Alpine.js) === --}}
                    <div x-data="{ isFormOpen: false }" class="mt-8">
                        
                        {{-- TOMBOL/HEADER YANG BISA DIKLIK --}}
                        <div @click="isFormOpen = !isFormOpen" 
                             class="flex items-center justify-between p-4 bg-primary-600 text-white rounded-lg shadow-md cursor-pointer hover:bg-primary-700 transition duration-200">
                            
                            <h3 class="text-xl font-bold">
                                {{ __('Pilih Gedung untuk Booking:') }}
                            </h3>
                            
                            {{-- Ikon panah yang berputar saat diklik --}}
                            <svg :class="{'rotate-180': isFormOpen, 'rotate-0': !isFormOpen}" 
                                 class="w-6 h-6 transform transition-transform duration-300" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        {{-- AREA FORM BOOKING (COLLAPSE/SEMBUNYI) --}}
                        <div x-show="isFormOpen" x-collapse.duration.500ms class="mt-4 border p-4 rounded-lg bg-white shadow-lg">
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach ($gedungs as $gedung)
                                    <div class="bg-gray-50 border p-4 rounded-lg shadow-md">
                                        <h4 class="text-lg font-semibold text-primary-700">{{ $gedung->nama_gedung }}</h4>
                                        
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

                                            <button type="submit" class="mt-4 w-full bg-primary-600 text-white py-2 rounded-md hover:bg-primary-700 transition duration-150">
                                                Booking Sekarang
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>
                    </div>
                    {{-- === AKHIR BAGIAN COLLAPSE FORM BOOKING === --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Catatan: Blok @push('scripts') telah dihapus karena tidak ada lagi kode JavaScript Kalender yang bermasalah --}}