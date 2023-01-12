<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function createUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'user_name' => 'required','email' => 'required|email|unique:users,email','password' => 'required'],
            [
                'user_name.required' => 'El campo nombre es requerido',
                'email.required' => 'El campo correo es requerido',
                'email.email' => 'El correo no tiene un formato válido',
                'email.unique' => 'El correo ya ha sido registrado',
                'password.required' => 'El campo contraseña es requerido'
            ]);

            $response = [];

            if($validateUser->fails()){
                $response = $this->returnValidateError($validateUser);
                return response()->json($response, 401);
            }

            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $response = $this->returnResponseToken(true, 'Usuario creado con éxito', $user);
            return response()->json($response, 200);
            
        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            ['email' => 'required|email','password' => 'required'],
            ['email.required' => 'El campo correo es requerido','email.email' => 'El correo no tiene un formato válido','password.required' => 'El campo contraseña es requerido']);
            $response = [];

            if($validateUser->fails()){
                $response = $this->returnValidateError($validateUser);
                return response()->json($response, 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                $response = $this->returnResponse(false,  'El correo electrónico o la contraseña no coinciden con nuestro registro', null );
                return response()->json($response, 401);
            }

            $user = User::where('email', $request->email)->first();
            $response = $this->returnResponseToken(true, 'El Usuario ha iniciado sesión con éxito', $user);

            return response()->json($response, 200);

        } catch (\Throwable $th) {
            return response()->json($this->returThrowable($th), 500);
        }
    }

    private function returThrowable($th){
        $response = [
            'status' => false,
            'message' => $th->getMessage()
        ];
        return $response;
    }

    private function returnValidateError($validateUser){
        $response = [
            'status' => false,
            'message' => 'Error de validación',
            'errors' => $validateUser->errors()
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
            'token' => $data->createToken("API TOKEN")->plainTextToken
        ];
        return $response;
    }
}
