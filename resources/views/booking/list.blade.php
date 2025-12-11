<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Booking Gedung') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Pesan Sukses (dari Controller) --}}
                    @if(session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    {{-- Pesan Error (dari Controller, misal error PDF) --}}
                    @if(session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                
                                {{-- KOLOM WA PEMINJAM --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">WA Peminjam</th>
                                
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gedung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Pinjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->user->name }}</td>
                                
                                {{-- DATA WA PEMINJAM --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="font-medium text-gray-900">{{ $booking->user->whatsapp_number }}</div>
                                    @if($booking->user->whatsapp_number)
                                    <a href="https://wa.me/{{ $booking->user->whatsapp_number }}" target="_blank" class="text-green-600 hover:text-green-800 text-xs">
                                        (Hubungi via WA)
                                    </a>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->gedung->nama_gedung }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->nama_kegiatan }}</td>
                                
                                {{-- TAMPILAN WAKTU DAN JAM DENGAN FORMAT 24 JAM (HH:mm) --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Mulai: <strong>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</strong><br>
                                    {{-- Menggunakan format PHP 'H:i' untuk memaksa 24 jam --}}
                                    Selesai: <strong>{{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }}</strong> 
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($booking->status === 'approved') bg-green-100 text-green-800
                                        @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    
                                    @if($booking->status === 'pending')
                                        {{-- Tombol Setujui/Tolak --}}
                                        <form method="POST" action="{{ route('booking.update-status', $booking) }}" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="text-primary-600 hover:text-primary-900 mr-2">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('booking.update-status', $booking) }}" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                        </form>
                                    @elseif($booking->status === 'approved')
                                        <span class="text-green-600 text-xs mr-2">DISETUJUI</span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="text-gray-600 text-xs mr-2">DITOLAK</span>
                                    @endif
                                    
                                    {{-- TOMBOL DELETE UNIVERSAL --}}
                                    <form method="POST" action="{{ route('booking.destroy', $booking) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini? Tindakan ini permanen.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-600">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>