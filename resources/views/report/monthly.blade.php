<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekapitulasi Booking Gedung Bulanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- === 1. FORM FILTER BULAN/TAHUN === --}}
                    <form method="GET" action="{{ route('report.monthly') }}" class="mb-6 p-4 border rounded-lg bg-gray-50">
                        <div class="flex space-x-4 items-end">
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700">Pilih Bulan</label>
                                <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @foreach ($months as $key => $name)
                                        <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700">Pilih Tahun</label>
                                <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    @foreach ($years as $y)
                                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition duration-150">Tampilkan Laporan</button>
                        </div>
                    </form>

                    {{-- === 2. RINGKASAN HASIL === --}}
                    <h4 class="text-xl font-bold mb-4">Ringkasan Bulan {{ $months[$month] }} {{ $year }}</h4>
                    <div class="grid grid-cols-4 gap-4 mb-8 text-center">
                        <div class="p-4 bg-blue-100 rounded-lg shadow">
                            <p class="text-2xl font-bold text-blue-700">{{ $summary['total'] }}</p>
                            <p class="text-sm text-gray-600">Total Booking</p>
                        </div>
                        <div class="p-4 bg-green-100 rounded-lg shadow">
                            <p class="text-2xl font-bold text-green-700">{{ $summary['approved'] }}</p>
                            <p class="text-sm text-gray-600">Disetujui</p>
                        </div>
                        <div class="p-4 bg-yellow-100 rounded-lg shadow">
                            <p class="text-2xl font-bold text-yellow-700">{{ $summary['pending'] }}</p>
                            <p class="text-sm text-gray-600">Pending</p>
                        </div>
                        <div class="p-4 bg-red-100 rounded-lg shadow">
                            <p class="text-2xl font-bold text-red-700">{{ $summary['rejected'] }}</p>
                            <p class="text-sm text-gray-600">Ditolak</p>
                        </div>
                    </div>

                    {{-- === 3. DETAIL LAPORAN === --}}
                    <h4 class="text-xl font-bold mb-4">Detail Booking</h4>
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tgl/Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gedung</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        {{ \Carbon\Carbon::parse($booking->waktu_mulai)->locale('id')->isoFormat('D MMMM YYYY') }}<br>
                                        ({{ \Carbon\Carbon::parse($booking->waktu_mulai)->locale('id')->isoFormat('HH:mm') }} - {{ \Carbon\Carbon::parse($booking->waktu_selesai)->locale('id')->isoFormat('HH:mm') }})
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->gedung->nama_gedung }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->nama_kegiatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($booking->status === 'approved') bg-green-100 text-green-800
                                            @elseif($booking->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data booking pada bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>