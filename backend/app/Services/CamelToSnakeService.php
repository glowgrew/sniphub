<?php

namespace App\Services;

use App\Models\Snippet;
use Hashids\Hashids;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
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