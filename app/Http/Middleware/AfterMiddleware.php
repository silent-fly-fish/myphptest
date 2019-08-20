<?php
/**
 * Created by PhpStorm.
 * User: Amesante_lx
 * Date: 2017/9/14
 * Time: 11:28
 */

namespace App\Http\Middleware;
use Closure;

class AfterMiddleware
{
    public function handle($request, Closure $next){

        $response = $next($request);
        // 之后的操作

        return $response;
    }

}