<?php


namespace App\Http\ORM;

use App\Http\Module\DoctorVisit;
use Illuminate\Support\Facades\DB;

class DoctorVisitORM extends BaseORM
{
    static function getVisitsByDoctorId($doctorId) {
        $visitList = DoctorVisit::select(DoctorVisit::$fields)
            ->where(['doctor_id'=>$doctorId,'r_status'=>1])
            ->get();

        return $visitList;
    }

    static function updateById($data)
    {

        $doctorVisitM = DoctorVisit::find($data['id']);

        if ( $doctorVisitM ){

            $data=self::isIncolumns( $doctorVisitM ,$data);
            $data['visit_json']=isset($data['visit_json'])?json_encode($data['visit_json']):"";
            $doctorVisitM ->fill($data);
            $ret=  $doctorVisitM ->save();
            return  $ret;
        }

        return 0;

    }

    static function addOne($data){

        $doctorVisitM = new DoctorVisit();
        $data['visit_json']=isset($data['visit_json'])?json_encode($data['visit_json']):"";
        $data=self::isIncolumns($doctorVisitM,$data);
        $doctorVisitM->fill($data);
        $doctorVisitM->save();
        return $doctorVisitM->getKey();
    }

    static function updateOrAddByData($data)
    {
        try{
            DB::beginTransaction();
            foreach ($data as $key=>$val)
            {
                if(isset($data[$key]['id'])){
                    $ret =self::updateById($val);
                }else{
                    $ret =self::addOne($val);
                }

                if(!$ret){

                    throw new \Exception("add  error");
                }
            }

            DB::commit();

        }catch (Exception $e){

            DB::rollBack();
            return false;
        }

        return true;
    }

}