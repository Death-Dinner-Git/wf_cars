<?php

namespace app\manage\controller;

use app\common\controller\BaseController;
use think\captcha\Captcha;
use app\manage\model\User;

/**
 * @description 后台唯一不需要权限验证的控制器
 * @author Sir Fu
 */
class LoginController extends BaseController
{

    /**
     * @description Login Home Page
     * @return \think\response\View
     */
    public function indexAction()
    {
        $this->redirect('manage/login/login');
    }

    /**
     * @description back login
     * @author Sir Fu
     */
    public function loginAction()
    {
        if ($this->isGuest()) {
            $this->goHome();
        }

        $username = trim(input('WF_username'));
        $password = input('WF_password');
        $token = input('__token__');

//        // 图片验证码校验
//        if (!$this->checkVerify(input('post.WF_verify')) && 'localhost' !== request()->host() && '127.0.0.1' !== request()->host()) {
//            $this->error('验证码输入错误');
//        }

        if (request()->isPost() && $username && $password && $token ) {
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $res = $user->login();
            if (is_object($res) && (@get_class($res) == User::class)){

//                // 验证管理员表里是否有该用户
//                $account_object = new Access();
//                $where['uid']   = $user->id;
//                $account_info   = $account_object->where($where)->find();
//                if (!$account_info) {
////                    $this->error('该用户没有管理员权限' . $account_object->getError());
//                }

//                // 跳转
//                if (0 < $account_info['uid'] && $account_info['uid'] === $user->id) {
//                    $this->success('登录成功！', url('Back/index/index'));
//                } else {
//                    $this->logoutAction();
//                }

//                $this->goBack();
                $this->goHome();
            }else{
                $this->error($res, null,'',1);
            }
        }
        // 临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        return view('login',['meta_title'=>'会员登录']);
    }


    /**
     * @description Logout action.
     * @return string
     */
    public function logoutAction()
    {
        User::logout();
        $this->success('退出成功！', url('login'),1);
    }

    /**
     * @description Register Home Page
     * @return \think\response\View
     */
    public function registerAction(){
        $user = new User();
        $user->save();
        $user->username = input('WF_username');
        $user->password = input('WF_password');
        $user->password_rep = input('WF_password_rep');
        $token = input('__token__');
        if ( request()->isPost() && $user->username && $user->password && $user->password_rep && $token){
            // 调用当前模型对应的User验证器类进行数据验证
            $user->data([
                'username'=>input('WF_username'),
                'password'=>input('WF_password'),
                'password_rep'=>input('WF_password_rep'),
                '__token__'=>input('__token__'),
            ]);
            $validate = \think\Loader::validate('userValidate');
            $validate->scene('register');
            if($validate->check($user)){ //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = $user->signUp();
                if($res){
                    if (get_class($res) == User::class){
                        $this->success('注册成功','login');
                    }else{
                        $this->error($res, 'register','',1);
                    }
                }
            }else{
                $this->error($validate->getError(), 'register','',1);
            }
        }
        return view('create',['title'=>'会员注册']);
    }

    /**
     * @description 图片验证码生成，用于登录和注册
     * @param $vid
     * @author Sir Fu
     */
    public function verifyAction($vid = 1)
    {
        $verify = new Captcha();
        $verify->entry($vid);
    }

    /**
     * @description 检测验证码
     * @param  integer $code 验证码ID
     * @param $vid
     * @return boolean 检测结果
     */
    protected function checkVerify($code, $vid = 1)
    {
        $verify = new Captcha();
        return $verify->check($code, $vid);
    }
}
