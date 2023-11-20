<?php

namespace App\Models;

class Actividades extends Model
{
    const ACTIVIDADES_INSERT_OK = "ACTIVIDADES_INSERT_OK";
    const ACTIVIDADES_INSERT_ERROR = "ACTIVIDADES_INSERT_ERROR";
    const ACTIVIDADES_UPDATE_OK = "ACTIVIDADES_UPDATE_OK";
    const ACTIVIDADES_UPDATE_ERROR = "ACTIVIDADES_UPDATE_ERROR";
    const ACTIVIDADES_DELETE_OK = "ACTIVIDADES_DELETE_OK";
    const GET_ACTIVIDADES_OK = "GET_ACTIVIDADES_OK";
    const GET_ACTIVIDADES_ERROR = "GET_ACTIVIDADES_ERROR";
    const GET_ALL_ACTIVIDADES_OK = "GET_ALL_ACTIVIDADES_OK";
    
    protected $fillable = [
        "id",
        "unidad_id",
        "titulo",
        "descripcion",
        "actividadescol"
    ];

    public function unidad(): string
    {
        return Unidades::class;
    }
}
