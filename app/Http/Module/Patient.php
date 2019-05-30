<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'user_patient';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'phone',
        'name',
        'sex',
        'head_img',
        'birth',
        'token',
        'token_time',
        'login_time',
        'intergral',
        'cash',
        'invite_code'
    ];

}