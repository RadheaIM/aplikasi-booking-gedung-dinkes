<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gedungs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_gedung');
            $table->integer('kapasitas');
            $table->text('fasilitas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gedungs');
    }
};