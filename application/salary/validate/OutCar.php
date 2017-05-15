<?php
/*
-----------------------------------
 工资结算验证器
-----------------------------------
*/

namespace app\salary\validate;
use think\Validate;

class OutCar extends Validate{

    protected $rule = [
        ['other_money','number','费用只能输入数字类型']
//        'other_money' => 'number',
//        'remark'  =>  'alphaDash'
    ];
    protected $message  =   [
        'other_money.number' => '费用只能输入数字类型',
        'remark.alphaDash' => '备注写入不合法数据'
    ];
//    protected $scene = [
//        'OutCar'   =>  ['cityId,repairType,fee,managerId'],
//    ];

}