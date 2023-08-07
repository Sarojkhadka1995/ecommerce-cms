<?php

namespace Database\Seeders;

use App\Model\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $englishBackend = Language::where('id', 1)->first();
        if (! $englishBackend) {
            Language::create([
                'name' => 'English',
                'language_code' => 'en',
                'group' => 'backend',
            ]);
        }
        $japaneseBackend = Language::where('id', 3)->first();
        if (! $japaneseBackend) {
            Language::create([
                'name' => 'Japanese',
                'language_code' => 'ja',
                'group' => 'backend',
            ]);
        }
    }
}
