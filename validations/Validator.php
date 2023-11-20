<?php
namespace Validation;
use JetBrains\PhpStorm\NoReturn;
use Response\ResponseManager;

class Validator implements ValidatorContract
{
    protected $data;
    protected mixed $options;
    protected array $errors = [];

    /**   La clase Validator se encarga de validar los datos de entrada
     *   de acuerdo a las reglas especificadas en el array $options
     *   y devuelve un array con los errores encontrados.
     *   Si no hay errores, devuelve un array vacío.
     * @package Validation
     *
     * @example $validator = new Validator($data, [
     *       'name' => ['required', 'min:3', 'max:50'],
     *       'email' => ['required', 'email'],
     *       'password' => ['required', 'min:8'],
     *       'password_confirmation' => ['required', 'min:8'],
     *   ]);
     *   if ($validator->validate()) {
     *       // Los datos son válidos
     *   } else {
     *       // Los datos no son válidos
     *       $errors = $validator->getErrors();
     *       // Mostrar los errores al usuario
     *  }
     *
     * @param $data
     * @param array $options
     * @return void
     **/
    public function __construct($data, array $options = [])
    {
        $this->data = $data;
        $this->options = $options;
    }


    /**
     * Validar los datos de entrada
     *
     * @return bool
     */
    public function validate(): bool
    {
        return $this->validateRules();
    }

    /**
     * Verificar si hay errores
     *
     * @return bool
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Obtener los errores
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validar los datos de entrada
     * @throws \Exception
     */
    function validateRules(): bool
    {
        // Perform your validation checks based on the provided data
        foreach ($this->options as $field => $rules) {
            foreach ($rules as $rule) {
                $this->validateRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Imprimir los errores
     *
     * @param string $status
     * @return void
     */
    #[NoReturn] public function printErrors(string $status = ''): void
    {
        $errors = $this->getErrors();
        $e = [];
        foreach ($errors as $field => $errorMessages) {
            $e[$field] = $errorMessages[0];
        }
        (new ResponseManager)->sendError($e);

    }

    /**
     * Validar una regla
     *
     * @param string $field
     * @param string $rule
     * @return void
     * @throws \Exception
     */
    protected function validateRule(string $field, string $rule): void
    {
        // Separamos el nombre de la regla de los valores permitidos
        $ruleParts = explode(':', $rule, 2);
        // La primera parte es el nombre de la regla a ejecutar
        $ruleName = $ruleParts[0];
        // La segunda parte son los valores permitidos
        $allowedValues = array_slice($ruleParts, 1);
        // Verificar si existe la regla
        if (validatorRules::existsCase($ruleName)){
            // llamamos a la regla de validacion
//            print_r([
//                'field' => $field,
//                'ruleName' => $ruleName,
//                'allowedValues' => $allowedValues,
//                'data' => $this->data[$field] ?? ''
//            ]);
//            echo '<br>';
            $response = validatorRules::callCase($ruleName, $this->data[$field] ?? '', $allowedValues);
            // Verificar si la respuesta no es vacia, cargamos el error
            $this->loadErrors($field, $response);
        }
    }

    /**
     * Cargar los errores
     * Verificar si la respuesta no es vacia, cargamos el error
     *
     * @param string $field
     * @param string $response
     * @return void
     */
    protected function loadErrors(string $field, string $response): void
    {
        if ($response != '')
            $this->errors[$field][] = $response;
    }

}
