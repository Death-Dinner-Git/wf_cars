<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class ManagerValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'wofang_id|我房网第三方Id'  =>  ['require','unique:manager,wofang_id'],
        'department_id|部门ID'  =>  ['require','exist:department,id'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'wofang_id.require'  =>  ':attribute 不能为空',
        'wofang_id.unique'  =>  ':attribute 已存在',
        'wofang_id.exist'  =>  ':attribute 不存在',
        'department_id.require'  =>  ':attribute 不能为空',
        'department_id.exist'  =>  ':attribute 不存在',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'sync'   =>  ['wofang_id','department_id'],
        'syncUpdate'   =>  [
            'wofang_id|我房网第三方Id'  =>  'require','exist:manager,wofang_id',
            'department_id|部门ID'  =>  'require','exist:department,id'
        ],
        'create'   =>  ['wofang_id'],
        'update'  =>  ['wofang_id'],
        'save'  =>  [],
        'not'  =>  [],
    ];

}