## **[Validation](./Validator.php)**  

### Descripción
El objetivo de esta parte del proyecto es validar los parametros de entrada de la API.
De esta forma se asegura que los datos que se reciben son correctos y se pueden procesar.

 Ejemplo de uso de la clase Validation:
```php
$validator = new Validator($data, [
            'name' => ['required', 'min:3', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'password_confirmation' => ['required', 'min:8'],
        ]);
        if ($validator->validate()) {
            // Los datos son válidos
        } else {
            // Los datos no son válidos
            $errors = $validator->getErrors();
            // Mostrar los errores al usuario
       }
```

### Validaciones disponibles
- `required`: El campo es obligatorio
- `min`: El campo debe tener un mínimo de caracteres
- `max`: El campo debe tener un máximo de caracteres
- `email`: El campo debe ser un email válido
- `unique`: El campo debe ser único en la base de datos
- `exists`: El campo debe existir en la base de datos
- `alpha`: El campo debe contener solo letras
- `alpha_num`: El campo debe contener solo letras y números
- `regex`: El campo debe cumplir con una expresión regular
- `date`: El campo debe ser una fecha válida
- `integer`: El campo debe ser un número entero
- `NOT NULL`: El campo no puede ser nulo
- `in_array`: El campo debe estar en un array