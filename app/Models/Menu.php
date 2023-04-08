<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $fillable = ['section_id','detail','url','icon','position','status'];

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'menu_permission');
    }
}
