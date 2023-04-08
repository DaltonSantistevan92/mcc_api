<?php

namespace App\Models;

use App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sex extends Model
{
    use HasFactory;

    protected $table = 'sexes'; //nombre de la tabla
    protected $fillable = ['type','status'];  //atributos de la tabla

    //hasMany => uno a muchos
    public function people(){
        return $this->hasMany(Person::class);
    }
}
