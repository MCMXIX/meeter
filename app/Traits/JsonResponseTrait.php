<?php

namespace App\Traits;

trait JsonResponseTrait
{
    /**
     * Returns data as json response
     * 
     * @param Array $data
     * @param Integer $status
     * @param Array<int, string> $header
     * @return Illuminate\Http\JsonResponse
     */
    public function response($data = [], $status = 200, $header = [])
    {
        return response()->json($data, $status, $header, JSON_PRETTY_PRINT);
    }
}