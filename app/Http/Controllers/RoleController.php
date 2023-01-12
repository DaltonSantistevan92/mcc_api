<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createRoles(Request $request)
    {
        try {
            //Validated
            $response = [];
            $validateRol = Validator::make($request->all(), 
            [ 'name' => 'required', 'guard_name' => 'nullable' ],
            [ 'name.required' => 'El rol es requerido' ]);
            
            if($validateRol->fails()){
                $response = [
                    'status' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validateRol->errors()
                ];
                return response()->json($response, 401);
            }

            $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name ]);

            $response = [
                'status' => true,
                'message' => 'El Rol registro con éxito',
                'role' => $role
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    public function getAll(){
        try {
            $role = Role::get(['id','name']);
            if ($role != null) {
                $response = [
                    'status' => true,
                    'message' => 'Existen datos',
                    'role' => $role
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
