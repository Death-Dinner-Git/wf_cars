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
use app\manage\model\User;
use app\manage\model\BaseUser;
use app\manage\model\Manager;
use app\manage\model\Department;

/**
 * 用户控制器
 * @author Sir Fu
 */
class UserController extends ManageController
{
    /**
     * @description 数据
     * @return mixed
     */
    public function indexAction()
    {
        $this->assign('meta_title', "个人信息");
        return view('user/index');
    }

    /**
     * @description 日志
     * @return string
     */
    public function logAction()
    {
        $this->assign('meta_title', "日志信息");
        return view('user/layer');
    }

    /**
     * @description 新增
     * @return string
     */
    public function resetPasswordAction()
    {
        $this->assign('meta_title', "安排出车界面");
        return view('user/list');
    }

    /**
     * @description 清单
     * @param bool $super
     * @param integer $pageNumber
     * @return string
     */
    public function listAction($super = false,$pageNumber = 1)
    {
        $where = [];
        $each = 10;
        $key = '';
        $type = '';
        if (request()->request('key')){
            $key = trim(request()->request('key'));
            $where =  array_merge($where, ['real_name'=>$key]);
        }
        $typeList = Manager::getManagerList();
        if ($super == 'true' || request()->request('type')){
            $type = $super == 'true' ? 'supperAdmin' :request()->request('type');
            if (in_array($type,array_keys($typeList))){
                $where =  array_merge($where, ['manager_type'=>$type]);
            }
        }
        $dataProvider = Manager::get()->where($where)->page($pageNumber,$each)->select();
        $count = Manager::get()->where($where)->count();
        $this->assign('meta_title', "账号管理");
        $this->assign('pages', ceil(($count)/$each));
        $this->assign('dataProvider', $dataProvider);
        $this->assign('indexOffset', (($pageNumber-1)*$each));
        $this->assign('count', $count);
        $this->assign('key', $key);
        $this->assign('type', $type);
        $this->assign('typeList', $typeList);
        $this->assign('super', $super);
        return view('user/list');
    }

    /**
     * @description 编辑
     * @param $id
     * @param $target
     * @return string
     */
    public function updateAction($id,$target = null)
    {
        $model = Manager::get($id);
        if (request()->isAjax()){
            foreach ($_REQUEST as $k=>$value){
                $_REQUEST[$k] = trim($value);
            }
            $res = ['status'=>'n','info'=>'更新失败'];
            $passed = true;
            if (isset($_REQUEST['username'])){
                if ($base = BaseUser::get(['username'=>$_REQUEST['username']])){
                    if($base->id != $model->base_user_id){
                        $res['info'] = '已存在此登录名 更新失败';
                        $passed = false;
                    }
                }else{
                    $model->getBaseUser->username = $_REQUEST['username'];
                }
            }
            if (isset($_REQUEST['realName'])){
                if (false){
                    $res['info'] = '已存在此登录名 更新失败';
                    $passed = false;
                }else{
                    if ($model->real_name != $_REQUEST['realName']){
                        $model->real_name = $_REQUEST['realName'];
                    }
                }
            }
            if (isset($_REQUEST['password']) && isset($_REQUEST['rePassword'])){
                if (!empty($_REQUEST['password']) && ($_REQUEST['password'] != $_REQUEST['rePassword'])){
                    $res['info'] = '两次密码不同';
                    $passed = false;
                }else{
                    if ($model->getBaseUser->password != md5($_REQUEST['password'])){
                        $model->getBaseUser->password =  md5($_REQUEST['password']);
                    }
                }
            }
            if(isset($_REQUEST['managerType'])){
                if (!in_array($_REQUEST['managerType'],array_keys(Manager::getManagerList()))){
                    $res['info'] = '未找到该管理类型';
                    $passed = false;
                }else{
                    if ($model->manager_type != $_REQUEST['managerType']){
                        $model->manager_type = $_REQUEST['managerType'];
                    }
                }
            }
            if (isset($_REQUEST['department'])){
                if (!Department::get($_REQUEST['department'])){
                    $res['info'] = '未找到该部门';
                    $passed = false;
                }else{
                    if ($model->department_id != $_REQUEST['department']){
                        $model->department_id = $_REQUEST['department'];
                    }
                }
            }
            if ($passed){
                $res['info'] = '更新成功';
                $res['status'] = 'y';
                if ($model->together('getBaseUser')->save()){
                    $res['info'] = '更新成功';
                    $model->update_time = date('Y-m-d H:i:s');
                    $model->save();
                }
            }
            return json($res);
        }
        $this->assign('meta_title', "更新信息");
        $this->assign('typeList', Manager::getManagerList());
        $this->assign('departmentList', Department::getAllDepartment());
        $this->assign('model', $model);
        return view('user/update');
    }

    /**
     * @description 删除
     * @param $id
     * @return string
     */
    public function deleteAction($id = 0)
    {
        return json(['code'=>1,'msg'=>'删除成功','delete_id'=>$id]);
    }
}
