<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
 
    use HasFactory;

    protected $table = "usuarios";
    protected $primaryKey = "id_usuario";
    public $incrementing = true;


    protected $fillable = [
        "nombre",
        "correo",
        "clave",
        "estatus",
        "rol"
    ];

    protected $hidden = [
        "clave",
        "updated_at",
        "deleted_at"
    ];
}
