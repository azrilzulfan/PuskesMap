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
        Schema::create('operasional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puskesmas_id')->unique()->constrained('puskesmas')->cascadeOnDelete();
            $table->boolean('status_igd_24jam')->default(false);
            $table->time('jam_buka')->default('07:30:00');
            $table->time('jam_tutup')->default('15:00:00');
            $table->string('kontak_darurat', 50)->nullable();
            $table->boolean('rawat_inap')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operasional');
    }
};
