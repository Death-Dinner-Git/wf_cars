<?php
/*
-----------------------------------
 抢单管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

class TakecarorderController extends BaseController{

	protected static $_currentModel;//当前操作模型
	protected $_DriverList;
	protected $_DepartmentList;
		
	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('TakeCarOrder');
		//获取司机、部门
		$this->_DriverList = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where='');
		$this->_DepartmentList = Loader::model('Department')->lists($pageNumber='',$totalNumber='',$where='');
	}

	/**
	* @打车列表
	* @return array
	**/
	public function listAction($pageNumber=1,$totalNumber=10){
		
		$where = array();
		$front = array('numberPlate'=>'','managerName'=>'','driverId'=>'','departmentid'=>'','orderstatus'=>'','ordertype'=>'','ordertype'=>'','create_time_start'=>'','create_time_end'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['numberPlate'])){
				$where['e.number_plate'] = $_GET['numberPlate'];
				$front['numberPlate'] = $_GET['numberPlate'];
			}
			if(!empty($_GET['managerName'])){
				$where['c.real_name'] = $_GET['managerName'];
				$front['managerName'] = $_GET['managerName'];
			}
			if(!empty($_GET['driverId'])){
				$where['f.id'] = $_GET['driverId'];
				$front['driverId'] = $_GET['driverId'];
			}
			if(!empty($_GET['departmentid'])){
				$where['b.id'] = $_GET['departmentid'];
				$front['departmentid'] = $_GET['departmentid'];
			}
			if(!empty($_GET['orderstatus'])){
				$where['a.order_status'] = $_GET['orderstatus'];
				$front['orderstatus'] = $_GET['orderstatus'];
			}
			if(!empty($_GET['ordertype'])){
				$where['a.order_type'] = $_GET['ordertype'];
				$front['ordertype'] = $_GET['ordertype'];
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


		$TakeCarOrderList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
		
		$this->assign('TakeCarOrderList',$TakeCarOrderList);//列表
		$this->assign('count',$count);//页码

		$this->assign('driver',$this->_DriverList);//司机
		$this->assign('department',$this->_DepartmentList);//部门
		$this->assign('front',$front);//筛选条件
		
		return $this->fetch();
	}

	/**
	*@查看行驶记录
	*@return array
	**/
	public function showrecordAction(){
		$id = Request::instance()->param('id');
		$result = self::$_currentModel->takeOrderShow($id);
		$this->assign('record',$result);
		return $this->fetch();
	}

}
