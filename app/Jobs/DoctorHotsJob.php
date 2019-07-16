<?php

namespace App\Jobs;

use App\Http\ORM\DoctorHotsORM;
use App\Http\ORM\DoctorORM;
use App\Http\ORM\DoctorViewORM;

class DoctorHotsJob extends Job
{
    /**
     * 创建一个新的作业实例
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 执行作业
     *
     * @return void
     */
    public function handle()
    {
        $doctorList = DoctorORM::getAllNotPage();
        if(empty($doctorList)) {
            jsonOut('success',true);
        }
        $doctorIds = array_column($doctorList,'id');
        $doctorScore = [];
        foreach ($doctorList as $k=>$v) {
            $doctorScore[$k]['doctor_id'] = $v['id'];
            //医生好评率得分
            $favorableRateScore = (floor($v['favorable_rate']))<0 ?0 : (floor($v['favorable_rate']));
            //医生上线时间得分
            $upScore = (100 - (floor((time()-$v['uptime'])/86400))*10)<0 ?0 : (100 - (floor((time()-$v['uptime'])/86400))*10);
            $doctorScore[$k]['favorable_score'] = $favorableRateScore;
            $doctorScore[$k]['online_score'] = $upScore;
            $doctorScore[$k]['total_score'] = $favorableRateScore+$upScore;
        }

        //医生浏览得分
        $doctorViews = DoctorViewORM::getAllByDoctorIds($doctorIds);
        if(!empty($doctorViews)) {
            $doctorViews = actionGetObjDataByData($doctorIds,$doctorViews,'doctor_id');
        }

        //医生人工得分
        $doctorHots = DoctorHotsORM::getAllByDoctorIds($doctorIds);

        $doctorHots = actionGetObjDataByData($doctorIds,$doctorHots,'doctor_id');

        foreach ($doctorScore as $k=>$v) {
            $viewScore = isset($doctorViews->{$v['doctor_id']}['view_numbers'])? $doctorViews->{$v['doctor_id']}['view_numbers'] : 0;
            $artificialScore = isset($doctorHots->{$v['doctor_id']}['artificial_score'])? $doctorHots->{$v['doctor_id']}['artificial_score'] : 0;
            $id = isset($doctorHots->{$v['doctor_id']}['id'])? $doctorHots->{$v['doctor_id']}['id'] : 0;
            $doctorScore[$k]['view_score'] = $viewScore;
            $doctorScore[$k]['artificial_score'] = $artificialScore;
            $doctorScore[$k]['total_score'] = $v['total_score'] + $viewScore + $artificialScore;
            $doctorScore[$k]['id'] = $id;
            if($id ==0){
                unset($doctorScore[$k]['id']);
            }
        }

        //更新或者添加热度积分
        $result = DoctorHotsORM::updateOrAddByData($doctorScore);
        if($result) {
            jsonOut('success',true);
        }
        jsonOut('success',false);

    }
}