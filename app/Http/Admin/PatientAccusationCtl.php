<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/18
 * Time: 11:31
 */
namespace App\Http\Admin;

use App\Http\ORM\PatientAccusationORM;

use Illuminate\Http\Request;

class PatientAccusationCtl
{
    static function updateByID($data){
        $id = isset($data['id'])?$data['id']:0;
        $reason = isset($data['reason'])?$data['reason']:'';

        //获取评论原始信息
        $info = PatientAccusationORM::getOneInfoById($id);
        if(!$info) jsonOut('NoFoundData');

        $ret=  PatientAccusationORM::updateById(['id'=>$id,'r_status'=>2,'reason'=>$reason]);
        if($ret){
            jsonOut('success',  $ret);
        }
        jsonOut('error',  $ret);
    }


    /**
     * 举报列表
     * @param $postData
     */
    static function getAll($postData){
        $ret = PatientAccusationORM::getAll($postData);

        if($ret['total'] > 0){
            foreach ($ret['list'] as $k=>$v){
                $ret['list'][$k]['created_at'] = $v['created_at']?date('Y-m-d',$v['created_at']):'';
            }
        }
        jsonOut('success',  $ret);
    }

    /**
     * 举报详情
     * @param $id
     */
    static function getInfo($id){

        $info = PatientAccusationORM::getOneInfoById($id);

        if(!$info){
            jsonOut('NoFoundData');
        }

        switch($info['type']){
            case 1:
                $info['type_name'] = '文章评论';

                break;
            case 2:
                $info['type_name'] = '文章内容';
                break;
            case 3:
                $info['type_name'] = '帖子内容';
                break;
            case 4:
                $info['type_name'] = '帖子评论';
                break;
            case 5:
                $info['type_name'] = '医生评价';
                break;
        }
        print_r($info);exit;
    }

}