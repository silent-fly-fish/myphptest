<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/7/16
 * Time: 14:13
 */
namespace App\Http\Patient;



use App\Http\ORM\RecordORM;
use App\Http\ORM\SysOptionsORM;
class RecordCtl
{
    static function AddOne($data){


        $ret=  RecordORM::addOne($data);
        if($ret){
            jsonOut('success',  $ret);
        }

        jsonOut('error',  $ret);


    }


    static function getCompletion($data){
        $patientId=$data['patient_id'];
        $ret=RecordORM::getCycleByPId( $patientId,'shengzhizhongxin');
        if(! $ret){
            jsonOut('success',  0);
        }
        $Pcycles=array_column($ret->toArray(),'cycle_id');
        $cycleList = SysOptionsORM::getAllByType('patient_cycle');
        $cycleList=$cycleList->toArray();
        $Acycle=array_column($cycleList,'id');
        $Anum=count( $Acycle);
        $new=array_diff($Acycle,$Pcycles);
        $Pnum=count( $new);
        $completion= floor(($Anum-$Pnum)/$Anum*100);
        jsonOut('success',  $completion);
    }


    static function getList($data){

        $patientId=$data['patient_id'];
        $categoryCode=$data['category_code'];

        $ret=RecordORM::getDataByPidAndCode( $patientId, $categoryCode);

        if(!$ret){

            jsonOut('success',  $ret);
        }

        $return= self::combination($ret,$categoryCode);


        jsonOut('success',  $return);




    }


    static function combination($data,$categoryCode){
        //判断是否是
        $data= $data->toArray();
        $return =[];
        if($categoryCode=='shengzhizhongxin'){
          //获取全部周期数据
          $cycleList = SysOptionsORM::getAllByType('patient_cycle');
          $cycleList=$cycleList->toArray();

          foreach ($cycleList  as $key =>$value){

              $temp=[
                'id'=> $value['id'],
                'name'=> $value['name']
              ];
              foreach ($data as $key1=>$value1){

                  if($value['id']==$value1['cycle_id']){

                      $temp['data'][]=[
                           'id'=>$value1['id'],
                           'img_urls'=>$value1['img_urls'],
                           'time'=>date('Y-m-d',$value1['created_at'])
                      ];
                  }

              }

              if(isset( $temp['data'])){

                $return[]=$temp;

              }

          }

        }else{

            foreach ($data as $key1=>$value1){


                $return[]=[
                        'id'=>$value1['id'],
                        'img_urls'=>$value1['img_urls'],
                        'time'=>date('Y-m-d',$value1['created_at'])
                ];

            }


        }


        return ['code'=>$categoryCode,'list'=>$return];



    }

}
