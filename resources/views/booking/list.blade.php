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
                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->gedung->nama_gedung }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $booking->nama_kegiatan }}</td>
                                
                                {{-- TAMPILAN WAKTU DAN JAM DENGAN FORMAT INDONESIA --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    Mulai: <strong>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</strong><br>
                                    Selesai: <strong>{{ \Carbon\Carbon::parse($booking->waktu_selesai)->locale('id')->isoFormat('HH:mm') }}</strong>
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
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-2">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('booking.update-status', $booking) }}" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="text-red-600 hover:text-red-900">Tolak</button>
                                        </form>
                                    @elseif($booking->status === 'approved')
                                        {{-- Tombol Cetak PDF --}}
                                        <a href="{{ route('booking.pdf', $booking) }}" target="_blank" class="text-green-600 hover:text-green-800">
                                            Cetak Surat (PDF)
                                        </a>
                                    @endif
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