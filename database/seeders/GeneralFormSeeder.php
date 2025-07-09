<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormType;

class GeneralFormSeeder extends Seeder
{
    public function run(): void
    {
        FormType::create([
            'name' => 'Bilan General',
            'description' => 'Évaluation General',
            'fields' => json_decode(file_get_contents(database_path('data/general_fields.json')), true),
            'is_active' => true,
        ]);
    }
}
