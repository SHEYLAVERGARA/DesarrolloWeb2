<?php

namespace App\Controllers;

use App\Models\Actividades;
use Request\RequestManager;
class ActividadesController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $Ciudades = (new Actividades())->select();
        $this->success($Ciudades, status_event: Actividades::GET_ALL_ACTIVIDADES_OK);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $Ciudades = (new Actividades())->find($id);
         $this->success($Ciudades, status_event: Actividades::GET_ACTIVIDADES_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $Ciudades = new Actividades();

        // Responder con éxito y el usuario creado
        $this->success($Ciudades->insert($data),"Registro satisfactorio", status_event: Actividades::ACTIVIDADES_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $Ciudades = (new Actividades())->find($id);
        $Ciudades->update($data);

        $this->success($Ciudades, "Actualización satisfactoria", status_event: Actividades::ACTIVIDADES_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $Ciudades = (new Actividades())->find($id);
        $Ciudades->delete();
        $this->success($Ciudades, "Eliminación satisfactoria", status_event: Actividades::ACTIVIDADES_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */



     // FALTAN LAS VALIDACIONES
    protected function validarLosDatosDeEntrada(RequestManager $requestManager): void
    {
        $rules = [
            'nombre' => ['required', 'max:45'],
            'codigo' => ['required'],
        ];
        $requestManager->validate($requestManager->all(), $rules);
    }


}