<?php
/*
-----------------------------------
  司机管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

class DriverController extends BaseController{


	protected static $_currentModel;//当前操作模型
	protected $_CityList;

	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('Driver');
		//获取城市
		$this->_CityList = Loader::model('City')->lists();
	}
	
	/**
	* @司机列表
	* @return array
	**/
	public function ListAction($pageNumber=1,$totalNumber=10){

		$where = array();
		$front = array('real_name'=>'','create_time_start'=>'','create_time_end'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['username'])){
				$where['real_name'] = $_GET['username'];
				$front['real_name'] = $_GET['username'];
			}
			if(!empty($_GET['create_time_start'])){
				$where['create_time'] = array('egt',$_GET['create_time_start']);
				$front['create_time_start'] = $_GET['create_time_start'];
			}
			if(!empty($_GET['create_time_end'])){
				$where['create_time'] = array('elt',$_GET['create_time_end']);
				$front['create_time_end'] = $_GET['create_time_end'];
			}
			if(!empty($_GET['create_time_start']) && !empty($_GET['create_time_end'])){
				$where['create_time'] = array(array('egt',$_GET['create_time_start']),array('elt',$_GET['create_time_end']),'and');
			}
		}
		$where['is_delete'] = ['eq',1];
		$driverList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
		foreach($driverList as $key=>$value){
			$value['name'] = self::$_currentModel->turnCityName($value['city_id']);
		}
		$this->assign('front',$front);
		$this->assign('driverList',$driverList);
		$this->assign('count',$count);
		return $this->fetch();
	}

	/**
	* @添加司机
	* @return array
	**/
	public function addAction(){

		$this->assign('city',$this->_CityList);//城市

		if(Request::instance()->isAjax()){
			$ValidateError = self::$_currentModel->DriverValidate($_POST);
			if(!empty($ValidateError)){
				return json(['data'=>NULL,'code'=>404,'message'=>$ValidateError]);
			}
			if(self::$_currentModel->add($_POST)){
				return json(['data'=>url('list'),'code'=>200,'message'=>'添加成功']);
			}else{
				return json(['data'=>NULL,'code'=>404,'message'=>'添加失败']);
			}
		}

		return $this->fetch();
	}
	

	/**
	*@编辑司机
	*@return array
	**/
	public function editAction(){
		
		$id = Request::instance()->param('id');

		$DriverDetail = self::$_currentModel->detail($id);
		$this->assign('DriverDetail',$DriverDetail);//车辆详情
		$this->assign('city',$this->_CityList);//城市
		
		if(Request::instance()->isAjax()){
			$ValidateError = self::$_currentModel->DriverValidate($_POST);
			if(!empty($ValidateError)){
				return json(['data'=>NULL,'code'=>404,'message'=>$ValidateError]);
			}
			if(self::$_currentModel->edit($_POST)){
				return json(['data'=>url('list'),'code'=>200,'message'=>'编辑成功']);
			}else{
				return json(['data'=>NULL,'code'=>404,'message'=>'编辑失败']);
			}
		}

		return $this->fetch();
	}
	

	/**
	*@删除司机
	*@return array
	**/
	public function delDriverAction(){

		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$result = self::$_currentModel->delDriver($id);
			if($result){
				return json(['data'=>url('list'),'code'=>200,'message'=>'删除成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'删除失败']);
			}
		}
	}



}
