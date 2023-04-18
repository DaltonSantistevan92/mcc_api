<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; //nombre de la tabla
    protected $fillable = ['category_id','nombre','descripcion','imagen','stock','precio_venta','margen_ganancia','status'];  //atributos de la tabla

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
