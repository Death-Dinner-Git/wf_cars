<?php
/*
-----------------------------------
 城市模型
-----------------------------------
*/
namespace app\repaircar\model;
use app\common\model\Model;
use think\Validate;

class City extends Model{
    protected $_cityId;
    public function lists(){
        $lists =  City::all();
        return $lists;
    }
    /*
     * 传入城市id 返回城市名称
     */
//    public function cityName($cityId){
//        $this->getAttr('$_cityId') = $cityId;
//        $cityIdValidate = $this->cityNameValidate();
//        if(!$cityIdValidate){
//            return fasle;
//        }
//        $cityName = City::get($cityId)->value('name');
//        return $cityName;
//    }

    /*
     * 验证传入的城市id，一定是数字类型的
     */
//    public function cityIdValidate(){
//        $validate = new Validate([
//            'cityId'  => 'require|number'
//        ]);
//        $data['cityId'] =  $this->getAttr('$_cityId');
//        if (!$validate->check($data)) {
//            return false;
//        }
//        else{
//            return true;
//        }
//    }
}