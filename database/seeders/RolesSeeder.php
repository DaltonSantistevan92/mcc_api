<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $newRol = new Role();
        $newRol->name = 'administrador';
        $newRol->save();

        $newRol2 = new Role();
        $newRol2->name = 'cliente';
        $newRol2->save();

        $newRol3 = new Role();
        $newRol3->name = 'vendedor';
        $newRol3->save();
    }
}
