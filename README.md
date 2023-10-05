# Proyecto de la materia de Programación Web 2
Este proyecto es un desarrollo de una aplicacion paso a paso, aplicando los conocimientos aprendidos en la materia de Programación Web 2. 

Usaran patrones y buenas practicas de programación, de la misma forma se explicara en el codigo el funcionamiento y el porque. 

### Puede encontrar informacion de las peticiones a la api en la carpeta `postman`
En esta carpeta se encontrara un archivo `json`, el cual se puede importar en postman, y se encontraran las peticiones a la api, con sus respectivas respuestas.
**[Postman](Postman/Taller.postman_collection.json)**
Cargue el archivo en la aplicacion postman y podra ver las peticiones a la api.

## Antes de iniciar
Se debe instalar composer, para poder usar psr-4 con el autoload proporcinado por composer.
`composer install `

De esta forma, este sera la estructura de carpetas:

```
├── composer.json
├── composer.lock
├── docker-compose.yml
├── .gitignore
├── README.md
├── public
│   ├── index.php
├── README.md
├── app
│   ├── Controllers
│   │   ├── Controller.php
│   │   └── UserController.php
│   ├── Models
│   │   ├── Model.php
│   │   └── User.php
├── database
│   ├── createDatabase.sql
│   ├── MysqlConnection.php
├── helpers
│   ├── ServerLogger.php
├── request
│   ├── ReqiestManager.php
├── response
│   ├── ResponseContract.php
│   ├── ResponseManager.php
├── routes
│   ├── RouterManager.php
│   ├── routes.php
├── validations
│   ├── Validator.php
│   ├── ValidatorContract.php
```
Se usara esta estructura de carpetas, para poder tener un orden en el proyecto.
Doonde la carpeta `app`, es donde se encontraran los `modelos` y `controladores`, la carpeta `database`, es donde se encontrara la conexion a la base de datos, la carpeta `helpers`, es donde se encontraran las funciones de ayuda, la carpeta `request`, es donde se encontraran las funciones de ayuda para las peticiones, la carpeta `response`, es donde se encontraran las funciones de ayuda para las `respuestas`, la carpeta `routes`, es donde se encontraran las rutas de la `aplicacion`, y la carpeta `validations`, es donde se encontraran las funciones de ayuda para las `validaciones`.

En cada carpeta se encontrara un archivo `README.md`, donde se explicara el funcionamiento de cada carpeta y sus archivos.

## Iniciar el servidor
Iniciamos el servidor en localhost:8000 con php.
```
php -S localhost:8000 -t public/ 
```

## Necesita instalar docker, y docker composer 
Esto para poder inicializar la base de datos, esto en temas de facilidad de iniciación de la base de datos. 
puede encontrar documentacion de docker en: https://docs.docker.com/get-docker/
y ejecuta el siguiente comando:
``` 
docker-compose up -d 
```

## Iniciar la base de datos
Para iniciar la base de datos se debera tener configurado el archivo `.env` con los datos de la base de datos, y el cual al iniciar el projecto verificara la existencia de la base de datos, y si no existe montara los datos de la misma que se cuentran en el archivo
`database/createDatabase.sql`


Trabajo realizado por Sheyal Vergara y Pedro Lopez. 