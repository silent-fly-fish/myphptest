<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorHots extends Model
{
    protected $table = 'user_doctor_hots';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'doctor_id',
        'view_score',
        'favorable_score',
        'online_score',
        'artificial_score',
        'total_score'
    ];
}