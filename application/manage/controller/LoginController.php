<?php

namespace app\manage\controller;

use app\common\controller\BaseController;
use app\manage\model\BuildingBase;
use app\manage\model\City;
use think\captcha\Captcha;
use app\manage\model\Identity;

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
        $model = \app\manage\model\BuildingBase::load();
        $data = [
            'wofang_id'=>9483,
            'name'=>'海松大厦/金谷二号',
            'address'=>'福田区深南大道车公庙地铁口泰然九路',
            'lng'=>'0',
            'lat'=>'0',
            'create_time'=>'2017:05:19 19:10:15',
            'update_time'=>'2017:05:19 19:10:15',
            'city_id'=>NULL,
        ];
        var_dump($model->synchroBuildingBase($data));
        exit();
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
            $identity = new Identity();
            $identity->username = 'myp';
//            $identity->username = $username;
            $identity->password = $password;
            $res = $identity->login();
            var_dump($res);
            exit();
            if (is_object($res) && (@get_class($res) == Identity::class)){

//                // 验证管理员表里是否有该用户
//                $account_object = new Access();
//                $where['uid']   = $identity->id;
//                $account_info   = $account_object->where($where)->find();
//                if (!$account_info) {
////                    $this->error('该用户没有管理员权限' . $account_object->getError());
//                }

//                // 跳转
//                if (0 < $account_info['uid'] && $account_info['uid'] === $identity->id) {
//                    $this->success('登录成功！', url('Back/index/index'));
//                } else {
//                    $this->logoutAction();
//                }

                var_dump($res);
                exit();
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
        Identity::logout();
        $this->success('退出成功！', url('login'),1);
    }

    /**
     * @description Register Home Page
     * @return \think\response\View
     */
    public function registerAction(){
        $identity = new Identity();
        $identity->save();
        $identity->username = input('WF_username');
        $identity->password = input('WF_password');
        $identity->password_rep = input('WF_password_rep');
        $token = input('__token__');
        if ( request()->isPost() && $identity->username && $identity->password && $identity->password_rep && $token){
            // 调用当前模型对应的Identity验证器类进行数据验证
            $identity->data([
                'username'=>input('WF_username'),
                'password'=>input('WF_password'),
                'password_rep'=>input('WF_password_rep'),
                '__token__'=>input('__token__'),
            ]);
            $validate = \think\Loader::validate('userValidate');
            $validate->scene('register');
            if($validate->check($identity)){ //注意，在模型数据操作的情况下，验证字段的方式，直接传入对象即可验证
                $res = $identity->signUp();
                if($res){
                    if (get_class($res) == Identity::class){
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
