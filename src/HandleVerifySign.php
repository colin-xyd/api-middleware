<?php

namespace Colin\Api\Middleware;

use Cache;
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
            return Response::json(['code' => 1403, 'msg' => '非法请求']);
        }
        // 检查时间戳
        $timestamp = $request->input('timestamp');
        $now = time();

        $allow_time = (int)(app('config')->get('middleware.allow_time') / 2);
        if (!$timestamp || !is_numeric($timestamp) || $timestamp < $now - $allow_time || $timestamp > $now + $allow_time) {
            return Response::json(['code' => 1403, 'msg' => '请求过期']);
        }

        $data['uid'] = $request->input('uid');
        $data['version'] = $request->input('version');
        $data['clientType'] = $request->input('clientType');
        $data['network'] = $request->input('network');
        $data['timestamp'] = $timestamp;
        $data['key'] = $request->input('key', '');
        $data['uuid'] = $request->input('uuid', '');

        if (empty($data['uuid'])){
            return Response::json(['code' => 1403, 'msg' => '非法请求']);
        }

        if (Cache::get('api-uuid'.$data['uuid'])){
            return Response::json(['code' => 1403, 'msg' => '非法请求']);
        }


        //验证参数正确性
        if (!in_array($data['clientType'],app('config')->get('middleware.sign.key'))){
            return Response::json(['code' => 1403, 'msg' => '非法请求']);
        }

        $secret = app('config')->get('middleware.sign.secret')[$data['clientType']];

        ksort($data);

        $requestPath = $request->path();

        // 待签名的字符串
        $stringToSign = '';
        foreach ($data as $key => $val) {
            $stringToSign .= $key . '=' . $val . '&';
        }
        $stringToSign = $stringToSign . 'action=' . $requestPath;

        $expectedSign = md5(md5($stringToSign).$secret);

        $clientSign = $request->input('sign');
        if ($expectedSign !== $clientSign) {
            return Response::json(['code' => 1401, 'msg' => '签名错误']);
        }
        Cache::put('api-uuid'.$data['uuid'],1,$allow_time);//记录uuid
        return $next($request);
    }

}