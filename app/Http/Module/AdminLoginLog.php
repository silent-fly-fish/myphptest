<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class AdminLoginLog extends Model
{
    protected $table = 'user_admin_login_log';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'admin_id',
        'ip',
        'address',
        'created_at'
    ];

}