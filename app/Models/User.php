<?php

namespace App\Models;

class User extends Model
{
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