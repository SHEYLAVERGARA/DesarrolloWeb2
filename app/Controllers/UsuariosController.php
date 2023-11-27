<?php

namespace App\Controllers;

use App\Models\Usuarios;
use Request\RequestManager;
class UsuariosController extends Controller
{
    public function __construct(public $usuarios = new Usuarios()){}

    public function index(RequestManager $requestManager): void
     {
        $users = $this->usuarios->select();
        $this->success($users, "Usuarios Obtenidos", status_event: Usuarios::GET_ALL_USUARIOS_OK);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $user = $this->usuarios->find($id);
         $this->success($user, status_event: Usuarios::GET_USUARIOS_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        $this->validarLosDatosDeEntrada($requestManager, ['identificacion' => ['required', 'integer', 'min:1']]);
        $data = $requestManager->all();
        $user = new Usuarios();
        $this->success($user->insert($data),"Registro satisfactorio", status_event: Usuarios::USUARIOS_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        $this->validarLosDatosDeEntrada($requestManager);
        $data = $requestManager->all();
        $user = $this->usuarios->find($id);
        $user->update($data);
        $this->success($user, "Actualización satisfactoria", status_event: Usuarios::USUARIOS_INSERT_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $user = $this->usuarios->find($id);
        $user->delete();
        $this->success($user, "Eliminación satisfactoria", status_event: Usuarios::USUARIOS_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, $other = []): void
    {
        $rules = [
            'nombres' => ['required', 'max:255'],
            'apellidos' => ['required','max:255'],
            'email' => ['required', 'email', 'max:255', 'lowercase'],
            'identificacion' => ['required', 'integer', 'min:1'],
            'fecha_nacimiento' => ['required', 'date', 'date_format:Y-m-d'],
            'username' => ['required', 'max:255'],
            'password' => ['required', 'max:255'],
            'celular' => ['required'],
        ];
    
        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }
    
        $requestManager->validate($requestManager->all(), $rules);
    }
}