<?php

namespace App\Controllers;

use App\Models\PersonaTipo;
use request\RequestManager;
class PersonaTipoController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $PersonaTipos = (new PersonaTipo())->select();
        $this->success($PersonaTipos, status_event: PersonaTipo::GET_ALL_PERSONA_TIPOS);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $PersonaTipo = (new PersonaTipo())->find($id);
         $this->success($PersonaTipo, status_event: PersonaTipo::GET_PERSONA_TIPO_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $PersonaTipo = new PersonaTipo();

        // Responder con éxito y el usuario creado
        $this->success($PersonaTipo->insert($data),"Registro satisfactorio", status_event: PersonaTipo::PERSONA_TIPO_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $PersonaTipo = (new PersonaTipo())->find($id);
        $PersonaTipo->update($data);

        $this->success($PersonaTipo, "Actualización satisfactoria", status_event: PersonaTipo::PERSONA_TIPO_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $PersonaTipo = (new PersonaTipo())->find($id);
        $PersonaTipo->delete();
        $this->success($PersonaTipo, "Eliminación satisfactoria", status_event: PersonaTipo::PERSONA_TIPO_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager): void
    {
        $rules = [
            'nombre' => ['required', 'max:45'],
        ];
        $requestManager->validate($requestManager->all(), $rules);
    }


}