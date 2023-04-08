<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator};


class MenuController extends Controller
{
    public function getAll()
    {
        $response = [];
        try {
           $menus =  Menu::all();

            if (count($menus) > 0) {
                $response = [ 'status' => true, 'message' => 'Exiten datos', 'data' => $menus ];
            } else{
                $response = [ 'status' => false, 'message' => 'No exiten datos'];
            }
            return response()->json( $response, 200 );
        } catch (\Throwable $th) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function getWhitPermissions(){
        $response = [];
        try {
           $menus =  Menu::with('permissions');

            if ($menus->count() > 0) {
                $response = [ 'status' => true, 'message' => 'Exiten datos', 'data' => $menus->get() ];
            } else{
                $response = [ 'status' => false, 'message' => 'No exiten datos'];
            }
            return response()->json( $response, 200 );
        } catch (\Throwable $th) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function createMenu(Request $request)
    {
        try {
           $validateMenu =  $this->validateMenu($request->all());

           if ($validateMenu["status"]) {
                $menu = Menu::create( $request->all() );
                if ($menu) {
                    $response = [ 'status' => true, 'message' => 'Se registró correctamente', 'data' => $menu ];
                }else{
                    $response = [ 'status' => false, 'message' => 'Se registró correctamente' ];
                }
           }else{
            $response = $validateMenu;
           }
           return response()->json( $response, 200 );
        } catch (\Throwable $th) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function validateMenu( $request ){
        $rules = [
            'detail' => 'required|unique:menus,detail',
            // 'url' => 'required|string',
            // 'icon' => 'required|string'
        ];

        $messages = [
            'detail.required' => 'El campo detalle es requerido',
            'detail.unique' => 'El detalle ya ha sido registrado',
            // 'url.required' => 'El campo url es requerido',
            // 'url.string' => 'El campo url no tiene un formato de string valido',
            // 'icon.required' => 'El campo icon es requerido',
            // 'icon.string' => 'El campo icono no tiene un formato de string valido',
        ];
        return $this->validation( $request, $rules, $messages );
    }

    public function validation( $request, $rules, $messages ){
        $response = [ 'status' => true, 'message' => 'No hubo errores' ];

        $validate = Validator::make( $request, $rules, $messages ); 

        if ( $validate->fails() ) {
            $response = [ 'status' => false, 'message' => 'Error de validación', 'error' => $validate->errors() ];
        }
        return $response;
    }

    public function assignPermissions( Request $request ){
        $menu_id = $request->menu_id;
        $permissions_id = collect($request->permission);

        if ($menu_id) {
            $menu = Menu::find($menu_id); 
            //$menu->permissions()->attach($permissions_id->all());//crear
            //$menu->permissions()->sync($permissions_id->all());//actualiza
            $menu->permissions()->syncWithoutDetaching($permissions_id->all());//crea los que no estan
            $response = [ 'status' => true, 'message' => 'Se asignó correctamente', 'data' => $menu->with('permissions')->get() ];

        }else{
            $response = [ 'status' => false, 'message' => 'no existe el menu' ];
        }
        return response()->json( $response, 200 );
    }


    public function removePermissions( Request $request ){
        $menu_id = $request->menu_id;
        $permissions_id = collect($request->permission);

        if ($menu_id) {
            $menu = Menu::find($menu_id); 
            $menu->permissions()->detach($permissions_id->all());//elimin los permisos asignados
            $response = [ 'status' => true, 'message' => 'Se quitó el permiso correctamente' ];

        }else{
            $response = [ 'status' => false, 'message' => 'no existe el menu' ];
        }
        return response()->json( $response, 200 );
    }
}
