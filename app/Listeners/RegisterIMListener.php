<?php


namespace App\Listeners;


use App\Events\RegisterIMEvent;

class RegisterIMListener
{
    /**
     * 创建事件监听器
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 处理事件
     *
     * @param  RegisterIMEvent  $event
     * @return void
     */
    public function handle(RegisterIMEvent $event)
    {
        $info=$event->info;
        $temp = [
            'user_id'=>$info['user_id'], //用户id
        ];
        if(isset($info['userId'])) { //用户id
            $temp['userId'] = $info['userId'];
        }
        if(isset($info['phone'])) { //用户手机号
            $temp['phone'] = $info['phone'];
        }
        if(isset($info['head_img'])) { //用户头像
            $temp['head_img'] = $info['head_img'];
        }
        if(isset($info['name'])) { //用户昵称
            $temp['name'] = $info['name'];
        }
        $data['data'] = $temp;
        \Amqp::publish('routing-key',json_encode($data), ['queue'=>'USER_REGISTER_IM']);
    }
}