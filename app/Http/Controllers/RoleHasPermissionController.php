<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleHasPermissionController extends Controller
{

    public function createAssignment2(Request $request){
        $role_id = intval($request->role_id);
        $permissions = $request->permissions;//array
       
        $role = Role::find($role_id);

        //$permissionExists = [];
        foreach ($permissions as $p) {
           
            $exitePermiss =  Permission::where('name',$p)->get()->first();

            if($exitePermiss != null ){
                if ($role != null) {
                    $role->syncPermissions($permissions);
                    $response = [
                        'status' => true,
                        'message' => 'Existen datos',
                        'permission' => $role
                    ];
                }else{
                    $response = [
                        'status' => false,
                        'message' => 'No existen un rol con el id '.$role_id,
                        'role' => null
                    ];
                }
            }else{
                $response = [
                    'status' => false,
                    'message' => 'No existen el permiso'
                ];
            }
        }
        return response()->json($response);
    }

    public function createAssignment(Request $request){
        $role_id = intval($request->role_id);
        $permissions = collect($request->permissions);//array
       
        $role = Role::find($role_id);

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

                if ($existPermission->count() == 0 && $noExistPermission->count() > 0) {
                    $response = [
                        'status' => false,
                        'message' => 'Los permisos no existen',
                        'permission' => $noExistPermission 
                    ];
                }

                if( $existPermission->count() > 0) {
                    $role->syncPermissions($existPermission);
                    if ($noExistPermission->count() == 0) {
                        $response = [
                            'status' => true,
                            'message' => 'Los permisos se asignaron correctamente',
                            'role' => $role
                        ];
                    } else {
                        $response = [
                            'status' => true,
                            'message' => 'Los permisos ' . $existPermission . ' se asignaron correctamente y estos permisos no se asignaron '. $noExistPermission,
                            'role' => $role
                        ];       
                    }
                }               
            }else{
                $response = [
                    'status' => false,
                    'message' => 'No existen permisos'
                ];
            }
        }else{
            $response = [
                'status' => false,
                'message' => 'No existen un rol con el id '.$role_id,
                'role' => null
            ];
        }

        return response()->json($response);
    }
}
