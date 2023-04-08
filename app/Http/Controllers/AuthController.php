<?php

namespace App\Http\Controllers;

use App\Models\User;

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Validator,Hash};

use App\Http\Controllers\{PersonController,RoleController};

class AuthController extends Controller
{
    private $personCtrl;
    private $roleCtrl;

    public function __construct()
    {
        $this->personCtrl = new PersonController();
        $this->roleCtrl = new RoleController();    
    }

    public function createUser( Request $request ) //For app
    {
        try {   
            $reqUser = collect( $request->user )->all();    $response = [];  
            $validateUser = $this->validateUser( $reqUser );

            if ( $validateUser["status"] ) {
                $user = User::create([
                    'user_name' => $reqUser["user_name"],
                    'email' => $reqUser["email"],
                    'password' => Hash::make( $reqUser["password"] )
                ]);
                $response = $this->returnResponseToken(true, 'Usuario creado con éxito', $user);
            }else {
                $response = [ 
                    'status' => false, 
                    'message' => 'No se pudo crear el usuario',
                    'fails' => [ 'error_user' => $validateUser["error"] ?? "No presenta errores" ]
                ];
            }
            return response()->json( $response, 200 );
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function createUserFull( Request $request ) //For app
    {
        try {
            $response = [];

            $reqPerson = collect( $request->person )->all();
            $reqUser = collect( $request->user )->all();
            $reqRole = collect( $request->role )->all();;

            $validatePerson = $this->personCtrl->validation( $reqPerson ); 
            $validateUser = $this->validateUser( $reqUser );
            $validateRole = $this->validateRole( $reqRole );

            if($validatePerson["status"] && $validateUser["status"] && $validateRole["status"]){ //Si no tiene errores continua
                $responsePerson = $this->personCtrl->savePerson( $reqPerson );
                $person_id = $responsePerson["data"]->id;

                $user = User::create([
                    'user_name' => $reqUser["user_name"],
                    'email' => $reqUser["email"],
                    'password' => Hash::make( $reqUser["password"] )
                ]);
                
                $user->assignRole( $reqRole["name"] );
                $response = $this->returnResponseToken( true, 'Usuario creado con éxito', $user );
            } else {
                $response = [ 
                    'status' => false, 
                    'message' => 'No se pudo crear el usuario',
                    'fails' => [ 
                        'error_person' => $validatePerson["error"] ?? "No presenta errores",
                        'error_user' => $validateUser["error"] ?? "No presenta errores",
                        'error_role' => $validateRole["error"] ?? "No presenta errores"
                    ]
                ];
            }  
            return response()->json( $response, 200 );    
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function validateUser( $request ){ 
        $rules = [
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ];

        $messages = [
            'user_name.required' => 'El campo nombre es requerido',
            'email.required' => 'El campo correo es requerido',
            'email.email' => 'El correo no tiene un formato válido',
            'email.unique' => 'El correo ya ha sido registrado',
            'password.required' => 'El campo contraseña es requerido'
        ];
        return $this->validation( $request, $rules, $messages );
    }

    public function validateRole( $request ){
        $rules = [ 'name' => 'required|exists:roles,name' ];

        $messages = [ 'name.required' => 'El rol es requerido', 'name.exists' => 'El rol no existe' ];

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

    public function loginUser( Request $request )
    {
        try {
            $validateUser = Validator::make( $request->all(), 
            ['email' => 'required|email','password' => 'required'],
            [
                'email.required' => 'El campo correo es requerido',
                'email.email' => 'El correo no tiene un formato válido',
                'password.required' => 'El campo contraseña es requerido'
            ]);
            $response = [];

            if( $validateUser->fails() ){
                $response = $this->returnValidateError( $validateUser );
                return response()->json( $response, 401 );
            }

            if( !Auth::attempt( $request->only(['email', 'password'])) ){
                $response = $this->returnResponse( false,  'El correo electrónico o la contraseña no coinciden con nuestro registro', null );
                return response()->json( $response, 401 );
            }

            $user = User::where('email', $request->email)->first();
            $response = $this->returnResponseToken( true, 'El Usuario ha iniciado sesión con éxito', $user );
            return response()->json( $response, 200 );
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => 'Error del Servidor' ];
            return response()->json( $response, 500 );
        }
    }

    private function returnValidateError($validate){
        $response = [
            'status' => false,
            'message' => 'Error de validación',
            'errors' => $validate->errors()
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

    private function returnResponseToken($status,$message,$data){
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'token' => $data->createToken("API TOKEN")->plainTextToken
        ];
        return $response;
    }
}
