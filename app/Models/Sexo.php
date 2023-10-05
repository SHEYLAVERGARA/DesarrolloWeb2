<?php

namespace App\Models;

class Sexo extends Model
{
    const  SEXOS_INSERT_ERROR = 'SEXOS_INSERT_ERROR';
    const  SEXOS_INSERT_OK = 'SEXOS_INSERT_OK';
    const  GET_SEXOS_OK = 'GET_SEXOS_OK';
    const  GET_ALL_SEXOS = 'GET_ALL_SEXOS';
    const SEXOS_UPDATE_OK = 'SEXOS_UPDATE_OK';
    const SEXOS_DELETE_OK = 'SEXOS_DELETE_OK';

    protected $fillable = [
        'id',
        'nombre',
    ];
}