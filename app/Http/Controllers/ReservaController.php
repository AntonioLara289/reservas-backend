<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Espacio;
use App\Models\HistorialCambio;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Helpers\ReservaHelper;

class ReservaController extends Controller
{
    public function crear(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [

            'key_espacio' => 'required|integer|exists:espacios,id_espacio',
            'fecha' => 'required|date',
            'hora' => 'required|date',
            'descripcion' => 'required|string|min:8',
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
                'fecha' => Carbon::parse($request->fecha),
                'hora' => Carbon::parse($request->hora),
                'descripcion' => $request->descripcion,
                'solicitante' => $request->solicitante,
                'estatus' => 1
            ]);

            $payload = auth()->payload();

            HistorialCambio::create([
                'fecha_cambio' => Carbon::now(),
                'accion_realizada' => "Reservación agendada",
                'key_usuario' => $payload->get('id'),
                'key_reserva' => $reserva->id_reserva
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

    public function actualizar(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [

            'id_reserva' => 'required|integer|exists:reservas,id_reserva',
            'key_espacio' => 'required|integer|exists:espacios,id_espacio',
            'fecha' => 'required|date',
            'hora' => 'required|date',
            'descripcion' => 'required|string|min:8',
            'solicitante' => 'required|string|min:3',

        ]);
        

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        DB::beginTransaction();

        try{

            $reserva = Reserva::where('id_reserva', $request->id_reserva)
            ->update([
                'key_espacio' => $request->key_espacio,
                'fecha' => Carbon::parse($request->fecha),
                'hora' => Carbon::parse($request->hora),
                'descripcion' => $request->descripcion,
                'solicitante' => $request->solicitante,
            ]);

            $payload = auth()->payload();

            HistorialCambio::create([
                'fecha_cambio' => Carbon::now(),
                'accion_realizada' => "Reservación actualizada",
                'key_usuario' => $payload->get('id'),
                'key_reserva' => $request->id_reserva
            ]);

            $response = [
                'reserva' => $reserva,
                'folio' => $request->id_reserva
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

    public function getReservas(Request $request){

        $reservas = Reserva::get();

        foreach ($reservas as $key => $item) {
            $item->reserva_str = ReservaHelper::getEstatusNombre($item->estatus);
        }

        return response()->json($reservas, 200);
    }

    public function consultarReservacion(Request $request){

        $response = [];

        $validator = Validator::make($request->all(), $rules = [
            'id_reserva' => 'required|integer|exists:reservas,id_reserva'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $reserva = Reserva::where('id_reserva', $request->id_reserva)->first();

        $reserva->reserva_str = ReservaHelper::getEstatusNombre($reserva->estatus);

        return response()->json($reserva, 200);
    }

    public function getEstatusReserva(Request $request){

        $estatus = ReservaHelper::getTodos();

        return response()->json($estatus, 200);
    }

    public function cambiarEstatus(Request $request){

        $response = [];
        $status_code = 400;

        $validator = Validator::make($request->all(), $rules = [

            'id_reserva' => 'required|integer|exists:reservas,id_reserva',
            'estatus' => 'required|integer'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), $status_code);
        }

        $reserva = Reserva::where('id_reserva', $request->id_reserva)->first();
        $espacio = Espacio::where('id_espacio', $reserva->key_espacio)->first();

        DB::beginTransaction();

        try{


            $accion_realizada = "";
            
            $reserva->estatus = $request->estatus;
            $reserva->save();
            
            if($request->estatus == 3){

                //Liberar ya que se cancelo
                $espacio->estatus = 1;
                $espacio->save();

                $accion_realizada = "Reservación rechazada";
            }

            if($request->estatus == 4){

                //liberar
                $espacio->estatus = 1;
                $espacio->save();
                $accion_realizada = "Reservación liberada";
            }

            if($request->estatus == 2){

                $accion_realizada = "Reservación aceptada";
                
            }

            $payload = auth()->payload();

            HistorialCambio::create([
                'fecha_cambio' => Carbon::now(),
                'accion_realizada' => $accion_realizada,
                'key_usuario' => $payload->get('id'),
                'key_reserva' => $request->id_reserva
            ]);

            $status_code = 200;

            $response ["mensaje"] = "Proceso exitoso";
            DB::commit();

        }catch(\Throw $e){
            
            $response = [
                'error' => $e,
                'data' => $request
            ];

            $status_code = 500;

            DB::rollback();
        }

        return response()->json($response, $status_code);

    }

}
