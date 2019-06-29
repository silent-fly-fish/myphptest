<?php


namespace App\Http\Admin;


use App\Http\Module\DoctorTeam;
use App\Http\ORM\DoctorORM;
use App\Http\ORM\DoctorTeamORM;
use Illuminate\Support\Facades\DB;

class DoctorTeamCtl
{
    /**
     * 团队医生列表
     * @param $data
     */
    static function getDoctorTeamList($data) {
        $doctorList = DoctorORM::getAllByTeam($data);

        foreach ($doctorList['list'] as $k=>$v) {
            $team = DoctorTeamORM::getListByDoctorId($v['id']);
            if(!empty($team)) {
                $team = array_column($team,'real_name');
            }
            $doctorList['list'][$k]['team'] = $team;
        }

        jsonOut('success',$doctorList);
    }

    /**
     * 分配团队医生
     * @param $doctorId
     * @param $doctorTeamIds
     */
    static function assignDoctorTeam($doctorId,$doctorTeamIds) {

        DB::beginTransaction();
        try{
            $info['doctor_id'] = $doctorId;

            DoctorTeamORM::delByDoctorId($doctorId);

            foreach ($doctorTeamIds as $k=>$v){
                $info['team_doctor_id'] = $v;
                DoctorTeamORM::addOne($info);
            }
            DB::commit();
            jsonOut('success',true);
        }catch (\Exception $e){
            DB::rollBack();
            jsonOut('success',false);
        }
    }

    /**
     * 医生团队分配列表
     * @param $doctorId
     */
    static function getTeamList($doctorId) {
       $list = DoctorTeamORM::getListByDoctorId($doctorId);

       jsonOut('success',$list);
    }
}