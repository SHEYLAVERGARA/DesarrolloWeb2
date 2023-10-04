<?php

namespace App\Models;

use AllowDynamicProperties;
use App\Traits\QueryBuilderTrait;


#[AllowDynamicProperties] class Model
{
    use QueryBuilderTrait;
}