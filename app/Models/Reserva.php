<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reserva extends Model
{
    use HasFactory;

    protected $table = "reservas";
    protected $primaryKey = "id_reserva";
    public $incrementing = true;

    protected $fillable = [
        "key_espacio",
        "fecha",
        "hora",
        "key_usuario",
        "descripcion",
        "estatus",
    ];

    protected $hidden = [
        "updated_at",
        "deleted_at"
    ];
}
