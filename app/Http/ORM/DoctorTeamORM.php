<?php


namespace App\Http\ORM;

use App\Http\Module\DoctorTeam;
class DoctorTeamORM extends BaseORM
{
    static function getAllByDoctorId($doctorId) {
        $model = new DoctorTeam();
        $doctorTeam = $model
            ->select([
            'd.id as doctor_id',
            'd.real_name',
            'd.img',
            'b.name as branch_name'
            ])
            ->leftJoin('user_doctor as d','d.id','=','user_doctor_team.team_doctor_id')
            ->leftJoin('user_sys_options as b','b.id','=','d.branch_id')
            ->where(['user_doctor_team.doctor_id'=>$doctorId])
            ->get();

        return $doctorTeam;
    }

}