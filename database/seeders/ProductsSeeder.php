<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = [
            [
                'category_id' => 5,
                'nombre' => 'Gorras Love',
                'descripcion' => 'De color blanco con letras de LOVE, separadas y en cada una las iniciales de la pareja',
                'imagen' => 'https://scontent.fgye4-1.fna.fbcdn.net/v/t39.30808-6/273621470_270830035156702_3154591251536223337_n.jpg?_nc_cat=108&ccb=1-7&_nc_sid=a26aad&_nc_eui2=AeGyl4CaX-_QWMIXZ55HZlbUwjGgfcSLuUjCMaB9xIu5SJrgpya4F9I3XyLcoxlZmWHXW47ygktElwXwqvIQE9r8&_nc_ohc=_SvyTeblQX4AX9Degmx&_nc_ht=scontent.fgye4-1.fna&oh=00_AfDrQPtc8riovnnkZJTXQZL1F4PG1TybPdIzptZG7SYd_A&oe=644315EA',
                'stock' => 10,
                'precio_venta'=> 8.15
            ],
            [
                'category_id' => 9,
                'nombre' => 'Arreglo de globos brillantes',
                'descripcion' => 'Incluye el número de los años de la persona, contiene globos dorados, fucsia, rosa pálido en diferentes presentaciones',
                'imagen' => 'https://scontent.fgye4-1.fna.fbcdn.net/v/t39.30808-6/275065955_286963110210061_5193142656557683697_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=a26aad&_nc_eui2=AeE6PisxmSMFbQZoBzCZG_epkvfVb0UTqymS99VvRROrKRbnFiqd_Jq21Py8yT3aJZZrfK3v_M-k7AVmGRoMmVPh&_nc_ohc=d4Ofg_UtqEUAX_yd_oJ&_nc_ht=scontent.fgye4-1.fna&oh=00_AfAV3wnZA81yXFkyY21ydmw3hMPbGItqXHzbkCuEWbYdeA&oe=6443F889',
                'stock' => 5,
                'precio_venta'=> 25.5
            ]
        ];
        

        foreach ($products as $valor) {
            $newCategory = new Product();
            $newCategory->category_id = $valor['category_id'];
            $newCategory->nombre = $valor['nombre'];
            $newCategory->descripcion = $valor['descripcion'];
            $newCategory->imagen = $valor['imagen'];
            $newCategory->stock = $valor['stock'];
            $newCategory->precio_venta = $valor['precio_venta'];

            $newCategory->save();
        }

    }
}
