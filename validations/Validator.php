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


    //funciones viejas.
    //        switch ($ruleName) {
//            case 'integer':
//                $response = validatorRules::case_integer($this->data[$field]);
//                if ($response != '') {
//                    $this->errors[$field][] = $response;
//                }
//                break;
//
//            case 'min':
////                if ((int) $this->data[$field] < $allowedValues[0]) {
////                    $this->errors[$field][] = "The field must be at least $allowedValues[0].";
////                }
//                $response = validatorRules::case_min($this->data[$field], $allowedValues);
//                break;
//            case 'max':
////                if ((int) $this->data[$field] > $allowedValues[0]) {
////                    $this->errors[$field][] = "The field cannot be longer than $allowedValues[0]";
////                }
//                $response = validatorRules::case_max($this->data[$field], $allowedValues);
//                break;
//
//            case 'NOT NULL':
////                if (empty($this->data[$field])) {
////                    $this->errors[$field][] = 'The field cannot be empty (NOT NULL).';
////                }
//                $response = validatorRules::case_NOT_NULL($this->data[$field]);
//                break;
//
//            case 'alpha':
////                if (!ctype_alpha($this->data[$field])) {
////                    $this->errors[$field][] = 'The field must contain only alphabetic characters.';
////                }
//                $response = validatorRules::case_alpha($this->data[$field]);
//                break;
//
//            case 'in_array':
////                $rules = $allowedValues[0];
////                // Check if the field's value is a string
////                if (is_string($rules)) {
////                    // Split the string by commas
////                    $values = explode(',', $rules);
////                    $invalidValues = [];
////                    foreach ($values as $value) {
////                        $trimmedValue = trim($value);
////                        if (!in_array(strtolower($trimmedValue), (array)strtolower($this->data[$field]))) {
////                            $invalidValues[] = $trimmedValue;
////                        }
////                    }
////                    if (!empty($invalidValues)) {
////                        $this->errors[$field][] = 'The following values in the field are not in the allowed values: ' . implode(', ', $invalidValues);
////                    }
////                } else {
////                    // If it's not a string, check if the single value is in the allowed values
////                    if (!in_array($this->data[$field], $allowedValues)) {
////                        $this->errors[$field][] = 'The field must be one of the allowed values: ' . implode(', ', $allowedValues);
////                    }
////                }
//                $response = validatorRules::case_in_array($this->data[$field], $allowedValues);
//                break;
//
//            case 'required':
////                if (empty($this->data[$field])) {
////                    $this->errors[$field][] = 'The field is required.';
////                }
//                $response = validatorRules::case_required($this->data[$field]);
//                break;
//
//            case 'email':
////                if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
////                    $this->errors[$field][] = 'Invalid email address.';
////                }
//                $response = validatorRules::case_email($this->data[$field]);
//                break;
//
//            case 'date':
////                if (!strtotime($this->data[$field])) {
////                    $this->errors[$field][] = 'Invalid date format.';
////                }
//                $response = validatorRules::case_date($this->data[$field]);
//                break;
//
//            case 'date_format':
////                $format = $allowedValues[0];
////                $date = $this->data[$field];
////                $parsedDate = date_create_from_format($format, $date);
////                if ($parsedDate === false || date_format($parsedDate, $format) !== $date) {
////                    $this->errors[$field][] = "Invalid date format. The date should be in the format $format.";
////                }
//                $response = validatorRules::case_date_format($this->data[$field], $allowedValues);
//                break;
//
//            case 'regex':
////                $pattern = $allowedValues[0];
////                if (!preg_match($pattern, $this->data[$field])) {
////                    $this->errors[$field][] = 'Invalid format.';
////                }
//                $response = validatorRules::case_regex($this->data[$field], $allowedValues);
//                break;
//
//            // Add more validation rules as needed
//
//            default:
//                break;
//        }




}
