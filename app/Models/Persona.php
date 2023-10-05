<?php

namespace App\Models;

class Persona extends Model
{
    const  PERSONA_INSERT_ERROR = 'PERSONA_INSERT_ERROR';
    const  PERSONA_INSERT_OK = 'PERSONA_INSERT_OK';
    const  GET_PERSONA_OK = 'GET_PERSONA_OK';
    const  GET_ALL_PERSONAS = 'GET_ALL_PERSONAS';
    const PERSONA_UPDATE_OK = 'PERSONA_UPDATE_OK';
    const PERSONA_DELETE_OK = 'PERSONA_DELETE_OK';

    protected $fillable = [
        'id',
        'identificacion',
        'nombres',
        'apellidos',
        'email',
        'fecha_nacimiento',
        'hora_registro',
        'tiempo_evento',
        'observaciones',
        'ciudad_id',
        'persona_tipo_id',
        'sexo_id'
    ];

    public function sexo(): string
    {
        return Sexo::class;
    }

    public function ciudad(): string
    {
        return Ciudades::class;
    }

    public function personaTipo(): string
    {
        return PersonaTipo::class;
    }

}