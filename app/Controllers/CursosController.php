<?php

namespace App\Controllers;

use App\Models\Cursos;
use Helpers\ServerLogger;
use Request\RequestManager;
class PersonaController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $Personas = (new Cursos())->select();
        foreach ($Personas as $Persona) {
            $Persona->loadRelation(['sexo', 'ciudad', 'personaTipo']);
        }
        $this->success($Personas,"Listado de todas las personas,", status_event: Cursos::GET_ALL_CURSOS_OK);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $Persona = (new Cursos())->find($id);
         $Persona->loadRelation(['sexo', 'ciudad', 'personaTipo']);
         $this->success($Persona, status_event: Cursos::GET_CURSO_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager, ['identificacion' => ['required', 'integer', 'min:1']]);
        // Obtener los datos del formulario
        $data = $requestManager->all();
        // Convertimos nombres y apellidos a mayúsculas
        $data['nombres'] = strtoupper($data['nombres']);
        $data['apellidos'] = strtoupper($data['apellidos']);
        // Crear el nuevo usuario en la base de datos
        $Persona = new Cursos();

        // Responder con éxito y el usuario creado
        $this->success($Persona->insert($data)->loadRelation(['sexo', 'ciudad', 'personaTipo']),"Registro satisfactorio", status_event: Cursos::CURSO_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();
        // Convertimos nombres y apellidos a mayúsculas
        $data['nombres'] = strtoupper($data['nombres']);
        $data['apellidos'] = strtoupper($data['apellidos']);
        $Persona = (new Cursos())->find($id);
        $Persona->update($data);
        $Persona->loadRelation(['sexo', 'ciudad', 'personaTipo']);
        $this->success($Persona, "Actualización satisfactoria", status_event: Cursos::CURSO_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $Persona = (new Cursos())->find($id);
//        $Persona->delete();
        $this->success(null, "Eliminación satisfactoria", status_event: Cursos::CURSO_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, $other = []): void
    {
        $rules = [
            'nombres' => ['required', 'max:30'],
            'apellidos' => ['required','max:30'],
            'fecha_nacimiento' => ['required', 'date', 'date_format:Y-m-d'],
            'sexo' => ['required'],
            'email' => ['required','email', 'max:100', 'lowercase'],
            'hora_registro' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
            'tiempo_evento' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'observaciones' => ['alphanum'],
            'sexo_id' => ['required', 'integer', 'min:1'],
            'ciudad_id' => ['required', 'integer', 'min:1'],
            'persona_tipo_id' => ['required', 'integer', 'min:1'],
        ];

        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }
        $requestManager->validate($requestManager->all(), $rules);
    }


}