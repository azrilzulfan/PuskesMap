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
        Schema::create('tenaga_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puskesmas_id')->unique()->constrained('puskesmas')->cascadeOnDelete();
            $table->unsignedInteger('dokter_umum')->default(0);
            $table->unsignedInteger('dokter_gigi')->default(0);
            $table->unsignedInteger('bidan')->default(0);
            $table->unsignedInteger('perawat')->default(0);
            $table->unsignedInteger('farmasi')->default(0);
            $table->unsignedInteger('gizi')->default(0);
            $table->unsignedInteger('kesling')->default(0);
            $table->unsignedInteger('kesmas')->default(0);
            $table->unsignedInteger('atlm')->default(0);
            $table->double('persentase_kelengkapan')->default(0);
            $table->string('kategori_jumlah_nakes', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenaga_medis');
    }
};
