<?php

namespace App\Controllers;

use App\Models\Unidades;
use Request\RequestManager;

class UnidadesController extends Controller
{
    public function index(RequestManager $requestManager): void
    {
        $unidades = (new Unidades())->select();
        foreach ($unidades as $unidad) {
            $unidad->loadRelation(['cursos', 'usuario']);
        }
        $this->success($unidades, "Unidades Obtenidas",status_event: Unidades::GET_ALL_UNIDADES_OK);
    }

    public function show(RequestManager $requestManager, $id): void
    {
        $requestManager->validate([
            'id' => $id
        ], [
            'id' => ['required', 'integer', 'min:1']
        ]);

        $unidad = (new Unidades())->find($id);
        $unidad->loadRelation(['cursos', 'usuario']);
        $this->success($unidad, status_event: Unidades::GET_UNIDADES_OK);
    }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear la nueva unidad en la base de datos
        $unidad = new Unidades();

        // Responder con éxito y la unidad creada
        $this->success($unidad->insert($data)->loadRelation(['cursos', 'usuario']), "Registro satisfactorio", status_event: Unidades::UNIDADES_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $unidad = (new Unidades())->find($id);
        $unidad->update($data);
        $unidad->loadRelation(['cursos', 'usuario']);
        $this->success($unidad, "Actualización satisfactoria", status_event: Unidades::UNIDADES_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $unidad = (new Unidades())->find($id);
        $unidad->delete();
        $this->success($unidad, "Eliminación satisfactoria", status_event: Unidades::UNIDADES_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, $other = []): void
    {
        $rules = [
            'cursos_id' => ['required', 'integer', 'min:1'],
            'usuario_id' => ['required', 'integer', 'min:1'],
            'nombre' => ['required', 'max:255'],
            'introduccion' => ['required', 'max:255'],
            'fecha_creacion' => ['required', 'date', 'date_format:Y-m-d'],
            'hora_creacion' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
            'activa' => ['required', 'boolean'],
        ];

        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }

        $requestManager->validate($requestManager->all(), $rules);
    }

}
