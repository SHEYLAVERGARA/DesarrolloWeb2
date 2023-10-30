<?php

namespace App\Controllers;

use App\Models\Sexo;
use Request\RequestManager;
class SexoController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $Sexos = (new Sexo())->select();
        $this->success($Sexos, status_event: Sexo::GET_ALL_SEXOS);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $Sexo = (new Sexo())->find($id);
         $this->success($Sexo, status_event: Sexo::GET_SEXOS_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $Sexo = new Sexo();

        // Responder con éxito y el usuario creado
        $this->success($Sexo->insert($data),"Registro satisfactorio", status_event: Sexo::SEXOS_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $Sexo = (new Sexo())->find($id);
        $Sexo->update($data);

        $this->success($Sexo, "Actualización satisfactoria", status_event: Sexo::SEXOS_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $Sexo = (new Sexo())->find($id);
        $Sexo->delete();
        $this->success($Sexo, "Eliminación satisfactoria", status_event: Sexo::SEXOS_DELETE_OK);
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