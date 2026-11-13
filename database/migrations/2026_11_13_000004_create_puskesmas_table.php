<?php

use App\Enums\ServiceCategory;
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

        Schema::create('puskesmas', function (Blueprint $table) use ($isPgsql) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->restrictOnDelete();
            $table->string('kode_puskesmas', 30)->unique();
            $table->string('nama_puskesmas', 150);
            $table->text('alamat');
            if (! $isPgsql) {
                $table->text('geom')->nullable();
            }
            $table->double('x_long');
            $table->double('y_lat');
            $table->string('website_url', 255)->nullable();
            $table->string('kategori_layanan', 50)->default(ServiceCategory::NonRawatInap->value);
            $table->unsignedInteger('jumlah_posyandu')->default(0);
            $table->unsignedInteger('jumlah_sekolah')->default(0);
            $table->decimal('skor_psw', 5, 2)->nullable();
            $table->timestamps();

            $table->index('kecamatan_id');
        });

        if ($isPgsql) {
            DB::statement('ALTER TABLE puskesmas ADD COLUMN geom GEOMETRY(Point, 4326)');
            DB::statement('CREATE INDEX idx_puskesmas_geom ON puskesmas USING GIST (geom)');
            DB::statement('CREATE INDEX idx_puskesmas_geog ON puskesmas USING GIST ((geom::geography))');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puskesmas');
    }
};
