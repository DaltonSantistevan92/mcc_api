<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;


class PermissionController extends Controller
{
    
    public function createPermission(Request $request)
    {
        try {
            $validatePermission = Validator::make($request->all(),[ 'name' => 'required', 'guard_name' => 'nullable' ],[ 'name.required' => 'El nombre del permiso es requerido' ]); 
            $response = [];
            
            if($validatePermission->fails()){
                $response = $this->returnValidateError($validatePermission);
                return response()->json($response, 401);
            }

            $permission = Permission::create([ 'name' => $request->name, 'guard_name' => $request->guard_name ]);

            $response = $this->returnResponse(true,'El Permiso registro con éxito',$permission);
            
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    public function getAll(){
        try {
            $permission = Permission::get(['id','name']);
            $response = [];

            if ($permission != null) {
                $response = $this->returnResponse(true, 'Existen datos', $permission);
            }else{
                $response = $this->returnResponse(false, 'No Existen datos', null);
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

    private function returnValidateError($validatePermission){
        $response = [
            'status' => false,
            'message' => 'Error de validación',
            'errors' => $validatePermission->errors()
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
