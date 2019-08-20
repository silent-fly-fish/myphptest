<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/6/21
 * Time: 13:52
 */
namespace App\Events;


use Illuminate\Queue\SerializesModels;


class ExamineUserEvent extends Event {
    use SerializesModels;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $info;
    public function __construct($info)
    {
        $this->info=$info;
    }
}