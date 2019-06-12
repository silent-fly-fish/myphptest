<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientAttention extends Model
{
    protected $table = 'user_patient_attention';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'doctor_id'
    ];
}