<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('categorias')->insert([
            'nombre' => "Operacion TR"
        ]);

        DB::table('categorias')->insert([
            'nombre' => "CRYD"
        ]);

        DB::table('categorias')->insert([
            'nombre' => "Higiene"
        ]);
    }
}
