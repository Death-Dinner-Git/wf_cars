<?php
/*
-----------------------------------
 车辆维护验证器
-----------------------------------
*/

namespace app\repaircar\validate;
use think\Validate;

class RepairCar extends Validate{

    protected $rule = [
        'city_id' => 'require',
        'repairType'  =>  'require',
        'fee'  =>  'require|number',
        'manager_id'=>'require',
        'start_time'=>'date',
        'end_time'=>'date'
    ];
    protected $message  =   [
        'city_id.require' => '请选择城市',
        'repairType.require' => '维护类型不能为为空',
        'fee.require' => '维护费用不能为空',
        'fee.number'=>'维护费用只能填入数字',
        'manager_id.require' => '经办人不能为空',
        'start_time.date'=>'请输入正确的日期格式',
        'end_time.date'=>'请输入正确的日期格式'
    ];
    protected $scene = [
        'repairCar'   =>  ['city_id,repairType,fee,manager_id'],
    ];
}