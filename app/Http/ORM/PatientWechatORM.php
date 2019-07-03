<?php


namespace App\Http\ORM;


use App\Http\Module\PatientWechat;

class PatientWechatORM extends BaseORM
{
    static function getOneByUnionid($unionid) {
        $info = PatientWechat::query()
            ->select(PatientWechat::$fields)
            ->where(['unionid'=>$unionid])
            ->first();

        return $info;
    }

    static function addOne($data) {
        $model = new PatientWechat();
        $data = self::isIncolumns($model, $data); //过滤添加参数

        $model->fill($data);
        $model->save();

        return $model->getKey();
    }

    static function update($data) {
        $model = new PatientWechat();
        $unionid = $data['unionid'];
        $data = self::isIncolumns($model, $data);

        return $model::whereRaw('unionid='.$unionid)->update($data);
    }

}