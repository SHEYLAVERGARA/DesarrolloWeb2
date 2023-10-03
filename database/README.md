## **[Mysql connection](./MysqlConnection.php)**
Esta clase permite realizar la conexión a la base de datos, 
para ello se debe instanciar la clase y llamar al método **getInstance()**. 
También se debe llamar al método **mountDatabase()** para montar la base de datos,
crear las tablas y poblarlas con datos de prueba.
```php
$instance = \Database\MysqlConnection::getInstance();
$instance->mountDatabase();
```

Para el archivo **[Base de datos](./createDatabase.sql)** se debe crear una base de datos.

### Estructura de la Base de Datos

**Taller 1 - Tabla "users"**

- `identificacion` (Clave Primaria): Un número de identificación único para cada usuario.
- `nombres`: Los nombres del usuario.
- `apellidos`: Los apellidos del usuario.
- `fecha_nacimiento`: La fecha de nacimiento del usuario.
- `sexo`: El sexo del usuario (MASCULINO o FEMENINO).
- `email`: La dirección de correo electrónico del usuario.
- `hora_registro`: La hora en la que se registró el usuario.
- `tiempo_accion`: La fecha y hora de alguna acción realizada por el usuario.
- `observaciones`: Observaciones adicionales sobre el usuario.

**Taller 2 - Tablas "ciudades", "persona_tipos", "sexos", y "personas"**

- `ciudades`:
    - `id` (Clave Primaria): Un identificador único para cada ciudad.
    - `nombre`: El nombre de la ciudad.
    - `codigo`: Un código identificador para la ciudad.

- `persona_tipos`:
    - `id` (Clave Primaria): Un identificador único para cada tipo de persona.
    - `nombre`: El nombre del tipo de persona.

- `sexos`:
    - `id` (Clave Primaria): Un identificador único para cada sexo.
    - `nombre`: El nombre del sexo.

- `personas`:
    - `id` (Clave Primaria): Un identificador único para cada persona.
    - `ciudad_id` (Clave Foránea): Se relaciona con la tabla `ciudades` mediante `id`.
    - `persona_tipo_id` (Clave Foránea): Se relaciona con la tabla `persona_tipos` mediante `id`.
    - `sexo_id` (Clave Foránea): Se relaciona con la tabla `sexos` mediante `id`.
    - `identificacion`: Un número de identificación único para cada persona.
    - `nombres`: Los nombres de la persona.
    - `apellidos`: Los apellidos de la persona.
    - `email`: La dirección de correo electrónico de la persona.
    - `fecha_nacimiento`: La fecha de nacimiento de la persona.
    - `hora_registro`: La hora en la que se registró la persona.
    - `tiempo_evento`: La fecha y hora de algún evento relacionado con la persona.
    - `observaciones`: Observaciones adicionales sobre la persona.

### Relaciones

- La tabla `personas` tiene relaciones con las tablas `ciudades`, `persona_tipos`, y `sexos` a través de las claves foráneas `ciudad_id`, `persona_tipo_id`, y `sexo_id`, respectivamente.

- Las claves foráneas aseguran que los datos en la tabla `personas` estén relacionados con los registros correspondientes en las tablas `ciudades`, `persona_tipos`, y `sexos`.


```
+----------------------+      +------------------+      +-----------------------+      
|        users         |      |     ciudades     |      |     persona_tipos     |      
+----------------------+      +------------------+      +-----------------------+      
| identificacion (PK)  |      |      id (PK)     |      |        id (PK)        |      
| nombres              |      |      nombre      |      |        nombre         |   
| apellidos            |      |      codigo      |      +-----------------------+      
| fecha_nacimiento     |      +------------------+            
| sexo                 |                    
| email                |                                    
| hora_registro        |                                    
+----------------------+ 
+----------------------+      +-------------------+     +---------------------+
|      personas        |      |       sexos       |     |        sexos        |
+----------------------+      +-------------------+     +---------------------+
|        id (PK)       |      |      id (PK)      |     |        id (PK)      |
|  ciudad_id           |      |      nombre       |     |        nombre       |
|  persona_tipo_id     |      +-------------------+     +---------------------+ 
|  sexo_id             |                        
|  identificacion      |                        
|  nombres             |                        
|  apellidos           |                        
|  email               |                        
|  fecha_nacimiento    |                        
|  hora_registro       |                        
|  tiempo_evento       |                        
|  observaciones       |                        
+----------------------+
```

#### Taller 1

```mysql
CREATE TABLE IF NOT EXISTS users (
    identificacion INT NOT NULL,
    nombres VARCHAR(30) NOT NULL,
    apellidos VARCHAR(30),
    fecha_nacimiento DATE NOT NULL,
    sexo ENUM('MASCULINO', 'FEMENINO') NOT NULL,
    email VARCHAR(100),
    hora_registro TIME NOT NULL,
    tiempo_accion DATETIME NOT NULL,
    observaciones VARCHAR(255),
    PRIMARY KEY (identificacion)
);
```
#### Taller 2
```mysql
CREATE TABLE IF NOT EXISTS ciudades (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    codigo VARCHAR(45) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS persona_tipos (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS sexos (
    id INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS personas (
    id INT NOT NULL AUTO_INCREMENT,
    ciudad_id INT NOT NULL,
    persona_tipo_id INT NOT NULL,
    sexo_id INT NOT NULL,
    identificacion VARCHAR(10) NOT NULL,
    nombres VARCHAR(45) NOT NULL,
    apellidos VARCHAR(45) NOT NULL,
    email VARCHAR(45) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    hora_registro TIME NOT NULL,
    tiempo_evento DATETIME NOT NULL,
    observaciones VARCHAR(255),
    PRIMARY KEY (id),
    CONSTRAINT fk_personas_ciudades
        FOREIGN KEY (ciudad_id)
        REFERENCES ciudades (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT fk_personas_persona_tipos
        FOREIGN KEY (persona_tipo_id)
        REFERENCES persona_tipos (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT fk_personas_sexos
        FOREIGN KEY (sexo_id)
        REFERENCES sexos (id)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
);
```
