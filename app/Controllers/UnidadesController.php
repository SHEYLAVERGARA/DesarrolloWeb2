<?php

namespace App\Controllers;

use App\Models\Unidades;
use Request\RequestManager;
class UnidadesController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $PersonaTipos = (new Unidades())->select();
        $this->success($PersonaTipos, status_event: Unidades::GET_ALL_UNIDADES_OK);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $PersonaTipo = (new Unidades())->find($id);
         $this->success($PersonaTipo, status_event: Unidades::GET_UNIDADES_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $PersonaTipo = new Unidades();

        // Responder con éxito y el usuario creado
        $this->success($PersonaTipo->insert($data),"Registro satisfactorio", status_event: Unidades::UNIDADES_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $PersonaTipo = (new Unidades())->find($id);
        $PersonaTipo->update($data);

        $this->success($PersonaTipo, "Actualización satisfactoria", status_event: Unidades::UNIDADES_INSERT_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $PersonaTipo = (new Unidades())->find($id);
        $PersonaTipo->delete();
        $this->success($PersonaTipo, "Eliminación satisfactoria", status_event: Unidades::UNIDADES_DELETE_OK);
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