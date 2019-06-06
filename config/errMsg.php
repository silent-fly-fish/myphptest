<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/5/20
 * Time: 17:44
 */

return [
    'success'=>['code'=>0,'msg'=>'请求成功'],
    'error'=>['code'=>1,'msg'=>'请求失败'],
    'validationError' => ['code'=>500,'msg'=>'参数验证失败'],
    'InterfaceUrlError'=>['code'=>500,'msg'=>'接口地址未定义'],
    'InterfaceError'=>['code'=>500,'msg'=>'接口错误'],
    'phoneIsRegister'=>['code'=>3001,'msg'=>'该手机号已注册'],
    'phoneCodeError'=>['code'=>3002,'msg'=>'手机验证码错误'],
    'phoneNotRegister'=>['code'=>3003,'msg'=>'该手机号尚未注册'],
    'patientNotExist'=>['code'=>3004,'msg'=>'不存在该用户'],
    'inviteCodeNotExist'=>['code'=>3005,'msg'=>'不存在该邀请码'],
    'inviteCodeNotSelf' => ['code'=>3007,'msg'=>'邀请码不能是自己的'],
];