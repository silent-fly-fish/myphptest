<?php


namespace App\Http\Patient;


use App\Http\ORM\DoctorORM;
use App\Http\ORM\TempOverseasHospitalORM;

class TempCtl
{
    /**
     * 获取海外医院详情
     * @param $id
     */
    static function getHospitalInfo($id) {
        $hospitalInfo = TempOverseasHospitalORM::getOneById($id);

        $hospitalInfo['list_img'] = explode(',',$hospitalInfo['list_img']);


        jsonOut('success',$hospitalInfo);
    }

    /**
     * 获取海外医院列表
     */
    static function getHospitalList() {
        $hospitalList = TempOverseasHospitalORM::getAll();

        jsonOut('success',$hospitalList);
    }

    /**
     * 获取医生列表
     * @param $ids
     */
    static function getDoctorList($ids) {
        $doctorList = DoctorORM::getInfoByIds($ids);

        jsonOut('success',$doctorList);
    }

    static function getIllnessInfo() {
        $info = [
            'illness_tag' => '多囊卵巢',
            'description' => '囊卵巢综合征（PCOS）是生育年龄妇女常见的一种复杂的内分泌及代谢异常所致的疾病，以慢性无排卵（排卵功能紊乱或丧失）和高雄激素血症（妇女体内男性激素产生过剩）为特征，主要临床表现为月经周期不规律、不孕、多毛和/或痤疮，是最常见的女性内分泌疾病。',
            'list_img' => [
                'http://futurefertile.oss-cn-hangzhou.aliyuncs.com/app/15657498036604.jpg'
            ]
        ];

        jsonOut('success',$info);
    }

}