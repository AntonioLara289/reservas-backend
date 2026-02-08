<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Espacio extends Model
{
    
    use HasFactory;

    protected $table = "espacios";
    protected $primaryKey = "id_espacio";
    public $incrementing = true;

    protected $fillable = [
        "name",
        "descripcion",
        "capacidad",
        "estatus",
    ];

    protected $hidden = [
        "updated_at",
        "deleted_at",
    ];
}
