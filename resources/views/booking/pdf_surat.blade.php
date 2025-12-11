<!DOCTYPE html>
<html>
<head>
    <title>Surat Izin Peminjaman Gedung Dinas Kesehatan</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; font-size: 12pt; }
        .container { width: 90%; margin: 40px auto; }
        .header { border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 30px; text-align: center; }
        .header img { float: left; margin-right: 15px; }
        .header h1 { margin: 0; font-size: 16pt; }
        .header p { margin: 2px 0; font-size: 10pt; }
        .content { line-height: 1.8; }
        .signature { margin-top: 50px; text-align: right; }
        .table-data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table-data td { padding: 5px 0; }
    </style>
</head>
<body>

    <div class="container">
        {{-- KOP SURAT --}}
        <div class="header">
            {{-- Anda bisa menempatkan logo di sini --}}
            {{-- <img src="{{ public_path('images/logo_dinkes.png') }}" width="80"> --}}
            <h1>DINAS KESEHATAN KABUPATEN/KOTA [Nama Daerah Anda]</h1>
            <p>Jl. [Alamat Kantor Dinas Kesehatan Anda] | Telp. (021) 1234567</p>
            <p>Email: dinkes@example.com</p>
        </div>

        <div style="text-align: center; margin-bottom: 30px;">
            <h2>SURAT IZIN PEMINJAMAN GEDUNG</h2>
            <hr style="width: 50%;">
            <p>Nomor: [Isi Nomor Surat Manual] / DINKES / {{ date('Y') }}</p>
        </div>

        <div class="content">
            Yang bertanda tangan di bawah ini, Kepala Sub Bagian Umum Dinas Kesehatan [Nama Daerah Anda], menyatakan memberikan izin peminjaman kepada:

            <table class="table-data">
                <tr>
                    <td style="width: 30%;">Nama Peminjam</td>
                    <td style="width: 5%;">:</td>
                    <td>**{{ $booking->user->name }}**</td>
                </tr>
                <tr>
                    <td>Email/Kontak</td>
                    <td>:</td>
                    <td>{{ $booking->user->email }}</td>
                </tr>
                <tr>
                    <td>Keperluan Kegiatan</td>
                    <td>:</td>
                    <td>**{{ $booking->nama_kegiatan }}**</td>
                </tr>
            </table>

            <p style="margin-top: 20px;">Untuk menggunakan fasilitas Gedung / Ruangan Dinas Kesehatan:</p>

            <table class="table-data">
                <tr>
                    <td style="width: 30%;">Nama Gedung</td>
                    <td style="width: 5%;">:</td>
                    <td>**{{ $booking->gedung->nama_gedung }}**</td>
                </tr>
                <tr>
                    <td>Kapasitas</td>
                    <td>:</td>
                    <td>{{ $booking->gedung->kapasitas }} orang</td>
                </tr>
                <tr>
                    <td>Fasilitas</td>
                    <td>:</td>
                    <td>{{ $booking->gedung->fasilitas }}</td>
                </tr>
                <tr>
                    <td>Tanggal Peminjaman</td>
                    <td>:</td>
                    <td>**{{ \Carbon\Carbon::parse($booking->tanggal_pinjam)->locale('id')->isoFormat('dddd, D MMMM Y') }}**</td>
                </tr>
            </table>

            <p style="margin-top: 30px; text-align: justify;">Peminjam wajib menjaga kebersihan, ketertiban, dan mengembalikan fasilitas dalam kondisi semula. Surat izin ini berlaku hanya untuk kegiatan yang tertera di atas.</p>
        </div>

        <div class="signature">
            <p>[Nama Daerah Anda], {{ date('d F Y') }}</p>
            <p>Kepala Sub Bagian Umum</p>
            <br><br><br>
            <p>**( [Nama Lengkap Pejabat] )**</p>
            <p>NIP. [Nomor Induk Pegawai]</p>
        </div>
    </div>

</body>
</html>