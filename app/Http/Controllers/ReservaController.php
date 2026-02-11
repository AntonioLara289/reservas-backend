<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Espacio;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    public function crear(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [

            'key_espacio' => 'required|integer|exists:espacios,id_espacio',
            'fecha' => 'required|date',
            'hora' => 'required|date',
            'descripcion' => 'required|string|min:10',
            'solicitante' => 'required|string|min:3'

        ]);
        

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $status_code = 400;

        $espacio = Espacio::where('id_espacio', $request->key_espacio)->first();

        if($espacio->estatus != 1){

            $response ['mensaje'] = "El espacio no se encuentra disponible";
            return response()->json($response, 400);

        }

        DB::beginTransaction();

        try{

            $reserva = Reserva::create([
                'key_espacio' => $request->key_espacio,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'descripcion' => $request->descripcion,
                'solicitante' => $request->solicitante,
                'estatus' => 1
            ]);

            $espacio->estatus = 2;
            $espacio->save();

            $response = [
                'reserva' => $reserva,
                'folio' => $reserva->id_reserva
            ];
            
            DB::commit();

        }catch(\Throw $e){
            
            $response = [
                'error' => $e,
                'data' => $request
            ];

            DB::rollback();
        }


        return response()->json($reserva, 201);
    }

    public function verificarReserva(){
        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'id_reserva' => 'required|integer|exists:reservas,id_reserva'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

     
        $reservacion = Reserva::where('id_reserva', $request->id_reserva)->firs();
        
        return response()->json($reservacion, 200);
    }

    public function getReservas(){

        $reservas = Reserva::get();

        return response()->json($reservas, 200);
    }
}
