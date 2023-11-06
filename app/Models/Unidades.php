<?php

namespace App\Models;

class Unidades extends Model
{
    const UNIDADES_INSERT_OK = "UNIDADES_INSERT_OK";
    const UNIDADES_INSERT_ERROR = "UNIDADES_INSERT_ERROR";
    const UNIDADES_UPDATE_OK = "UNIDADES_UPDATE_OK";
    const UNIDADES_UPDATE_ERROR = "UNIDADES_UPDATE_ERROR";
    const UNIDADES_DELETE_OK = "UNIDADES_DELETE_OK";
    const GET_UNIDADES_OK = "GET_UNIDADES_OK";
    const GET_UNIDADES_ERROR = "GET_UNIDADES_ERROR";
    const GET_ALL_UNIDADES_OK = "GET_ALL_UNIDADES_OK";
    
    protected $fillable = [
        "id",
        "cursos_id",
        "usuario_id",
        "nombre",
        "introduccion",
        "fecha_creacion",
        "hora_creacion",
        "activa"
    ];
}
