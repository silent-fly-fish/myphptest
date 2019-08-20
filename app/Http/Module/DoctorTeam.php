<?php


namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class DoctorTeam extends Model
{
    protected $table = 'user_doctor_team';

    protected $dateFormat = 'U';

    protected $guarded = [

    ];

    static $fields = [
        'id',
        'doctor_id',
        'team_doctor_id'
    ];

}