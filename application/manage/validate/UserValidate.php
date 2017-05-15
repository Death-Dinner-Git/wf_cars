<?php

namespace app\manage\validate;

use app\common\validate\Validate;

class UserValidate extends Validate
{
    protected $password;
    protected $password_rep;

    /**
     * @var array
     */
    protected $rule = [
        '__token__|校验数据' =>  ['token'],
        'username|用户名'  =>  ['require','max'=>25,'min'=>4],
        'email|邮箱' =>  ['email'],
        'password|登录密码' =>  ['require','max'=>32,'min'=>6],
        'password_rep|确认密码' =>  ['require','comparePassword:password','max'=>32,'min'=>6 ],
    ];

    /**
     * @description 自定义验证规则
     * @access protected
     * @param mixed     $value  字段值
     * @param mixed     $rule  验证规则
     * @param array     $data  数据
     * @param string    $fieldName  字段名
     * @param string    $fieldDesc 字段描述
     * @return bool|string
     */
    protected function uniqueUsername($value, $rule, $data,$fieldName,$fieldDesc)
    {
        $rule = !empty($rule) ? $rule : $fieldName;
        if (!isset($data[$rule]) || !$value) {
            //找不到用户名，直接报验证失败
            return false;
        }

        $ret = false;

        $result = \app\manage\model\User::get()->where([$fieldName=>$value])->select();

        if ($result){
            foreach ($result as $key => $model){
                if ($model->getData('id') != $data['id']){
                    $ret = true;
                }
            }
        }

        // 是否出现重复进行验证
        if ($ret) {
            return false;
        }

        return true;
    }

    /**
     * @var array
     */
    protected $message = [
        '__token__.token'  =>  ':attribute 无效',
        'username.require'  =>  ':attribute 不能为空',
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