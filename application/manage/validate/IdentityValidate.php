<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class IdentityValidate extends Validate
{
    protected $password;
    protected $password_rep;

    /**
     * @var array
     */
    protected $rule = [
        '__token__|校验数据' =>  ['token'],
        'username|用户名'  =>  ['require','max'=>25,'min'=>1],
        'email|邮箱' =>  ['email'],
        'password|登录密码' =>  ['require','max'=>32,'min'=>6],
        'password_rep|确认密码' =>  ['require','max'=>32,'min'=>6, 'confirm:password'],
    ];

    /**
     * @var array
     */
    protected $message = [
        '__token__.token'  =>  ':attribute 无效',
        'username.require'  =>  ':attribute 不能为空',
        'username.exist'  =>  ':attribute 不存在',
        'username.usernameExist'  =>  ':attribute 不存在',
        'password.require'  =>  ':attribute 不能为空',
        'password_rep.require'  =>  ':attribute 不能为空',
        'password_rep.comparePassword'  =>  '两次密码 不一致',
        'email' =>  ':attribute 不合法',
    ];

    /**
     * @var array
     */
    protected $scene = [
        'create'   =>  ['username','password'],
        'update'  =>  ['email'],
        'save'  =>  [],
        'loginAjax'   =>  ['username'=> 'require|usernameExist:base_user,username'],
        'login'  =>  ['username','password','__token__'],
        'signUp'  =>  ['username','password'],
        'register'  =>  ['username','password','password_rep','__token__'],
    ];

    /**
     * @description 自定义验证规则
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @param string    $param  字段
     * @return bool|string
     */
    protected function usernameExist($value, $rule, $data,$param)
    {
        $ret = !$this->unique($value, $rule, $data, $param);
        return $ret;
    }

    /**
     * @description 自定义验证规则
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @return bool|string
     */
    protected function comparePassword($value, $rule, $data)
    {
        $rule = !empty($rule) ? $rule : 'password';
        if (!isset($data[$rule]) || empty($value)) {
            // 两次密码 不一致
            return false;
        }

        // 密码对比
        if (isset($data[$rule]) && $value === $data[$rule]) {
            return true;
        }
        return false;
    }

}