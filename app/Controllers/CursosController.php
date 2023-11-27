<?php

namespace App\Controllers;

use App\Models\Cursos;
use Request\RequestManager;

class CursosController extends Controller
{
    public function __construct(public $cursos = new Cursos()){}

    public function index(RequestManager $requestManager): void
    {
        $cursos = $this->cursos->select();
        $this->success($cursos, "Cursos Obtenidos", status_event: Cursos::GET_ALL_CURSOS_OK);
    }

    public function show(RequestManager $requestManager, $id): void
    {
        $requestManager->validate([
            'id' => $id
        ], [
            'id' => ['required', 'integer', 'min:1']
        ]);

        $curso = $this->cursos->find($id);
        $this->success($curso, status_event: Cursos::GET_CURSO_OK);
    }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo curso en la base de datos
        $curso = new Cursos();

        // Responder con éxito y el curso creado
        $this->success($curso->insert($data), "Registro satisfactorio", status_event: Cursos::CURSO_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();
        $curso = $this->cursos->find($id);
        $curso->update($data);

        $this->success($curso, "Actualización satisfactoria", status_event: Cursos::CURSO_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $curso = $this->cursos->find($id);
        $curso->delete();
        $this->success(null, "Eliminación satisfactoria", status_event: Cursos::CURSO_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @param array $other
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, array $other = []): void
    {
        $rules = [
            'nombre' => ['required', 'max:255'],
            'creditos' => ['required', 'integer', 'min:0'],
        ];

        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }
        $requestManager->validate($requestManager->all(), $rules);
    }
}
