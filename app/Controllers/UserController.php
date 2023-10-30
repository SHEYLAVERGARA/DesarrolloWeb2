<?php

namespace App\Controllers;

use App\Models\User;
use Helpers\ServerLogger;
use Request\RequestManager;
class UserController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $users = (new User())->select();
        $this->success($users, status_event: User::GET_ALL_USERS);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $user = (new User())->find($id);
         $this->success($user, status_event: User::GET_USER_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager, ['identificacion' => ['required', 'integer', 'min:1']]);
        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $user = new User();

        // Responder con éxito y el usuario creado
        $this->success($user->insert($data),"Registro satisfactorio", status_event: User::USER_INSERT_OK);
    }

    public function update(RequestManager $requestManager, $id): void
    {
        // Validar los datos de entrada
        $this->validarLosDatosDeEntrada($requestManager);

        $data = $requestManager->all();

        $user = (new User())->find($id);
        $user->update($data);

        $this->success($user, "Actualización satisfactoria", status_event: User::USER_UPDATE_OK);
    }

    public function delete(RequestManager $requestManager, $id): void
    {
        $user = (new User())->find($id);
        $user->delete();
        $this->success($user, "Eliminación satisfactoria", status_event: User::USER_DELETE_OK);
    }

    /**
     * @param RequestManager $requestManager
     * @return void
     */
    protected function validarLosDatosDeEntrada(RequestManager $requestManager, $other = []): void
    {
        $rules = [
            'nombres' => ['required', 'max:30'],
            'apellidos' => ['max:30'],
            'fecha_nacimiento' => ['required', 'date', 'date_format:Y-m-d'],
            'sexo' => ['required'],
            'email' => ['email', 'max:100', 'lowercase'],
            'hora_registro' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
            'tiempo_accion' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'observaciones' => ['alphanum', 'nullable'],
        ];

        if (!empty($other)) {
            $rules = array_merge($rules, $other);
        }
        $requestManager->validate($requestManager->all(), $rules);
    }


}