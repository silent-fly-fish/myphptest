<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->group(['middleware' => ['auth','before','after']], function () use ($router) {


    $router->group(['prefix'=> 'wechatusers'], function() use ($router){


        $router->get('', function(\Illuminate\Http\Request $request){
            $getData = $request->all();

            return App\Http\Doctor\WechatUserCtl::getInfoById($getData );
        });

    });


});



