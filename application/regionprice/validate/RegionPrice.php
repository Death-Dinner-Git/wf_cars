<?php
/*
-----------------------------------
 区域结算验证器
-----------------------------------
*/

namespace app\regionprice\validate;
use think\Validate;

class RegionPrice extends Validate{

    protected $rule = [
        ['num_price','number','接单费用只能输入数字类型'],
        ['time_price','number','小时费用只能输入数字类型']
    ];
}