<?php

namespace Database\Seeders;

use App\Enums\User\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->isProduction()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => Role::Admin,
            ]);
        }

        $this->call([
            SpottagSeeder::class,
        ]);
    }
}
