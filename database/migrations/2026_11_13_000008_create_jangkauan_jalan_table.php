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

        Schema::create('jangkauan_jalan', function (Blueprint $table) use ($isPgsql) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->string('kategori_jangkauan', 50);
            $table->unsignedInteger('jangkauan_faskes');
            $table->unsignedInteger('jangkauan_jalan');
            if (! $isPgsql) {
                $table->text('geom')->nullable();
            }

            $table->index(['kecamatan_id', 'kategori_jangkauan']);
        });

        if ($isPgsql) {
            DB::statement('ALTER TABLE jangkauan_jalan ADD COLUMN geom GEOMETRY(MultiPolygon, 4326)');
            DB::statement('CREATE INDEX idx_jangkauan_geom ON jangkauan_jalan USING GIST (geom)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jangkauan_jalan');
    }
};
