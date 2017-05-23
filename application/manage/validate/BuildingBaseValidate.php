<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class BuildingBaseValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'wofang_id|我房网第三方Id'  =>  ['require','unique:building_base,wofang_id'],
        'city_id|城市'  =>  ['require','exist:city,id'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'wofang_id.require'  =>  ':attribute 不能为空',
        'wofang_id.unique'  =>  ':attribute 已存在',
        'wofang_id.exist'  =>  ':attribute 不存在',
        'city_id.require'  =>  ':attribute 不能为空',
        'city_id.exist'  =>  ':attribute 不存在',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'sync'   =>  ['wofang_id','city_id'],
        'syncUpdate'   =>  [
            'wofang_id|我房网第三方Id'=>'require|exist:building_base,wofang_id',
            'city_id|城市'  =>  'require|exist:city,id'
        ],
        'create'   =>  ['wofang_id'],
        'update'  =>  ['wofang_id'],
        'save'  =>  [],
        'not'  =>  [],
    ];

}