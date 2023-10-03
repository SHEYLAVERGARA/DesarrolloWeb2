## **[ResponseManager](./ResponseManager.php)** 
ResponseManager es una clase para configurar la respuesta de la aplicacion,
en caso de que ocurra un error o se quiera enviar un mensaje al usuario.

### Uso
```php

use App\Managers\ResponseManager;

$response = new ResponseManager();

$response->send([
        'status' => 'success',
        'message' => 'Mensaje de exito'
    ], 200 , [
    'Content-Type' => 'application/json'
]);

```

De esta manera se puede enviar una respuesta al usuario, en caso de que no se envie. 