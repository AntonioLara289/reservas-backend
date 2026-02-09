<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable implements JWTSubject
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

    /**
     * INDISPENSABLE: Laravel busca la columna 'password'. 
     * Con esto le decimos que use 'clave'.
     */
    public function getAuthPassword()
    {
        return $this->clave;
    }
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retorna un array con la información personalizada que quieres 
     * que el token lleve dentro (esto es lo que podrás "desencriptar").
     */
    public function getJWTCustomClaims()
    {
        return [
            'id' => $this->id_usuario,
            'nombre' => $this->nombre,
            'correo' => $this->correo,
            'rol'    => $this->rol,
            'status' => $this->estatus
        ];
    }
}
