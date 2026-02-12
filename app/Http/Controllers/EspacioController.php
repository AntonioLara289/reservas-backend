<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Espacio;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EspacioController extends Controller
{
    
    public function crear(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'name' => 'required|string|min:5',
            'descripcion' => 'required|string|min:8',
            'capacidad' => 'required|integer|min:1',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $espacio = Espacio::create([
            'name' => $request->name,
            'descripcion' => $request->descripcion,
            'capacidad' => $request->capacidad,
            'estatus' => 1
        ]);

        return response()->json($espacio, 201);
    }

    public function verificarDisponibilidad(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'id_espacio' => 'required|integer|exists:espacios,id_espacio'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $espacio = Espacio::where('id_espacio', $request->id_espacio)->select('estatus')->first();

        return response()->json($espacio, 200);
    }

    public function get(Request $request){

        $mostrar_solo_disponibles = $request->boolean('mostrar_solo_disponibles', true);

        $espacios = Espacio::
        when($mostrar_solo_disponibles == true, function($quer) use ($mostrar_solo_disponibles) {
            $quer->where('estatus', 1);
        })
        ->get();

        return response()->json($espacios, 200);
    }
}
