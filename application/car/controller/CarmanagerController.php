<?php
/*
-----------------------------------
 车辆管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

class CarmanagerController extends BaseController{

	protected static $_currentModel;//当前操作模型
	protected $_CityList;


	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('Car');
		//获取城市
		$this->_CityList = Loader::model('City')->lists();
	}

	/**
	* @车辆列表
	* @return array
	**/	
	public function listAction($pageNumber=1,$totalNumber=10){
		
		$where = array();
		$front = array('numberPlate'=>'','cityId'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['numberPlate'])){
				$where['a.number_plate'] = ['like','%'.$_GET['numberPlate'].'%'];
				$front['numberPlate'] = $_GET['numberPlate'];
			}
			if(!empty($_GET['cityId'])){
				$where['b.id'] = $_GET['cityId'];
				$front['cityId'] = $_GET['cityId'];
			}
		}

		$where['a.is_delete'] = ['eq',1];

		$CarList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
	

		$this->assign('CarList',$CarList);//列表
		$this->assign('count',$count);//页码


		$this->assign('city',$this->_CityList);//城市

		$this->assign('front',$front);//筛选条件
		$this->assign('empty',"<tr><td colspan='6'>暂时没有数据</td></tr>");
		return $this->fetch();
	}

	/**
	*@添加车辆
	*@return array
	**/
	public function addAction(){
		
	
		$this->assign('city',$this->_CityList);//城市

		if(Request::instance()->isAjax()){
			$ValidateError = self::$_currentModel->CarValidate($_POST);
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
	*@编辑车辆
	*@return array
	**/
	public function editAction(){
		
		$id = Request::instance()->param('id');

		$CarDetail = self::$_currentModel->detail($id);
		$this->assign('CarDetail',$CarDetail);//车辆详情
		$this->assign('city',$this->_CityList);//城市

		if(Request::instance()->isAjax()){
			$ValidateError = self::$_currentModel->CarValidate($_POST);
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
	*@删除车辆
	*@return array
	**/
	public function delCarAction(){

		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$result = self::$_currentModel->delCar($id);
			if($result){
				return json(['data'=>url('list'),'code'=>200,'message'=>'删除成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'删除失败']);
			}
		}
	}





}
