<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientHistory extends Model
{
    protected $table = 'user_patient_history';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'search',
        'type'
    ];
}