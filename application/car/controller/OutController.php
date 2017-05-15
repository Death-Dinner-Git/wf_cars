<?php
/*
-----------------------------------
 出车管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

class OutController extends BaseController{

	protected $_CityList;
	protected $_Driver;
	protected static $_currentModel;//当前操作模型

	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('Outcar');
		//获取城市、司机
		$this->_CityList = Loader::model('City')->lists();
		$this->_Driver = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where='');

	}

	/**
	* @安排出车列表
	* @return array
	**/
	public function listAction($pageNumber=1,$totalNumber=10){
		
		$where = array();
		$front = array('numberPlate'=>'','managerName'=>'','cityid'=>'','driverId'=>'','status'=>'','create_time_start'=>'','create_time_end'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['numberPlate'])){
				$where['g.number_plate'] = $_GET['numberPlate'];
				$front['numberPlate'] = $_GET['numberPlate'];
			}
			if(!empty($_GET['managerName'])){
				$where['c.real_name'] = $_GET['managerName'];
				$front['managerName'] = $_GET['managerName'];
			}
			if(!empty($_GET['cityid'])){
				$where['b.city_id'] = $_GET['cityid'];
				$front['cityid'] = $_GET['cityid'];
			}
			if(!empty($_GET['driverId'])){
				$where['b.driver_id'] = $_GET['driverId'];
				$front['driverId'] = $_GET['driverId'];
			}
			if(!empty($_GET['status'])){
				$where['b.order_status'] = $_GET['status'];
				$front['status'] = $_GET['status'];
			}
			if(!empty($_GET['create_time_start'])){
				$where['a.out_car_time'] = array('egt',$_GET['create_time_start']);
				$front['create_time_start'] = $_GET['create_time_start'];
			}
			if(!empty($_GET['create_time_end'])){
				$where['a.back_date'] = array('elt',$_GET['create_time_end']);
				$front['create_time_end'] = $_GET['create_time_end'];
			}
			
		}

		$where['a.is_delete'] = ['eq',1];
		$where['b.is_delete'] = ['eq',1];

		$OutList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
		
		$this->assign('front',$front);

		$this->assign('OutList',$OutList);
		$this->assign('count',$count);
		$this->assign('city',$this->_CityList);
		$this->assign('driver',$this->_Driver);
		return $this->fetch();
	}

	/**
	* @安排看房
	* @return array
	**/
	public function lookbuildAction(){

		$id = Request::instance()->param('id');

        $OutDetail = self::$_currentModel->detail($id);
		$takeOrderCarDetail = Loader::model('TakeCarOrder')->detail($id);
		$ManagerList = Loader::model('Manager')->noPagelists($where='');//销售顾问
		$BuildingList = Loader::model('BuildingBase')->noPagelists($where='');//楼盘
		

		//数据提交
		if(Request::instance()->isAjax()){
			$data = Request::instance()->post();
			
			$id = $data['ordercarid'];
			$insertData = array();
			$insertData['city_id'] = $data['cityid'];
			$insertData['car_id'] = $data['citycar'];
			$insertData['driver_id'] = $data['citydriver'];
			$insertData['manager_id'] = $data['managerId'];
			$insertData['building_base_id'] = $data['buildingId'];
			$insertData['start_address'] = empty($data['start_address'])?$data['startaddress']:$data['start_address'];
			$insertData['start_lat'] = $data['startLat'];
			$insertData['start_lng'] = $data['startLng'];
			$insertData['end_address'] = empty($data['end_address'])?$data['endaddress']:$data['end_address'];
			$insertData['end_lat'] = $data['endLat'];
			$insertData['end_lng'] = $data['endtLng'];

			//过滤空数组
			$insertData = array_filter($insertData);
			$result = self::$_currentModel->updateTakeOrderCar($id,$insertData);
			if($result){
				return json(['data'=>url('list'),'code'=>200,'message'=>'派车成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'派车失败']);
			}
		}


		$this->assign('OutDetail',$OutDetail);//out_car表
		$this->assign('takeOrderCarDetail',$takeOrderCarDetail);//take_Order_Car表
		$this->assign('BuildingList',$BuildingList);
		$this->assign('ManagerList',$ManagerList);
		$this->assign('city',$this->_CityList);//城市
		$this->assign('driver',$this->_Driver);//司机

		return view('out/lookbuild');
	}

	/**
	* @根据城市选择车辆
	* @return array
	**/
	public function chooseCarAction(){
		$cityid = $_POST['id'];
		$cityCarList = Loader::model('Car')->cityCar($cityid);
		if($cityCarList->isEmpty()){
			return json(['data'=>null,'code'=>404,'message'=>'此城市暂无车辆']);
		}else{
			return json(['data'=>$cityCarList,'code'=>200,'message'=>'获取成功']);
		}
	}
	/**
	* @根据城市选择司机
	* @return array
	**/
	public function chooseDriverAction(){
		$cityid = $_POST['id'];
		$where['city_id'] = $cityid;
		$cityDriverList = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where);
		if($cityDriverList->isEmpty()){
			return json(['data'=>null,'code'=>404,'message'=>'此城市暂无司机']);
		}else{
			return json(['data'=>$cityDriverList,'code'=>200,'message'=>'获取成功']);
		}
	}

	/**
	* @附近车辆
	* @return array
	**/
	public function nearcarsAction(){

		$id = Request::instance()->param('id');

		//当前订单所在位置
		$currentLocation = self::$_currentModel->detail($id);
		
		$this->assign('id',$id);//当前订单id
		$this->assign('start_lat',$currentLocation['start_lat']);
		$this->assign('start_lng',$currentLocation['start_lng']);
		return view('out/nearcars');
	}

	/**
	* @附近车辆Ajax
	* @return array
	**/
	public function nearcarsAjaxAction(){
		
		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$nearCars = self::$_currentModel->nearCars($id);
			return json(['data'=>$nearCars,'code'=>200,'message'=>'获取成功']);
		}

	}
	/**
	* @删除出车需求
	* @return array
	**/
	public function delOutAction(){
		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$result = self::$_currentModel->delOut($id);
			if($result){
				return json(['data'=>$result,'code'=>200,'message'=>'删除成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'删除失败']);
			}
		}
	}

	/**
	* @删除出车订单
	* @return array
	**/
	public function deloutOrderAction(){
		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$result = Loader::model('TakeCarOrder')->delOrder($id);
			if($result){
				return json(['data'=>$result,'code'=>200,'message'=>'删除成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'删除失败']);
			}
		}
	}

	/**
	* @查看行驶记录
	* @return array
	**/
	public function takeOrderShowAction(){
		$id = Request::instance()->param('id');
		$result = self::$_currentModel->takeOrderShow($id);
		$this->assign('record',$result);
		return $this->fetch('out/showMark');
	}

	/**
	* @编辑出车订单
	* @return array
	**/
	public function editOrderAction(){

		$id = Request::instance()->param('id');

        $OutDetail = self::$_currentModel->detail($id);
		$takeOrderCarDetail = Loader::model('TakeCarOrder')->detail($id);
		$ManagerList = Loader::model('Manager')->noPagelists($where='');//销售顾问
		$BuildingList = Loader::model('BuildingBase')->noPagelists($where='');//楼盘
		

		//数据提交
		if(Request::instance()->isAjax()){
			$data = Request::instance()->post();
			
			$id = $data['ordercarid'];
			$insertData = array();
			$insertData['city_id'] = $data['cityid'];
			$insertData['car_id'] = $data['citycar'];
			$insertData['driver_id'] = $data['citydriver'];
			$insertData['manager_id'] = $data['managerId'];
			$insertData['building_base_id'] = $data['buildingId'];
			$insertData['start_address'] = empty($data['start_address'])?$data['startaddress']:$data['start_address'];
			$insertData['start_lat'] = $data['startLat'];
			$insertData['start_lng'] = $data['startLng'];
			$insertData['end_address'] = empty($data['end_address'])?$data['endaddress']:$data['end_address'];
			$insertData['end_lat'] = $data['endLat'];
			$insertData['end_lng'] = $data['endtLng'];

			//过滤空数组
			$insertData = array_filter($insertData);
			$result = self::$_currentModel->updateTakeOrderCar($id,$insertData);
			if($result){
				return json(['data'=>url('list'),'code'=>200,'message'=>'编辑成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'编辑失败']);
			}
		}


		$this->assign('OutDetail',$OutDetail);//out_car表
		$this->assign('takeOrderCarDetail',$takeOrderCarDetail);//take_Order_Car表
		$this->assign('BuildingList',$BuildingList);
		$this->assign('ManagerList',$ManagerList);
		$this->assign('city',$this->_CityList);//城市
		$this->assign('driver',$this->_Driver);//司机
		
		
		return $this->fetch();
	}

	/**
	* @从附近车辆里派车
	* @return array
	**/
	public function paicheAction(){
		$data = Request::instance()->post();
		$result = self::$_currentModel->paiche($data);
		if($result){
			return json(['data'=>$result,'code'=>200,'message'=>'派车成功']);
		}else{
			return json(['data'=>null,'code'=>404,'message'=>'派车失败']);
		}
	}
}
