## **[RequestManager](./RequestManager.php)**
RequestManager es una clase que permite recibir las peticiones que realizan 
por los diferentes métodos HTTP, esta obtiene todos los datos de la petición, sea en los metodos 
GET, POST, PUT, DELETE, etc.


### Uso

```php
use Request\RequestManager;

$request = new RequestManager();
$datos = $request->all();
```

De esta manera se obtienen todos los datos de la petición, en los controladores de forma controlada. 

