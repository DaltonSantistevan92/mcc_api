<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PersonController extends Controller
{
    public function getAll()
    {
        try {
            $people = Person::all();
            if ( $people->count() > 0 ) {
                $response = [ 'status' => true, 'message' => 'Existen Datos', 'data' => $people ];
            }else {
                $response = [ 'status' => false, 'message' => 'No Existen Datos' ];
            }
            return response()->json( $response, 200 );
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function getById( $person_id )
    {
        $id = intval($person_id);   $response = [];

        try {
            $people = Person::find( $id );
            if ( $people ) {
                $response = [ 'status' => true, 'message' => 'Existen Datos', 'data' => $people ];
            } else {
                $response = [ 'status' => false, 'message' => 'No Existen Datos' ];
            }
            return response()->json( $response, 200 );        
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function create( Request $request )//metodo que consume el cliente
    { 
        $personRequest = $request->all();   $response = [];
        try {
            $validatePerson = $this->validation( $personRequest );

            if ( $validatePerson['status'] ) {
                $response = $this->savePerson( $personRequest );
            } else {
                $response = $validatePerson;
            }
            return response()->json( $response, 200 );                    
        } catch ( \Throwable $th ) {
            $response = [ 'status' => false, 'message' => $th->getMessage() ];
            return response()->json( $response, 500 );
        }
    }

    public function validation( $request )
    {
        $response = [ 'status' => true, 'message' => 'No hubo errores' ];

        $rules = [
            'identification' => 'required|unique:people,identification',
            'first_name' => 'required',
            'last_name' => 'required',
            'sex_id' => 'required|exists:sexes,id'
        ];

        $messages = [
            'identification.required' => 'El número de identificación es requerido',
            'identification.unique' => 'El número de identificación existe en nuestros registros',
            'first_name.required' => 'El nombre es requerido',
            'last_name.required' => 'El apellido es requerido',
            'sex_id.required' => 'El tipo de sexo es requerido',
            'sex_id.exists' => 'El tipo de sexo no existe'
        ];

        $validatePerson = Validator::make( $request, $rules, $messages );

        if ( $validatePerson->fails() ) {
            $response = [ 'status' => false, 'message' => 'Error de validación', 'error' => $validatePerson->errors() ];
        }

        return $response;
    }

    public function savePerson( $data )
    {  
        $people = Person::create( $data );   $response = [];

        if ( $people ) {
            $response = [ 'status' => true, 'message' => 'Se creó el registro correctamente', 'data' => $people ];
        } else {
            $response = [ 'status' => false, 'message' => 'No se pudo guardar el registro' ];
        }

        return $response;
    }




    
}
