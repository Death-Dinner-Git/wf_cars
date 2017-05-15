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

class TableController extends ManageController
{
    /**
     * @description 数据
     * @return mixed
     */
    public function indexAction()
    {
        // 临时却换当前模板的布局功能
        $this->view->engine->layout('layouts/main');

        $this->assign('meta_title', "维护列表");
        return view('table/outCarListAdd');
    }
}
