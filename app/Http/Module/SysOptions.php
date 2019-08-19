<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class SysOptions extends Model
{
    protected $table = 'user_sys_options';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'type',
        'name',
        'value',
        'others',
        'created_at'
    ];
}