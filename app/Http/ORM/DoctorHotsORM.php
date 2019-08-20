<?php


namespace App\Http\ORM;


use App\Http\Module\DoctorHots;
use Illuminate\Support\Facades\DB;

class DoctorHotsORM extends BaseORM
{
    static function getAllByDoctorIds($doctorIds) {
        $model = new DoctorHots();
        $list = $model::query()
            ->select(DoctorHots::$fields)
            ->whereIn('doctor_id',$doctorIds)
            ->get()
            ->toArray();

        return $list;
    }

    static function updateById($data)
    {

        $doctorVisitM = DoctorHots::find($data['id']);

        if ( $doctorVisitM ){

            $data=self::isIncolumns( $doctorVisitM ,$data);
            $doctorVisitM ->fill($data);
            $ret=  $doctorVisitM ->save();
            return  $ret;
        }

        return 0;

    }

    static function addOne($data){

        $doctorVisitM = new DoctorHots();
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

    static function getOneByDoctorId($doctorId) {

        return DoctorHots::query()->where(['doctor_id'=>$doctorId])->first();
    }

}