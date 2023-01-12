<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{

    public function createRoles(Request $request)
    {
        try {
            $validateRol = Validator::make($request->all(), [ 'name' => 'required', 'guard_name' => 'nullable' ],[ 'name.required' => 'El rol es requerido' ]);
            $response = [];
            
            if($validateRol->fails()){
                $response = $this->returnResponseError(false, 'Error de validación', $validateRol );
                return response()->json( $response, 401 );
            }

            $role = Role::create(['name' => $request->name, 'guard_name' => $request->guard_name ]);

            $response = $this->returnResponse(true, 'El Rol registro con éxito', $role );
            return response()->json( $response, 200 );

        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    public function getAll(){
        try {
            $role = Role::get(['id','name']);
            $response = [];

            if ($role != null) {
                $response = $this->returnResponse(true, 'Existen datos', $role );
            }else{
                $response = $this->returnResponse(false, 'No existen datos', null );
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

    private function returnResponseError($status,$message,$data){
        $response = [
            'status' => $status,
            'message' => $message,
            'errors' => $data->errors()
        ];
        return $response;
    }

    private function returnResponse($status,$message,$data){
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return $response;
    }



}
