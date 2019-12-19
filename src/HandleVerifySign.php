<?php

namespace Colin\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Colin\Api\Middleware\Support\Response;

class HandleVerifySign
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('APP_SKIP_VERIFY_SIGN')) {
            return $next($request);
        }

        //检查签名
        $clientSign = $request->input('sign',false);
        if ($clientSign === false){
            return Response::json(['code' => 401, 'msg' => '非法请求']);
        }
        // 检查时间戳
        $timestamp = $request->input('timestamp');
        $now = time();
        if (!$timestamp || !is_numeric($timestamp) || $timestamp < $now - 60 * 5 || $timestamp > $now + 60 * 5) {
            return Response::json(['code' => 401, 'msg' => '请求过期']);
        }

        $data['uid'] = $request->input('uid');
        $data['version'] = $request->input('version');
        $data['clientType'] = $request->input('clientType');
        $data['network'] = $request->input('network');
        $data['timestamp'] = $timestamp;
        $data['key'] = $request->input('key', '');
        $data['uuid'] = $request->input('uuid', '');
        ksort($data);

        $requestPath = $request->path();

        // 待签名的字符串
        $stringToSign = '';
        foreach ($data as $key => $val) {
            $stringToSign .= $key . '=' . $val . '&';
        }
        $stringToSign = $stringToSign . 'action=' . $requestPath;

        $expectedSign = md5($stringToSign);
        $clientSign = $request->input('sign');
        if ($expectedSign !== $clientSign) {
            return Response::json(['code' => 401, 'msg' => '签名错误']);
        }

        return $next($request);
    }

}