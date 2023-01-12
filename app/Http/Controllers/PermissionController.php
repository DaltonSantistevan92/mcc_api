<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createPermission(Request $request)
    {
        try {
            //Validated
            $response = [];
            $validatePermission = Validator::make($request->all(), 
            [ 'name' => 'required', 'guard_name' => 'nullable' ],
            [ 'name.required' => 'El nombre del permiso es requerido' ]);
            
            if($validatePermission->fails()){
                $response = [
                    'status' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validatePermission->errors()
                ];
                return response()->json($response, 401);
            }

            $permission = Permission::create(['name' => $request->name, 'guard_name' => $request->guard_name ]);

            $response = [
                'status' => true,
                'message' => 'El Permiso registro con éxito',
                'permission' => $permission
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    public function getAll(){
        try {
            $permission = Permission::get(['id','name']);
            if ($permission != null) {
                $response = [
                    'status' => true,
                    'message' => 'Existen datos',
                    'permission' => $permission
                ];
            }else{
                $response = [
                    'status' => false,
                    'message' => 'No Existen datos',
                    'role' => null
                ];
            }
            return response()->json($response); 
        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th),500);
        }
    }

    private function returThrowable($th){
        $response = [
            'status' => false,
            'message' => $th->getMessage()
        ];
        return $response;
    }
}
