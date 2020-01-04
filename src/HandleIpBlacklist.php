<?php

namespace Colin\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Colin\Api\Middleware\Support\Response;

class HandleIpBlacklist
{
    public function handle(Request $request, Closure $next)
    {
        //查询redis
        $result = true;
        if ($result){
            return Response::json(['code' => 1404, 'msg' => '']);
        }
        return $next($request);
    }
}