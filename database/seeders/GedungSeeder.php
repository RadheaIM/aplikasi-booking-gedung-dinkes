<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Wajib: Tambahkan ini

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Matikan pengecekan Foreign Key sementara
        Schema::disableForeignKeyConstraints();
        
        // 2. Bersihkan tabel Gedung (menggunakan truncate yang menyebabkan error tadi)
        \App\Models\Gedung::truncate();
        
        // 3. Hidupkan kembali pengecekan Foreign Key
        Schema::enableForeignKeyConstraints(); 

        // 4. Memasukkan data gedung dengan nama dan fasilitas baru
        \App\Models\Gedung::insert([
            [
                'nama_gedung' => 'Aula Gedung Sekretariat Lt 3',
                'kapasitas' => 500,
                'fasilitas' => 'Panggung, Sound System, AC Central',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Ruang Meeting Lt 1',
                'kapasitas' => 50,
                'fasilitas' => 'Proyektor, Meja Bundar, AC',
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'nama_gedung' => 'Aula Gedung IFK',
                'kapasitas' => 200,
                'fasilitas' => 'Area Parkir Luas, Tenda (Opsional)',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}