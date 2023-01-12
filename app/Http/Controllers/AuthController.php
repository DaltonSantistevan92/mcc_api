<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'user_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ],
            [
                'user_name.required' => 'El campo nombre es requerido',
                'email.required' => 'El campo correo es requerido',
                'email.email' => 'El correo no tiene un formato válido',
                'email.unique' => 'El correo ya ha sido registrado',
                'password.required' => 'El campo contraseña es requerido'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Usuario creado con éxito',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ],
            [
                'email.required' => 'El campo correo es requerido',
                'email.email' => 'El correo no tiene un formato válido',
                'password.required' => 'El campo contraseña es requerido'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validacion',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'El correo electrónico o la contraseña no coinciden con nuestro registro.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'El Usuario ha iniciado sesión con éxito',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
