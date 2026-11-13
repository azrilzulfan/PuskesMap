<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@puskesmap.bogor.go.id'],
            [
                'name' => 'Administrator PuskesMap',
                'password' => 'AdminBogor2025!',
                'role' => UserRole::Admin,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'dinkes@puskesmap.bogor.go.id'],
            [
                'name' => 'Analis Dinas Kesehatan Kota Bogor',
                'password' => 'DinkesBogor2025!',
                'role' => UserRole::Pemerintah,
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );
    }
}
