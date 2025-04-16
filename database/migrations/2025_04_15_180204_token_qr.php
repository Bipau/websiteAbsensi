<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('token_qr', function (Blueprint $table) {
            $table->id();
            $table->string('token_qr')->unique();
            $table->foreignId('jadwal_id')->constrained('jadwal_pelajaran');
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->timestamp('expired_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('token_qr');
    }
};
