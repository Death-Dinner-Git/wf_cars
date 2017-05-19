<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class BaseUserValidate extends Validate
{

    /**
     * @var array
     */
    protected $rule = [
        'username|登录名'  =>  ['require','unique:base_user,username'],
    ];

    /**
     * @var array
     */
    protected $message = [
        'username.require'  =>  ':attribute 不能为空',
        'username.unique'  =>  ':attribute 已存在',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'sync'   =>  ['username'],
        'create'   =>  ['username'],
        'update'  =>  ['username'],
        'save'  =>  [],
        'not'  =>  [],
    ];

}