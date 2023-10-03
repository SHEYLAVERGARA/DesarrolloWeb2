## **[Router Manager](./RouterManager.php)**  

## Descripción
Este módulo se encarga de manejar las rutas de la aplicación, 
para ello se utiliza el módulo de express llamado Router, 
el cual permite crear un objeto que se comporta como un mini-aplicación, 
con sus propias rutas, middleware (Próximamente) y métodos HTTP.

## Uso
Para utilizar este módulo se debe importar el módulo de express y el módulo de RouterManager,
luego se debe crear una instancia de RouterManager y pasarle como parámetro,

```php
// Iniciamos el RouterManager
$router = new RouterManager();
// Obtenemos el método y la URI de la solicitud actual
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Creamos una ruta
$router->addRoute('GET|POST|PUT|DELETE', '/example/{param}', 'Controller@method');

// Ejecutamos el método handleRequest
$router->handleRequest($method, $uri);
```
## Proximamente
- Middleware
- Grupos de rutas
- Rutas con parámetros opcionales
