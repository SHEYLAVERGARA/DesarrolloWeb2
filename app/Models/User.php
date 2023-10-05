<?php

namespace App\Models;

class User extends Model
{
    const  USER_INSERT_ERROR = 'USER_INSERT_ERROR';
    const  USER_INSERT_OK = 'USER_INSERT_OK';
    const  GET_USER_OK = 'GET_USER_OK';
    const  GET_ALL_USERS = 'GET_ALL_USERS';
    const USER_UPDATE_OK = 'USER_UPDATE_OK';

    protected string $primaryKey = 'identificacion';
    protected $fillable = [
        'identificacion',
        'nombres',
        'apellidos',
        'email',
        'fecha_nacimiento',
        'sexo',
        'hora_registro',
        'tiempo_accion',
        'observaciones'
    ];
}