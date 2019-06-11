<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientWechat extends Model
{
    protected $table = 'user_patient_wechat';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'open_id',
        'nickname',
        'head_img',
        'sex',
        'province',
        'city',
        'area'
    ];
}