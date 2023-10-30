<?php

namespace App\Controllers;

use App\Models\Ciudades;
use Request\RequestManager;
class CiudadesController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $Ciudades = (new Ciudades())->select();
        $this->success($Ciudades, status_event: Ciudades::GET_ALL_CIUDADES);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $Ciudades = (new Ciudades())->find($id);
         $this->success($Ciudades, status_event: Ciudades::GET_CIUDADES_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $Ciudades = new Ciudades();

        // Responder con éxito y el usuario creado
        $this->success($Ciudades->insert($data),"Registro satisfactorio", status_event: Ciudades::CIUDADES_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $Ciudades = (new Ciudades())->find($id);
        $Ciudades->update($data);

        $this->success($Ciudades, "Actualización satisfactoria", status_event: Ciudades::CIUDADES_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $Ciudades = (new Ciudades())->find($id);
        $Ciudades->delete();
        $this->success($Ciudades, "Eliminación satisfactoria", status_event: Ciudades::CIUDADES_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager): void
    {
        $rules = [
            'nombre' => ['required', 'max:45'],
            'codigo' => ['required'],
        ];
        $requestManager->validate($requestManager->all(), $rules);
    }


}