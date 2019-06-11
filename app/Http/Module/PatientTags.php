<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class PatientTags extends Model
{
    protected $table = 'user_patient_tags';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'patient_id',
        'tag_id_str'
    ];
}