<?php

namespace App\Controllers;

use App\Models\User;
use request\RequestManager;
class UserController extends Controller
{
     public function index(RequestManager $requestManager): void
     {
        $users = (new User())->select();
        $this->success($users);
     }

     public function show(RequestManager $requestManager, $id): void
     {
         $requestManager->validate([
             'id' => $id
         ], [
             'id' => ['required', 'integer', 'min:1']
         ]);

         $user = (new User())->select(where: [
             [ 'identificacion', '=', $id]
         ]);
         $this->success($user, status_event: self::GET_USER_OK);
     }

    public function create(RequestManager $requestManager): void
    {
        // Validar los datos de entrada
        $requestManager->validate($requestManager->all(), [
            'identificacion' => ['required', 'integer', 'min:1'],
            'nombres' => ['required',  'max:30'],
            'apellidos' => ['max:30', 'nullable'],
            'fecha_nacimiento' => ['required', 'date', 'date_format:Y-m-d'],
            'sexo' => ['required'],
            'email' => ['email', 'max:100', 'nullable', 'lowercase'],
            'hora_registro' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$/'],
            'tiempo_accion' => ['required', 'date', 'date_format:Y-m-d H:i:s'],
            'observaciones' => ['alphanum', 'nullable'],
        ]);

        // Obtener los datos del formulario
        $data = $requestManager->all();

        // Crear el nuevo usuario en la base de datos
        $user = new User();


        // Responder con éxito y el usuario creado
        $this->success($user->insert($data), status_event: self::USER_INSERT_OK);
    }


}