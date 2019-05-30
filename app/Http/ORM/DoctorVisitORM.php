<?php


namespace App\Http\ORM;

use App\Http\Module\DoctorVisit;
class DoctorVisitORM extends BaseORM
{
    static function getVisitsByDoctorId($doctorId) {
        $visitList = DoctorVisit::select(DoctorVisit::$fields)
            ->where(['doctor_id'=>$doctorId,'r_status'=>1])
            ->get();

        return $visitList;
    }

}