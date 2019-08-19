<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientSuggest extends Model
{
    protected $table = 'user_patient_suggest';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'reason',
        'img_urls'
    ];
}