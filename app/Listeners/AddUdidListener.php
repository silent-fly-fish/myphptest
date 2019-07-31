<?php


namespace App\Listeners;


use App\Events\AddUserUdidEvent;

class AddUdidListener
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
     * @param  AddUserUdidEvent  $event
     * @return void
     */
    public function handle(AddUserUdidEvent $event)
    {
        $info=$event->info;
        $data = [
            'registerId'=>$info['registerId'], //设备号id
            'platform'=>$info['platform'] //平台
        ];
        if(isset($info['userId'])) { //用户id
            $data['userId'] = $info['userId'];
        }
        if(isset($info['roleType'])) { //角色id
            $data['roleType'] = $info['roleType'];
        }

        \Amqp::publish('routing-key',json_encode($data), ['queue'=>'MSG_JPUSH_DEVICE_SAVE']);
    }

}