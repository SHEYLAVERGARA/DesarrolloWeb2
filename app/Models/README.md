## Models
Esta carpeta se encarga de tener los modelos de la base de datos, 
los cuales son utilizados por los controladores para realizar 
las operaciones de la base de datos.

Está compuesta de una clase ```Model``` que es la clase padre, la que se encargara
de tener todos los metodos que se utilizaran en los modelos, y estos estaran creados en el Trait **[QueryBuilderTrait](../Traits/QueryBuilderTrait.php)**.

De esta forma en los demás modelos solo deben extender de `Model` y definir las parametros 
que se utilizaran en la base de datos en la variable `$fillable`, también cuenta con la variable $table
que en caso de que no se siga los estandares para el nombrado de tablas en la base de datos, se puede definir
el nombre usado.  

El estandar es el siguiente: Si la clase se llama `User` la tabla se llamaria `users` y si la clase se llama `UserType` la tabla se llamaria `user_types`.
Ya que coge el nombre de la clase modelo que se esté usando. 
De caso contrario definir el nombre de la tabla de la base de datos en `$table`.

Los modelos creados hasta el momento son los siguientes:
- [User](User.php)
- [Ciudades](Ciudades.php)
- [Sexo](Sexo.php)
- [Persona](Persona.php)
- [PersonaTipo](PersonaTipo.php)


Para definir una relacion con el modelo, se recomienda definir en el modelo la funcion con el nombre de la tabla con la que se relaciona, y dentro de esta funcion definir la relacion, de la siguiente forma:

```php
class Persona extends Model
{
    const  PERSONA_INSERT_ERROR = 'PERSONA_INSERT_ERROR';
    const  PERSONA_INSERT_OK = 'PERSONA_INSERT_OK';
    const  GET_PERSONA_OK = 'GET_PERSONA_OK';
    const  GET_ALL_PERSONAS = 'GET_ALL_PERSONAS';
    const PERSONA_UPDATE_OK = 'PERSONA_UPDATE_OK';
    const PERSONA_DELETE_OK = 'PERSONA_DELETE_OK';

    protected $fillable = [
        'id',
        'identificacion',
        'nombres',
        'apellidos',
        'email',
        'fecha_nacimiento',
        'hora_registro',
        'tiempo_evento',
        'observaciones',
        'ciudad_id',
        'persona_tipo_id',
        'sexo_id'
    ];

    // Definicion de la relaciones
    
    // Relacion con la tabla sexo
    public function sexo(): string
    {
        return Sexo::class;
    }
    // Relacion con la tabla ciudad
    public function ciudad(): string
    {
        return Ciudades::class;
    }
    // Relacion con la tabla persona_tipo
    public function personaTipo(): string
    {
        return PersonaTipo::class;
    }

}
```

Se recomienda que a la hora de usar la relacion se use el metodo `loadRelation()` y se le pasa 
por parametro el array de relaciones que se quieren cargar.
Ejemplo:

```php
public function index(RequestManager $requestManager): void
     {
        $Personas = (new Persona())->select();
        foreach ($Personas as $Persona) {
            $Persona->loadRelation(['sexo', 'ciudad', 'personaTipo']);
        }
        $this->success($Personas,"Listado de todas las personas,", status_event: Persona::GET_ALL_PERSONAS);
     }
```
