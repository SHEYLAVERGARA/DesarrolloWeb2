<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Traits\QueryBuilderTrait;


/**
 * Añadimos la anotación @AllowDynamicProperties para que el IDE no muestre errores
 * a la hora de acceder a las propiedades dinámicas
 * @AllowDynamicProperties
 * y extendemos de \stdClass para que el IDE no muestre errores a la hora de acceder a las propiedades dinámicas
 *
 */
#[AllowDynamicProperties] class Model extends \stdClass
{
    use QueryBuilderTrait;

    /**
     * toArray serializa el modelo a un array
     */
    public function toArray(): array
    {
        return array_merge($this->attributesToArray(), $this->relationsToArray());
    }

    /**
     * __toArray serializa el modelo a un array
     */
    public function __toArray(): array
    {
        return $this->toArray();
    }
}