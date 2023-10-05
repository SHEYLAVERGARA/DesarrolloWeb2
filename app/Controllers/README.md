## Controllers
La carpeta `app/Controllers` se encarga de tener los controladores de la aplicación, 
los cuales se encargan de recibir las peticiones de los usuarios y procesarlas para devolver una respuesta.

Los controladores a usar extienden de la clase `Controller` que se encuentra en `app/Controllers/Controller.php`,

De esta forma los controladores existentes son los siguientes:
- [PersonaController](PersonaController.php)
- [CiudadController](CiudadesController.php)
- [SexoController](SexoController.php)
- [PersonaTipoController](PersonaTipoController.php)
- [UserController](UserController.php)

Estos controladores tienen los metodos basicos para una api rest, los cuales son:
- index
- show
- store / create
- update
- destroy / delete

