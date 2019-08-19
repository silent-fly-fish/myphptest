<?php
/**
 * Created by PhpStorm.
 * User: Mloong
 * Date: 2019/7/16
 * Time: 14:10
 */
namespace App\Http\Module;


use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $table = 'user_record';

    protected $guarded = [

    ];


    protected $dateFormat='U';



}