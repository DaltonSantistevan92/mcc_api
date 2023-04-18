<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; //nombre de la tabla
    protected $fillable = ['category','status'];  //atributos de la tabla
    public $timestamps = false;

    public function product(){
        return $this->hasMany(Product::class);
    }
}
