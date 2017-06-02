<?php
/*
-----------------------------------
 点评管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

class CommentsController extends BaseController{

	protected static $_currentModel;//当前操作模型
	protected $_DriverList;
	protected $_DepartmentList;

	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('Feedback');
		//获取司机、部门
		$this->_DriverList = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where='');
		$this->_DepartmentList = Loader::model('Department')->lists($pageNumber='',$totalNumber='',$where='');
	}

	/**
	* @评价列表
	* @return array
	**/	
	public function listAction($pageNumber=1,$totalNumber=10){
		
		$where = array();
		$front = array('departmentid'=>'','level'=>'','driverId'=>'','create_time_start'=>'','create_time_end'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['departmentid'])){
				$where['e.id'] = $_GET['departmentid'];
				$front['departmentid'] = $_GET['departmentid'];
			}
			if(!empty($_GET['level'])){
				$where['a.level'] = $_GET['level'];
				$front['level'] = $_GET['level'];
			}
			if(!empty($_GET['driverId'])){
				$where['d.id'] = $_GET['driverId'];
				$front['driverId'] = $_GET['driverId'];
			}
			if(!empty($_GET['create_time_start'])){
				$where['a.start_time'] = array('egt',$_GET['create_time_start']);
				$front['create_time_start'] = $_GET['create_time_start'];
			}
			if(!empty($_GET['create_time_end'])){
				$where['a.end_time'] = array('elt',$_GET['create_time_end']);
				$front['create_time_end'] = $_GET['create_time_end'];
			}
		}
		$CommentsList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
		
		$this->assign('CommentsList',$CommentsList);//列表
		$this->assign('count',$count);//页码
		$this->assign('empty',"<tr><td colspan='6'>暂时没有数据</td></tr>");

		$this->assign('driver',$this->_DriverList);//司机
		$this->assign('department',$this->_DepartmentList);//部门

		$this->assign('front',$front);//筛选条件

		return $this->fetch();
	}

}
