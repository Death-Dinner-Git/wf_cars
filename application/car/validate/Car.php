<?php
/*
-----------------------------------
 车辆验证器
-----------------------------------
*/

namespace app\car\validate;
use think\Validate;

class Car extends Validate{

    protected $rule = [      
		'numberPlate' => 'require',
		'carType'  =>  'require',
		'cityId'   =>  'require',
		'seatNum'  =>  'require',
		'address'  =>  'require',
    ];
    protected $message  =   [
		'numberPlate.require' => '车牌号不能为空',
		'carType.require' => '车型不能为空',
		'cityId.require' => '城市不能为空',
		'seatNum.require' => '座位数不能为空',
		'address.require' => '车辆地址不能为空',
    ];
   protected $scene = [
        'car'   =>  ['numberPlate','carType','cityId','seatNum','address'],
    ];

}