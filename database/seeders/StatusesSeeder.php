<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'name' => 'Activo',
            'model' => 'All',
            'color_status' => '#00c853',
            'translation_status' => 'Activo',
        ]);

        Status::create([
            'name' => 'Inactivo',
            'model' => 'All',
            'color_status' => '#d50000',
            'translation_status' => 'Inactivo',
        ]);
    }
}
