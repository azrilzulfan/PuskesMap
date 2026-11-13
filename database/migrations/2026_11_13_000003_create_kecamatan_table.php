<?php

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
        $isPgsql = Schema::getConnection()->getDriverName() === 'pgsql';

        Schema::create('kecamatan', function (Blueprint $table) use ($isPgsql) {
            $table->id();
            $table->string('kode_kecamatan', 20)->unique();
            $table->string('nama_kecamatan', 100);
            if (! $isPgsql) {
                $table->text('geom')->nullable();
            }
            $table->unsignedInteger('jumlah_penduduk');
            $table->double('luas_wilayah_km2');
        });

        if ($isPgsql) {
            DB::statement('ALTER TABLE kecamatan ADD COLUMN geom GEOMETRY(MultiPolygon, 4326)');
            DB::statement('CREATE INDEX idx_kecamatan_geom ON kecamatan USING GIST (geom)');
            DB::statement('ALTER TABLE kecamatan ADD CONSTRAINT chk_kecamatan_penduduk CHECK (jumlah_penduduk >= 0)');
            DB::statement('ALTER TABLE kecamatan ADD CONSTRAINT chk_kecamatan_luas CHECK (luas_wilayah_km2 > 0)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
