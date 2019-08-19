<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorApply extends Model
{
    protected $table = 'user_doctor_apply';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'phone',
        'name',
        'hospital',
        'desc',
        'apply_status',
        'created_at'
    ];
}