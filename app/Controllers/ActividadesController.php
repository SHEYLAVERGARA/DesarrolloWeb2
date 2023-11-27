<?php

namespace App\Controllers;

use App\Models\Actividades;
use Request\RequestManager;

class ActividadesController extends Controller
{
    
    public function __construct(public $actividades = new Actividades())
    {}

    public function index(RequestManager $requestManager): void
    {
        $actividades = $this->actividades->select();
        foreach ($actividades as $actividad) {
            $actividad->loadRelation(['unidad']); 
        }
        $this->success($actividades, "Actividades Obtenidas", status_event: Actividades::GET_ALL_ACTIVIDADES_OK);
    }

    public function show(RequestManager $requestManager, $id): void
    {
        $requestManager->validate([
            'id' => $id
        ], [
            'id' => ['required', 'integer', 'min:1']
        ]);

        $actividad = $this->actividades->find($id);
        $actividad->loadRelation(['unidad']);
        $this->success($actividad, status_event: Actividades::GET_ACTIVIDADES_OK);
    }

    public function create(RequestManager $requestManager): void
    {
        $this->validarLosDatosDeEntrada($requestManager);
        $data = $requestManager->all();
        $actividad = new Actividades();
        $this->success($actividad->insert($data)->loadRelation(['unidad']), "Registro satisfactorio", status_event: Actividades::ACTIVIDADES_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        $this->validarLosDatosDeEntrada($requestManager);
        $data = $requestManager->all();
        $actividad = $this->actividades->find($id);
        $actividad->update($data);
        $actividad->loadRelation(['unidad']);
        $this->success($actividad, "Actualización satisfactoria", status_event: Actividades::ACTIVIDADES_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $actividad = $this->actividades->find($id);
        $actividad->delete();
        $this->success($actividad, "Eliminación satisfactoria", status_event: Actividades::ACTIVIDADES_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, $other = []): void
    {
        $rules = [
            'unidad_id' => ['required', 'integer', 'min:1'],
            'titulo' => ['required', 'max:255'],
            'descripcion' => ['required', 'string'],
            'actividadescol' => ['max:255'],
        ];
    
        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }
    
        $requestManager->validate($requestManager->all(), $rules);
    }
}
