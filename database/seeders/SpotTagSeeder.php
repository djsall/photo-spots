<?php

namespace Database\Seeders;

use App\Models\SpotTag;
use Illuminate\Database\Seeder;

class SpotTagSeeder extends Seeder
{
    public function run(): void
    {
        SpotTag::factory()
            ->state([
                'name' => 'Drón',
            ])
            ->create();

        SpotTag::factory()
            ->state([
                'name' => 'Tejút',
            ])
            ->create();

        SpotTag::factory()
            ->state([
                'name' => 'Karika',
            ])
            ->create();
    }
}
