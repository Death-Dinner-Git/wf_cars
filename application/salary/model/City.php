<?php
/*
-----------------------------------
 城市模型
-----------------------------------
*/
namespace app\salary\model;
use app\common\model\Model;
use think\Validate;

class City extends Model{
    protected $_cityId;
    public function lists(){
        $lists =  City::all();
        return $lists;
    }
}