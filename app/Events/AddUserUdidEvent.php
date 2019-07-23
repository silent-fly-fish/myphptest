<?php


namespace App\Events;


use Illuminate\Queue\SerializesModels;

class AddUserUdidEvent extends Event
{
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