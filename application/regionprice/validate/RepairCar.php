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
        'cityId' => 'require',
        'repairType'  =>  'require',
        'fee'  =>  'require|number',
        'managerId'=>'require',
//        'startTime'=>['regex'=>'/^d{4}-d{2}-d{2} d{2}:d{2}$/'],
//        'endTime'=>['regex'=>'/^d{4}-d{2}-d{2} d{2}:d{2}$/']
    ];
    protected $message  =   [
        'cityId.require' => '经办人不能为空',
        'repairType.require' => '维护类型不能为为空',
        'fee.require' => '维护费用不能为空',
        'fee.number'=>'维护费用只能填入数字',
        'managerId.require' => '经办人不能为空',
    ];
    protected $scene = [
        'repairCar'   =>  ['cityId,repairType,fee,managerId'],
    ];

}