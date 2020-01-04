<?php

namespace Colin\Api\Middleware\Support;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class Response
{
    public static function json($data, $status = 200, $headers = [], $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    {
        if (isset($data['code']) && $data['code'] == 1403){
            //加入redis
            Redis::sadd('ip-blacklist',1);
        }
        return new JsonResponse($data, $status, $headers, $options);
    }
}