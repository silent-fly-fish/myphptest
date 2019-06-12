<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorTags extends Model
{
    protected $table = 'user_doctor_tags';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'doctor_id',
        'tag_name',
        'is_system',
        'r_status',
        'created_at'
    ];

}