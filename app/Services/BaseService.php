<?php

namespace App\Services;

use App\Traits\JsonResponseTrait;
use App\Traits\InertiaResponseTrait;

class BaseService
{
    use JsonResponseTrait, InertiaResponseTrait;
}