<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categorias_stages')->insert([
            'nombre' => "Inbound"
        ]);

        DB::table('categorias_stages')->insert([
            'nombre' => "Outbound"
        ]);
    }
}
