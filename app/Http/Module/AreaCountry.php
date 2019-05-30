<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class AreaCountry extends Model
{
    protected $table = 'user_area_country';

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