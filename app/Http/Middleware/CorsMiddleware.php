<?php

/**
 * Created by PhpStorm.
 * User: Amesante_lx
 * Date: 2017/10/19
 * Time: 15:37
 */
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CorsMiddleware
{
    /**
     * 这些 URI 将免受 CSRF 验证
     *
     * @var array
     */
    private $headers;
    private $allow_origin;

    public function handle(Request $request, \Closure $next)
    {

        header('Content-Type:application/json; charset=utf-8');
//        header('Access-Control-Allow-Origin: *');
//        header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT');
//        header('Access-Control-Allow-Headers: Origin,openid,token, X-Auth-Token, Content-Type, Accept');

        if ($request->isMethod('options'))
            return new Response('OK', 200);

        $response = $next($request);

        return $response;
    }


    /**
     * @param $response
     * @return mixed
     */
    public function setCorsHeaders($response, $origin)
    {
        foreach ($this->headers as $key => $value) {
            $response->header($key, $value);
        }
        if (in_array($origin, $this->allow_origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
        } else {
            $response->header('Access-Control-Allow-Origin', '');
        }
        return $response;
    }

}