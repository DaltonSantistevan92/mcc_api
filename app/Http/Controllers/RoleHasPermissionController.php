<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleHasPermissionController extends Controller
{

    public function createAssignment(Request $request){
        $role_id = intval($request->role_id);
        $permissions = collect($request->permissions);
        $role = Role::find($role_id);
        $response = [];  $message = ''; 
       
        if ($role) {
            if ($permissions->count() > 0) {
                $existPermission = collect();   $noExistPermission =  collect(); 
                foreach($permissions as $item){
                    $per =  Permission::where('name',$item)->get('name')->first(); 
                    
                    if ($per != null) {
                        $existPermission->push($per->name);
                    }else{
                        $noExistPermission->push($item);
                    }
                } 

                if ( $existPermission->count() == 0 && $noExistPermission->count() > 0 ) {
                    $response = $this->returnResponse(false, 'Los permisos no existen', $noExistPermission);
                }

                if( $existPermission->count() > 0 ) {
                    $role->syncPermissions($existPermission);

                    if ( $noExistPermission->count() == 0 ) {
                        $response = $this->returnResponse(true, 'Los permisos se asignaron correctamente', $role);
                    } else {
                        $message = 'Los permisos ' . $existPermission . ' se asignaron correctamente y estos permisos no se asignaron '. $noExistPermission;
                        $response = $this->returnResponse(true, $message, $role);      
                    }
                }               
            }else{
                $response = $this->returnResponse(false, 'No existen permisos', null);
            }
        }else{
            $message = 'No existen un rol con el id ' . $role_id;
            $response = $this->returnResponse(false, $message, null);
        }
        return response()->json($response);
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
