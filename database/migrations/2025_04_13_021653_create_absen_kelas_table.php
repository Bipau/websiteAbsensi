<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Kelas;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absen_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('users'); 
            $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran'); 
            $table->foreignIdFor(Kelas::class);
            $table->date('tanggal');
            $table->time('jam_absen');
            $table->string('token_qr')->nullable(); 
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alfa'])->default('Hadir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen_kelas');
    }
};
