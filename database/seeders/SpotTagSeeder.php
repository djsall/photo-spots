<?php

namespace Database\Seeders;

use App\Models\Technique;
use Illuminate\Database\Seeder;

class SpotTagSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'Drón',
            'Tejút',
            'Karika',
        ];

        foreach ($defaults as $name) {
            Technique::create([
                'name' => $name,
            ]);
        }
    }
}
