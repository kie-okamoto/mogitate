<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    public function run()
    {
        $seasons = ['春', '夏', '秋', '冬'];

        foreach ($seasons as $season) {
            Season::create(['name' => $season]);
        }
    }
}
