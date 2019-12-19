<?php

namespace Colin\Api\Middleware\Support;


use Illuminate\Http\JsonResponse;

class Response
{
    public static function json($data, $status = 200, $headers = [], $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    {
        return new JsonResponse($data, $status, $headers, $options);
    }
}