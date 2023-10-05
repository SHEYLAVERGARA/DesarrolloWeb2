<?php

namespace App\Models;

class Ciudades extends Model
{
    const  CIUDADES_INSERT_ERROR = 'CIUDADES_INSERT_ERROR';
    const  CIUDADES_INSERT_OK = 'CIUDADES_INSERT_OK';
    const  GET_CIUDADES_OK = 'GET_CIUDADES_OK';
    const  GET_ALL_CIUDADES = 'GET_ALL_CIUDADES';
    const CIUDADES_UPDATE_OK = 'CIUDADES_UPDATE_OK';
    const CIUDADES_DELETE_OK = 'CIUDADES_DELETE_OK';

    protected $fillable = [
        'id',
        'nombre',
        'codigo',
    ];
}