<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'user_sys_account';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'name',
        'salt',
        'password',
        'created_at',
        'updated_at'
    ];

}