<?php

use App\Enums\CrowdStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunjungan_pasien', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puskesmas_id')->constrained('puskesmas')->cascadeOnDelete();
            $table->unsignedSmallInteger('bulan');
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('total_pasien_bulanan')->default(0);
            $table->unsignedInteger('kapasitas_maksimal_bulanan')->default(850);
            $table->double('persentase_keramaian')->default(0);
            $table->string('status_kepadatan', 30)->default(CrowdStatus::Rendah->value);

            $table->unique(['puskesmas_id', 'tahun', 'bulan']);
        });

        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE kunjungan_pasien ADD CONSTRAINT chk_kunjungan_bulan CHECK (bulan BETWEEN 1 AND 12)');
            DB::statement('ALTER TABLE kunjungan_pasien ADD CONSTRAINT chk_kunjungan_kapasitas CHECK (kapasitas_maksimal_bulanan > 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan_pasien');
    }
};
