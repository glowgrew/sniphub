<?php

namespace App\Services;

use Illuminate\Support\Str;

class CamelToSnakeService
{
    public function camelToSnake($data)
    {
        $snakeCaseData = array_map(function ($key) {
            return Str::snake($key);
        }, array_keys($data));
        return array_combine($snakeCaseData, array_values($data));
    }
}