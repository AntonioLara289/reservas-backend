<?php

namespace App\Helpers;

class ReservaHelper
{
    // Definimos las constantes para usarlas en el código
    const PENDIENTE = 1;
    const ACEPTADA  = 2;
    const RECHAZADA = 3;
    const LIBERADA = 4;

    /**
     * Devuelve el nombre del estatus según el ID
     */
    public static function getEstatusNombre(int $id): string
    {
        return match ($id) {
            self::PENDIENTE => 'Pendiente',
            self::ACEPTADA  => 'Aceptada',
            self::RECHAZADA => 'Rechazada',
            self::LIBERADA => 'Liberada',
            default         => 'Desconocido',
        };
    }

    /**
     * Devuelve todos los estatus (útil para selects en el frontend)
     */
    public static function getTodos()
    {
        return [
            ['id' => self::PENDIENTE, 'nombre' => 'Pendiente'],
            ['id' => self::ACEPTADA,  'nombre' => 'Aceptada'],
            ['id' => self::RECHAZADA, 'nombre' => 'Rechazada'],
            ['id' => self::LIBERADA, 'nombre' => 'Liberada'],
        ];
    }
}