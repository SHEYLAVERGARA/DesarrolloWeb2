<?php

namespace App\Models;

class PersonaTipo extends Model
{
    const  PERSONA_TIPO_INSERT_ERROR = 'PERSONA_TIPO_INSERT_ERROR';
    const  PERSONA_TIPO_INSERT_OK = 'PERSONA_TIPO_INSERT_OK';
    const  GET_PERSONA_TIPO_OK = 'GET_PERSONA_TIPO_OK';
    const  GET_ALL_PERSONA_TIPOS = 'GET_ALL_PERSONA_TIPOS';
    const PERSONA_TIPO_UPDATE_OK = 'PERSONA_TIPO_UPDATE_OK';
    const PERSONA_TIPO_DELETE_OK = 'PERSONA_TIPO_DELETE_OK';

    protected $fillable = [
        'id',
        'nombre',
    ];
}