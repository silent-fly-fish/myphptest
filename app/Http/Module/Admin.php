<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'user_admin';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'username',
        'password',
        'icon',
        'email',
        'nick_name',
        'note',
        'login_time',
        'r_status',
        'salt'
    ];

}