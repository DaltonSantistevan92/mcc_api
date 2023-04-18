<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin
        $newUser = new User();
        $newUser->user_name = 'admin';
        $newUser->email = 'admin@gmail.com';
        $newUser->password = Hash::make('123456789');
        $newUser->save();

        $newUser->assignRole( ['administrador'] );


        //cliente
        $newUser2 = new User();
        $newUser2->user_name = 'cliente';
        $newUser2->email = 'cliente@gmail.com';
        $newUser2->password = Hash::make('123456789');
        $newUser2->save();

        $newUser2->assignRole( ['cliente'] );
    }
}
