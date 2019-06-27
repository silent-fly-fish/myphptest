<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Referee extends Model
{
    protected $table = 'user_referee';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'name',
        'phone',
        'r_status',
        'created_at'
    ];

}