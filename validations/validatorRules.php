<?php

namespace Validation;

class validatorRules
{

    /**
     * Cases tiene la lista de reglas de validación disponibles.
     * Cada regla de validación es una función que recibe el valor del campo y devuelve un string con el mensaje de error.
     * Si no hay error, devuelve un string vacío.
     *
     *
     */
    public const cases = [
        'integer' => self::class . '::case_integer',
        'min' => self::class . '::case_min',
        'max' => self::class . '::case_max',
        'NOT NULL' => self::class . '::case_NOT_NULL',
        'alpha' => self::class . '::case_alpha',
        'in_array' => self::class . '::case_in_array',
        'required' => self::class . '::case_required',
        'email' => self::class . '::case_email',
        'date' => self::class . '::case_date',
        'date_format' => self::class . '::case_date_format',
        'lowercase' => self::class . '::case_lowercase',
        'alphanum' => self::class . '::case_alphanum',
        'regex' => self::class . '::case_regex',
        'nullable' => self::class . '::case_nullable',
    ];

    /**
     * CasesMessage tiene la lista de mensajes de error para cada regla de validación.
     * Cada mensaje de error puede contener placeholders para los valores permitidos.
     * Los placeholders se reemplazan por los valores permitidos en el orden en que aparecen en el array.
     * Si no hay placeholders, el array puede contener un único string.
     * Si hay placeholders, el array debe contener dos strings.
     */
    const casesMessage = [
        'integer' => 'The field must be an integer.',
        'min' => 'The field must be at least %s.',
        'max' => 'The field cannot be longer than %s.',
        'NOT NULL' => 'The field cannot be empty (NOT NULL).',
        'alpha' => 'The field must contain only alphabetic characters.',
        'in_array' => [
            'The following values in the field are not in the allowed values:  %s.',
            'The field must be one of the allowed values: %s.',
            ],
        'required' => 'The field is required.',
        'email' => 'The field must be a valid email address.',
        'date' => 'The field must be a valid date.',
        'date_format' => 'The field must be a valid date in the format %s.',
        'lowercase' => 'The field must be lowercase.',
        'alphanum' => 'The field must contain only alphanumeric characters.',
        'regex' => 'The field must match the regular expression: %s.',
        'nullable' => 'The field can be empty (NULL).',
    ];

    /**
     * callCase recibe el nombre de una regla de validación, el valor del campo y los valores permitidos.
     * Devuelve un string con el mensaje de error.
     * Si no hay error, devuelve un string vacío.
     *
     * @param string $case
     * @param mixed $value
     * @param array $allowedValues
     * @return string
     */
    public static function callCase(string $case, mixed $value, array $allowedValues): string
    {
        return self::cases[$case]($value, $allowedValues);
    }

    /**
     * existsCase recibe el nombre de una regla de validación.
     * Devuelve true si la regla de validación existe, false si no existe.
     *
     * @param string $case
     */
    public static function existsCase(string $case): bool
    {
        return isset(self::cases[$case]);
    }


    /**
     * case_integer valida que el valor del campo sea un número entero.
     * Devuelve un string con el mensaje de error.
     *
     * @param $value
     * @return string
     */
    public static function case_integer(string $value): string
    {
        if (!is_numeric($value) || !is_int($value + 0)) {
            return self::casesMessage['integer'];
        }
        return '';
    }

    /**
     * case_min valida que el valor del campo sea mayor o igual que el valor permitido.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @param array $allowedValues
     * @return string
     */
    public static function case_min(string $value, array $allowedValues): string
    {
        if ((int) $value < $allowedValues[0]) {
            return sprintf(self::casesMessage['min'], $allowedValues[0]);
        }
        return '';
    }

    /**
     * case_max valida que el valor del campo sea menor o igual que el valor permitido.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @param array $allowedValues
     * @return string
     */
    public static function case_max(string $value, array $allowedValues): string
    {
        if ((int) $value > $allowedValues[0]) {
            return sprintf(self::casesMessage['max'], $allowedValues[0]);
        }
        return '';
    }

    /**
     * case_NOT_NULL valida que el valor del campo no sea vacío.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_NOT_NULL(string $value): string
    {
        if (empty($value)) {
            return self::casesMessage['NOT NULL'];
        }
        return '';
    }

    /**
     * case_alpha válida que el valor del campo contenga sólo caracteres alfabéticos.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_alpha(string $value): string
    {
        if (!ctype_alpha($value)) {
            return self::casesMessage['alpha'];
        }
        return '';
    }

    /**
     * case_in_array valida que el valor del campo esté entre los valores permitidos.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @param array $allowedValues
     * @return string
     */
    public static function case_in_array(string $value, array $allowedValues): string
    {
        $rules = $allowedValues[0];
        // Check if the field's value is a string
        if (is_string($rules)) {
            // Split the string by commas
            $values = explode(',', $rules);
            $invalidValues = [];
            foreach ($values as $value) {
                $trimmedValue = trim($value);
                if (!in_array(strtolower($trimmedValue), (array)strtolower($value))) {
                    $invalidValues[] = $trimmedValue;
                }
            }
            if (!empty($invalidValues)) {
                return sprintf(self::casesMessage['in_array'][0], implode(', ', $invalidValues));
            }
        } else {
            // If it's not a string, check if the single value is in the allowed values
            if (!in_array($value, $allowedValues)) {
                return sprintf(self::casesMessage['in_array'][1], implode(', ', $allowedValues));
            }
        }
        return '';
    }

    /**
     * case_required valida que el valor del campo no sea vacío.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_required(string $value): string
    {
        if (empty($value)) {
            return self::casesMessage['required'];
        }
        return '';
    }

    /**
     * case_email valida que el valor del campo sea una dirección de email válida.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_email(string $value): string
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return self::casesMessage['email'];
        }
        return '';
    }

    /**
     * case_date valida que el valor del campo sea una fecha válida.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_date(string $value): string
    {
        $date = date_parse($value);
        if (!checkdate($date['month'], $date['day'], $date['year'])) {
            return self::casesMessage['date'];
        }
        return '';
    }

    /**
     * case_date_format valida que el valor del campo sea una fecha válida en el formato indicado.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @param array $allowedValues
     * @return string
     */
    public static function case_date_format(string $value, array $allowedValues): string
    {
        $format = $allowedValues[0];
        $date = $value;
        $d = \DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) == $date)) {
            return sprintf(self::casesMessage['date_format'], $format);
        }
        return '';
    }

    /**
     * case_lowercase valida que el valor del campo esté en minúsculas.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_lowercase(string $value): string
    {
        if (strtolower($value) !== $value) {
            return self::casesMessage['lowercase'];
        }
        return '';
    }

    /**
     * case_alphanum valida que el valor del campo contenga sólo caracteres alfanuméricos.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_alphanum(string $value): string
    {
        if (!ctype_alnum($value)) {
            return self::casesMessage['alphanum'];
        }
        return '';
    }

    /**
     * case_regex valida que el valor del campo coincida con la expresión regular indicada.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @param array $allowedValues
     * @return string
     */
    public static function case_regex(string $value, array $allowedValues): string
    {
        $pattern = $allowedValues[0];
        if (!preg_match($pattern, $value)) {
            return sprintf(self::casesMessage['regex'], $pattern);
        }
        return '';
    }

    /**
     * case_nullable valida que el valor del campo sea vacío.
     * Devuelve un string con el mensaje de error.
     *
     * @param string $value
     * @return string
     */
    public static function case_nullable(string $value): string
    {
        // TODO: Implement case_nullable() method.
        return '';
    }
}