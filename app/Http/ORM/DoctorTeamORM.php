<?php


namespace App\Http\ORM;

use App\Http\Module\Doctor;
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

    static function getListByDoctorId($doctorId) {
        $model = new DoctorTeam();
        $list = $model::query()
            ->select([
                'd.real_name'
            ])
            ->leftJoin('user_doctor as d','user_doctor_team.team_doctor_id','=','d.id')
            ->where(['user_doctor_team.doctor_id'=>$doctorId])
            ->get()
            ->toArray();

        return $list;
    }

    static function delByDoctorId($doctorId) {
        $res = DoctorTeam::query()
            ->where(['doctor_id'=>$doctorId])
            ->delete();

        return $res;
    }

    static function addOne($data) {
        $model = new DoctorTeam();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

}