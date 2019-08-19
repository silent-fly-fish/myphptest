<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/6/21
 * Time: 13:58
 */


namespace App\Listeners;

use App\Events\ExamineUserEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddIntergralListener{
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
     * @param  ExamineUserEvent  $event
     * @return void
     */
    public function handle(ExamineUserEvent $event)
    {
       $info=$event->info;
       $data['data'] = ['task_id'=>$info['task_id'],'patient_id'=>$info['patient_id']];
        \Amqp::publish('routing-key',json_encode($data), ['queue'=>'INTERGRAL_ADD_BY_TASK']);
    }
}