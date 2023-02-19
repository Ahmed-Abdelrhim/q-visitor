<?php

namespace Database\Seeders;

use App\Models\Languages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = ['ar','en'];
        foreach ($languages as $key => $lang) {
            $lang = Languages::query()->insert([
                'iso' => $lang,
                'active' => 1,
                'created_at' => Carbon::now(),
            ]);
        }
    }
}
