<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'user_doctor';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'name',
        'sex',
        'hospital_id',
        'branch_id',
        'position_id',
        'real_name',
        'password',
        'salt',
        'telephone',
        'img',
        'good_at',
        'description',
        'token',
        'login_time',
        'token_time',
        'one_price',
        'more_price',
        'phone_price',
        'category_id_str',
        'created_at',
        'is_test'
    ];

}