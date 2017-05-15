<?php
/*
-----------------------------------
 维护车辆模型
-----------------------------------
*/
namespace app\repaircar\model;
use app\common\model\Model;
use think\Loader;
class RepairCar extends Model{
    /*
     * 车辆维护信息
     */
    public function lists(){
        $lists =  RepairCar::all(['is_delete'=>1]);
        return $lists;
    }

    /*
     * 车辆维护操作验证器
     */
    public function repairCarValidate($checkData){
        $validate = Loader::validate('RepairCar');
        if(!$validate->scene('RepairCar')->check($checkData)){
            return $validate->getError();
        }
    }

    /**
     * 添加车辆维护信息
     * return array
     **/
    public function add($repairCarData){
        //写入的数据
        $data = array (
            'city_id'=>$repairCarData['cityId'],
            'repairType'=>$repairCarData['repairType'],
            'fee'=>$repairCarData['fee'],
            'shop_name'=>$repairCarData['shopName'],
            'project'=>$repairCarData['project'],
            'manager_id'=>$repairCarData['managerId'],
            'reason'=>$repairCarData['reason'],
            'start_time'=>$repairCarData['startTime'],
            'end_time'=>$repairCarData['endTime']

        );
        $driver = self::create($data);
        return $driver->id;
    }
    /**
     * @根据城市id获取城市名称
     * @return array
     **/
    public  function turnCityName($cityid){
        $CityData = Loader::model('City')->where('id',$cityid)->value('name');
        return $CityData;
    }
}