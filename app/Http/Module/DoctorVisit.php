<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorVisit extends Model
{
    protected $table = 'user_doctor_visit';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'doctor_id',
        'hospital_name',
        'hospital_address',
        'visit_json',
        'description'
    ];
}