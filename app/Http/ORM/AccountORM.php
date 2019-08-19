<?php


namespace App\Http\ORM;


use App\Http\Module\Account;

class AccountORM extends BaseORM
{
    static  function  isExistByAccountName($name){
        return Account::where([
            'name'=>$name,
            'r_status'=>1
        ])->count();
    }

    static function addOne($data){

        $AccountM = new Account();

        $data=self::isIncolumns($AccountM,$data);

        // 填充post 记录
        $AccountM->fill($data);
        $AccountM->save();
        return $AccountM->getKey();
    }


    static  function  isExistByUpdateAccountName($id,$name){
        return Account::where('id','<>',$id)->where([
            'name'=>$name,
            'r_status'=>1
        ])->count();
    }


    static function updateOne($data){
        $AccountM = Account::where([
            'id'=>$data['id']
        ])->first();

        if ($AccountM){

            $data=self::isIncolumns($AccountM,$data);
            $AccountM->fill($data);
            $ret= $AccountM->save();
            return  $ret;

        }
        return 0;
    }

    static function getInfoByName($name=''){
        $ret = Account::select('id','name','salt','password')->where(['name'=>$name,'r_status'=>1])->first();
        $ret = $ret?$ret->toArray():[];
        return  $ret;
    }
}