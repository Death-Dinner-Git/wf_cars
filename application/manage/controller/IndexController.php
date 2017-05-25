<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace app\manage\controller;

use app\manage\controller\ManageController;
use app\manage\model\Manager;
use app\manage\model\User;
use think\Loader;

/**
 * 后台默认控制器
 * @author Sir Fu
 */
class IndexController extends ManageController
{
    /**
     * 默认方法
     * @author Sir Fu
     */
    public function indexAction()
    {
        // 模板赋值
        $this->assign('meta_title', "出车系统");
        return view('index');
    }

    /**
     * @description 控制面板
     * @author Sir Fu
     */
    public function homeAction()
    {
        // 模板赋值
        $this->assign('meta_title', "控制面板");
        return view('home');
    }

    /**
     * @description 菜单列表
     * @author Sir Fu
     */
    public function navAction()
    {
        return parent::nav();
    }
}
