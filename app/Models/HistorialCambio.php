<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HistorialCambio extends Model
{
    use HasFactory;

    protected $table = "historial_cambios";
    protected $primaryKey = "id_cambio";
    public $incrementing = true;

    protected $fillable = [
        'fecha_cambio',
        'accion_realizada',
        'key_usuario',
        'key_reserva',
    ];

    protected $hidden = [
        "updated_at",
        "deleted_at"
    ];
}
