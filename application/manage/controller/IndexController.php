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
        // 临时选择其他模板的布局功能
        $this->view->engine->layout('common@layouts/index');

        // 模板赋值
        $this->assign('meta_title', "CAR");

        $url = '/';
        $currentUrl = $this->getCurrentUrl();
        // 权限检测，首页不需要权限
        if ('manage/index/index' === strtolower($currentUrl)) {
            $currentUrl = 'manage/index/home';
        }
        $this->assign('url',$url.$currentUrl.'.html?iframe=true');
        return view('index');
    }

    /**
     * @description 控制面板
     * @author Sir Fu
     */
    public function homeAction()
    {
        // 模板赋值
        $this->assign('meta_title', "后台首页");
        return view('index');
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
