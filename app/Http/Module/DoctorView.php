<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorView extends Model
{
    protected $table = 'user_doctor_view';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'doctor_id',
        'view_number'
    ];
}