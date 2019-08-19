<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class TempOverseasHospital extends Model
{
    protected $table = 'temp_overseas_hospital';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'hospital_name',
        'first_img',
        'list_img',
        'address',
        'description',
        'telephone',
        'sort'
    ];

}