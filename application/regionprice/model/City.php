<?php
/*
-----------------------------------
 城市模型
-----------------------------------
*/
namespace app\regionprice\model;
use app\common\model\Model;
use think\Validate;

class City extends Model{
//    protected $autoWriteTimestamp = 'datetime';//自动写入
//    protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
//    protected $resultSetType = 'collection';
    protected $_cityId;
    public function lists(){
        $lists =  City::all();
        return $lists;
    }
}