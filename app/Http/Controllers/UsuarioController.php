<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class UsuarioController extends Controller
{

    public function Listar(){

        $Usuario = Usuario::all();
        return $Usuario;

    }

    public function Buscar($IdUsuario){

        $Usuario = Usuario::find($IdUsuario);
        return $Usuario;

    }

    public function Eliminar(Request $Request){

        $Json = $Request -> input('json', null);

        $ParamsArray = json_decode($Json, true);

        $Usuario = Usuario::find($ParamsArray['id']);

        $Usuario -> delete();

        $Respuesta = array(
            'status'      =>     'success',
            'code'        =>     '200',
            'message'     =>     'Usuario eliminado',
            'persona'     =>     $Usuario
        );
        
        return response() -> json($Respuesta,$Respuesta['code']);

    }

    public function Actualizar(Request $Request){

        $Json = $Request -> input('json', null);

        $ParamsArray = json_decode($Json, true);

        $Usuario = Usuario::find($ParamsArray['id']);

        $Validacion = \Validator::make($ParamsArray,[
            'nombre'       =>     'required',
            'apellido'     =>     'required',
            'username'     =>     'required|alpha',
            'email'        =>     'required|email',
            'password'     =>     'required'
        ]);

        if($Validacion -> fails()){

            $Respuesta = array(
                'status'      =>    'error',
                'code'        =>    '500',
                'message'     =>    'Error de validacion',
                'errors'      =>    $Validacion -> fails()
            );

        } else {

            $Usuario -> nombre = $ParamsArray['nombre'];
            $Usuario -> apellido = $ParamsArray['apellido'];
            $Usuario -> username = $ParamsArray['username'];
            $Usuario -> email = $ParamsArray['email'];
            $Usuario -> password = $ParamsArray['password'];    

            $Usuario -> save();

            $Respuesta = array(
                'status'      =>    'success',
                'code'        =>    '200',
                'message'     =>    'Registro Agregado',
                'usuario'      =>    $Usuario
            );

        }

        return response() -> json($Respuesta,$Respuesta['code']);

    }

    public function Agregar(Request $Request){

        $Json = $Request -> input('Json', null);

        $ParamsArray = json_decode($Json, true);

        $Validacion = \Validator::make($ParamsArray,[
            'nombre'       =>     'required',
            'apellido'     =>     'required',
            'username'     =>     'required|alpha',
            'email'        =>     'required|email',
            'password'     =>     'required'
        ]);

        if($Validacion -> fails()){

            $Respuesta = array( 
                'status'      =>    'error',
                'code'        =>    '500',
                'message'     =>    'Error de validacion',
                'errors'      =>    $Validacion -> errors()
            );

        } else {

            $Usuario = new Usuario();

            $Usuario -> nombre = $ParamsArray['nombre'];
            $Usuario -> apellido = $ParamsArray['apellido'];
            $Usuario -> username = $ParamsArray['username'];
            $Usuario -> email = $ParamsArray['email'];
            $Usuario -> password = $ParamsArray['password'];    

            $Usuario -> save();

            $Respuesta = array(
                'status'      =>    'success',
                'code'        =>    '200',
                'message'     =>    'Registro Agregado',
                'usuario'      =>    $Usuario
            );

        }

        return response() -> json($Respuesta, $Respuesta['code']);

    }

}
