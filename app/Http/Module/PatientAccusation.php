<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientAccusation extends Model
{
    protected $table = 'user_patient_accusation';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'type',
        'content',
        'accusation_id',
        'r_status',
        'created_at'

    ];
}