<?php
/*
-----------------------------------

车辆模型
-----------------------------------
*/
namespace app\salary\model;
use app\common\model\Model;
use think\Validate;

class TakeCarOrder extends Model{
    protected $autoWriteTimestamp = 'datetime';//自动写入
    protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
    protected $resultSetType = 'collection';

    public function lists(){
        $lists =  TakeCarOrder::all();
        return $lists;
    }
}