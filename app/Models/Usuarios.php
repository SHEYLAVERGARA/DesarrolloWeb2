<?php

namespace App\Models;

class Usuarios extends Model
{
    const USUARIOS_INSERT_OK = "USUARIOS_INSERT_OK";
    const USUARIOS_INSERT_ERROR = "USUARIOS_INSERT_ERROR";
    const USUARIOS_UPDATE_OK = "USUARIOS_UPDATE_OK";
    const USUARIOS_UPDATE_ERROR = "USUARIOS_UPDATE_ERROR";
    const USUARIOS_DELETE_OK = "USUARIOS_DELETE_OK";
    const GET_USUARIOS_OK = "GET_USUARIOS_OK";
    const GET_USUARIOS_ERROR = "GET_USUARIOS_ERROR";
    const GET_ALL_USUARIOS_OK = "GET_ALL_USUARIOS_OK";
    
    protected $fillable = [
        "id",
        "nombres",
        "apellidos",
        "email",
        "identificacion",
        "fecha_nacimiento",
        "username",
        "password",
        "celular"
    ];
}
