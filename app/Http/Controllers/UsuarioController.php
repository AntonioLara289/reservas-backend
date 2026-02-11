<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    public function crear(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            "nombre" => 'required|string|max:120',
            "correo" => 'required|email|unique:usuarios,correo',
            "clave" => 'required|string|min:4',
        ]);      

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $usuario = Usuario::create([
            "nombre" => $request->nombre,
            "correo" => $request->correo,
            "clave" => Hash::make($request->clave),
            "estatus" => 1,
            "rol" => 1
        ]);

        $response ["usuario"] =  $usuario;
        $response ["mensaje"] = "Finalizado";

        return response()->json($response, 201);

    }

    public function login(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            "correo" => "required|email",
            "clave" => "required|string"
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $usuario_email = Usuario::where('correo', $request->correo)
        ->select('*', 'id_usuario')
        ->first();

        $mensaje_error = [];

        if(!$usuario_email){
            $mensaje_error ["code"] = 1;
            $mensaje_error ["error"] = "El correo no existe";
            return response()->json($mensaje_error, 400);
        }

        if(!Hash::check($request->clave, $usuario_email->clave)){
            $mensaje_error ["code"] = 2;
            $mensaje_error ["error"] = "La clave no coincide";
            return response()->json($mensaje_error, 400);
        }

        $token = auth()->guard('api')->attempt(
            [
                'correo'   => $request->correo,
                'password' => $request->clave 
            ]
        );

        $response = [
            "token" => $token,
            "usuario" => $usuario_email
        ];

        return response()->json($response, 200);

    }

    public function getTokenData(Request $request){
        
    
        $payload = auth()->payload();
        return $nombre = $payload->get('id');
        
    }
}
