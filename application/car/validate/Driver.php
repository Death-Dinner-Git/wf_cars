<?php
/*
-----------------------------------
 司机验证器
-----------------------------------
*/

namespace app\car\validate;
use think\Validate;

class Driver extends Validate{

    protected $rule = [      
		'username'  =>  'require',
		'phone'  =>  'require',
		'phone'=>['regex'=>'/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/'],
		'password'=>'require|confirm',
    ];
    protected $message  =   [
		'username.require' => '司机姓名不能为空',
		'phone.require' => '手机号不能为空',
		'phone.regex' => '手机格式错误',
		'password.require' => '密码不能为空',
		'password.confirm' => '密码输入不一致',
    ];
   protected $scene = [
        'driver'   =>  ['username','phone','password'],
    ];

}