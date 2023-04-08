<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $table = 'people'; //nombre de la tabla
    protected $fillable = ['sex_id','identification','first_name','last_name','birthday','address','cellphone_number','phone_number','status'];  //atributos de la tabla

    //belongsTo  => muchos a uno
    public function sex(){
        return $this->belongsTo(Sex::class);
    }
}
