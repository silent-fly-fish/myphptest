<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class AreaProvince extends Model
{
    protected $table = 'user_area_province';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'code',
        'name',
    ];

}