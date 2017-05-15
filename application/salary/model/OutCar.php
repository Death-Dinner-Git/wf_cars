<?php
/*
-----------------------------------
 城市模型
-----------------------------------
*/
namespace app\salary\model;
use app\common\model\Model;
use think\Validate;

class OutCar extends Model{
    protected $_cityId;
    public function lists(){
        $lists =  OutCar::all();
        return $lists;
    }
}