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
    /**
     * 举报处理
     * @param $data
     */
    static function updateByID($data){
        $id = isset($data['id'])?$data['id']:0;
        $reason = isset($data['reason'])?$data['reason']:'';
        $r_status = isset($data['r_status'])?$data['r_status']:0;

        //获取评论原始信息
        $info = PatientAccusationORM::getOneInfoById($id);
        if(!$info) jsonOut('NoFoundData');

        $ret=  PatientAccusationORM::updateById(['id'=>$id,'r_status'=>$r_status,'reason'=>$reason]);
        if($ret){
            jsonOut('success',  $ret);
        }
        jsonOut('error',  $ret);
    }

    /**
     * 举报类型列表
     * @param $postData
     */
    static function getTypeAll($postData){
        $ret = [
           [
                'id'=>1,
                'name'=>'文章'
            ],
            [
                'id'=>2,
                'name'=>'文章评论'
            ],
            [
                'id'=>3,
                'name'=>'帖子'
            ],
            [
                'id'=>4,
                'name'=>'帖子评论'
            ],
            [
                'id'=>5,
                'name'=>'医生评价'
            ]
        ];
        jsonOut('success',  $ret);
    }

    /**
     * 举报列表
     * @param $postData
     */
    static function getAll($postData){
        $ret = PatientAccusationORM::getAll($postData);

        $type = isset($postData['type'])?$postData['type']:0;
//        print_r($ret);exit;
        if($ret['total'] > 0){
            $accusationIds = array_unique(array_column($ret['list'],'accusation_id'));

            if($type == 1){
                $accusationArrJson =   GET('article.open/master',['article_ids'=>implode(',',$accusationIds)]);
            }elseif($type == 2){
                $accusationArrJson =   GET('article.open/comment',['comment_ids'=>implode(',',$accusationIds)]);
            }elseif($type == 3){
                $accusationArrJson =   GET('circle.open/master',['circle_ids'=>implode(',',$accusationIds)]);
            }elseif($type == 4){
                $accusationArrJson =   GET('circle.open/comment',['comment_ids'=>implode(',',$accusationIds)]);
            }elseif($type == 5){
                $accusationArrJson =   GET('question.open/evaluate',['evaluate_ids'=>implode(',',$accusationIds)]);
            }
            
            $accusationArrInfo = [];
            foreach ($ret['list'] as $k=>$v){
                $accusationArrInfo = isset($accusationArrJson['data'][$v['accusation_id']])?$accusationArrJson['data'][$v['accusation_id']]:'';

//                print_r($accusationArrJson['data'][$v['accusation_id']]);exit;
                $ret['list'][$k]['created_at'] = $v['created_at']?date('Y-m-d',$v['created_at']):'';
                $ret['list'][$k]['reason'] = $v['content']?$v['content']:'';
                unset($ret['list'][$k]['content']);
                if($type == 1){
                    $ret['list'][$k]['title'] = isset($accusationArrInfo['title'])?$accusationArrInfo['title']:'';
                    $ret['list'][$k]['sub_content'] = isset($accusationArrInfo['sub_content'])?$accusationArrInfo['sub_content']:'';
                    $ret['list'][$k]['img_cover'] = isset($accusationArrInfo['img_cover'])?explode(';',$accusationArrInfo['img_cover'])[0]:'';
                }elseif($type == 2){
                    $ret['list'][$k]['sub_content'] = isset($accusationArrInfo['content'])?$accusationArrInfo['content']:'';
                }elseif($type == 3){
                    $ret['list'][$k]['title'] = isset($accusationArrInfo['title'])?$accusationArrInfo['title']:'';
                    $ret['list'][$k]['content'] = isset($accusationArrInfo['content'])?$accusationArrInfo['content']:'';
                    $ret['list'][$k]['img_cover'] = isset($accusationArrInfo['img_urls'])?explode(';',$accusationArrInfo['img_urls'])[0]:'';
                }elseif($type == 4){
                    $ret['list'][$k]['sub_content'] = isset($accusationArrInfo['content'])?$accusationArrInfo['content']:'';
                }elseif($type == 5){
                    $ret['list'][$k]['sub_content'] = isset($accusationArrInfo['evaluate'])?$accusationArrInfo['evaluate']:'';
                }

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
        jsonOut('success',  $info);
    }

}