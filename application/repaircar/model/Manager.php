<?php
/*
-----------------------------------

车辆模型
-----------------------------------
*/
namespace app\repaircar\model;
use app\common\model\Model;
use think\Validate;
class Manager extends Model{
    public function lists(){
        $lists =  Manager::all(['is_delete'=>1]);
        return $lists;
    }
}