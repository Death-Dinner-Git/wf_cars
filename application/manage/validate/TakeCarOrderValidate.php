<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class TakeCarOrderValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'out_car_id|我房网第三方Id'  =>  ['require','unique:take_car_order,out_car_id'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'out_car_id.require'  =>  ':attribute 不能为空',
        'out_car_id.unique'  =>  ':attribute 已存在',
        'out_car_id.exist'  =>  ':attribute 不存在',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'sync'   =>  ['out_car_id'],
        'syncUpdate'   =>  ['out_car_id|我房网第三方Id'  =>  'require','exist:take_car_order,out_car_id'],
        'create'   =>  ['out_car_id'],
        'update'  =>  ['out_car_id'],
        'save'  =>  [],
        'not'  =>  [],
    ];

}