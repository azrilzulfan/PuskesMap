<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            $exists = DB::selectOne("SELECT 1 FROM pg_extension WHERE extname = 'postgis'");

            if ($exists === null) {
                DB::statement('CREATE SCHEMA IF NOT EXISTS extensions');
                DB::statement('CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA extensions');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // PostGIS extension is intentionally kept during schema rollbacks.
    }
};
