<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array = array('Camisas', 'Blusas', 'Pantalones', 'Abrigos', 'Gorras', 'Agendas', 'Tazas', 'Vasos', 'Globos', 'Arreglos');
        foreach ($array as $valor) {
            $newCategory = new Category();
            $newCategory->category = $valor;
            $newCategory->save();
        }
    }
}
