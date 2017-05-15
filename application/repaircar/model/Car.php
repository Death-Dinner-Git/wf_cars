<?php
/*
-----------------------------------
 车辆模型
-----------------------------------
*/
namespace app\repaircar\model;
use app\common\model\Model;

class Car extends Model{

    protected $autoWriteTimestamp = 'datetime';//自动写入
    protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
    protected $resultSetType = 'collection';
    /*
     *返回所有数据
     */
     public function lists(){
         $lists = Car::all();
         return $lists;
     }
    /**
     * @获取指定城市所有的车
     * @return array
     **/
    public function cityCar($cityid){
        $cityCarList = Car::where('city_id','=',$cityid)->select();
        return $cityCarList;
    }
}