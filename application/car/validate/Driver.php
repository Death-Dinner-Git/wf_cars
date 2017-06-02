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
		//'phone'=>'/^1[34578]{1}[0-9]{9}$/',
		'cityid'  =>  'require',
		'drivingLicense'  =>  'require',
		'password'=>'require|confirm',
    ];
    protected $message  =   [
		'username.require' => '司机姓名不能为空',
		'phone.require' => '手机号不能为空',
		//'phone' => '手机格式错误',
	    'cityid.require' => '城市不能为空',
		'drivingLicense.require' => '驾照类型不能为空',
		'password.require' => '密码不能为空',
		'password.confirm' => '密码输入不一致',
    ];
   protected $scene = [
        'driver'   =>  ['username','phone','cityid','drivingLicense','password'],
    ];

}