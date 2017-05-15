<?php
/*
-----------------------------------
 司机模型
-----------------------------------
*/
namespace app\salary\model;
use app\common\model\Model;
use think\Validate;

class Driver extends Model{
    protected $_cityId;
    public function lists(){
        $lists =  Driver::all();
        return $lists;
    }
}