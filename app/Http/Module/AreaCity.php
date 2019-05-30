<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class AreaCity extends Model
{
    protected $table = 'user_area_city';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'code',
        'name',
        'p_code'
    ];

}