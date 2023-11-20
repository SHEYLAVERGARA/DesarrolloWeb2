<?php

namespace App\Models;


class Cursos extends Model
{

    const CURSO_INSERT_OK = "CURSO_INSERT_OK";
    const CURSO_INSERT_ERROR = "CURSO_INSERT_ERROR";
    const CURSO_UPDATE_OK = "CURSO_UPDATE_OK";
    const CURSO_UPDATE_ERROR = "CURSO_UPDATE_ERROR";
    const CURSO_DELETE_OK = "CURSO_DELETE_OK";
    const GET_CURSO_OK = "GET_CURSO_OK";
    const GET_CURSO_ERROR = "GET_CURSO_ERROR";
    const GET_ALL_CURSOS_OK = "GET_ALL_CURSOS_OK";

     
    
    protected $fillable = [
        "id",
        "nombre",
        "creditos"
    ];
}