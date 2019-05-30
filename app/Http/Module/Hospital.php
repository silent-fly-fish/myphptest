<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $table = 'user_hospital';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'name',
        'level',
        'province_id',
        'city_id',
        'area_id',
        'address',
        'logo',
        'description',
        'public_hospital',
        'created_at'
    ];

}