<?php
namespace Validation;


// Crea una interfaz para validar los datos
// Esta interfaz se implementa en la clase Validator
// Es un contrato que se debe cumplir.
interface ValidatorContract
{
    /**
     * Validar los datos.
     *
     * @return bool
     */
    public function validate(): bool;

    /**
     * Validar las reglas.
     *
     * @return bool
     */
    function validateRules(): bool;

    /**
     * Validar los fallos.
     *
     * @return bool
     */
    public function fails(): bool;

    /**
     * Obtener los errores.
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     * Imprimir los errores.
     *
     * @return void
     */
    public function printErrors(): void;
}
