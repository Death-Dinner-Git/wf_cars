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

use app\car\model\OutCar;
use app\car\model\TakeCarOrder;
use app\car\model\Car;
use app\car\model\City;
use app\car\model\Manager;
use app\car\model\Driver;
use app\car\model\BuildingBase;
use app\car\model\Department;

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
     * @description 安排出车列表
     * @param  $pageNumber
     * @param  $totalNumber
     * @param null $numberPlate
     * @param null $managerName
     * @param null $cityid
     * @param null $driverId
     * @param null $status
     * @param null $create_time_start
     * @param null $create_time_end
     * @return mixed
     */
	public function listAction($pageNumber=1,$totalNumber=10,$numberPlate = null,$managerName = null, $cityid = null,$driverId = null, $status = null, $create_time_start = null, $create_time_end = null)
    {

        $each = 10;
        $where = ['t.is_delete'=>'1'];
        $param = [
            'numberPlate'=>'',
            'managerName'=>'',
            'cityid'=>'',
            'driverId'=>'',
            'status'=>'',
            'create_time_start'=>'',
            'create_time_end'=>'',
        ];
        $join = [
            [[Manager::tableName()=>'m'],['t.manager_id = m.id']],
            [[Department::tableName()=>'de'],['m.department_id = de.id']],
            [[TakeCarOrder::tableName()=>'ta'],['t.take_car_order_id = ta.id'],'LEFT'],
            [[BuildingBase::tableName()=>'b'],['ta.building_base_id = b.id'],'LEFT'],
            [[Car::tableName()=>'ca'],['ta.car_id = ca.id'],'LEFT'],
            [[City::tableName()=>'ci'],['ta.city_id = ci.id'],'LEFT'],
            [[Driver::tableName()=>'dr'],['ta.driver_id = dr.id'],'LEFT'],
        ];
        $fields = [
            't.*,t.id as outid',
            'm.*,m.id as managerid,m.real_name as manager_name',
            'de.*,de.id as departid,de.name as department_name',
            'ta.*,ta.id as orderid',
            'b.*,b.id as buildid,b.name as build_name',
            'ca.*',
            'ci.*',
            'dr.*,dr.id as driverid,dr.real_name as driver_name',
        ];

        $field = implode(',',$fields);
        $query = OutCar::load()->alias('t');

        if ($numberPlate && $numberPlate != ''){
            $query = $query->where('ca.number_plate',$numberPlate);
            $param['numberPlate'] = $numberPlate;
        }

        if ($managerName && $managerName != ''){
            $query = $query->where('m.real_name',$managerName);
            $param['managerName'] = $managerName;
        }

        if ($cityid && $cityid != ''){
            $query = $query->where('ta.city_id',$cityid);
            $param['cityid'] = $cityid;
        }

        if ($driverId && $driverId != ''){
            $query = $query->where('dr.driver_id',$driverId);
            $param['driverId'] = $driverId;
        }

        if ($status && $status != ''){
            $statuses = 'cancel';
            if ($status == '1'){
                $query = $query->where('t.take_car_order_id',null);
            }else{
                $query = $query->where('ta.order_status','not in', $statuses);
            }
            $param['status'] = $status;
        }
        $newStart = null;
        if (strtotime($create_time_start) && $create_time_start !== null && $create_time_start !== '') {
            $newStart = date('Y-m-d H:i:s', strtotime($create_time_start));
            $query = $query->where('t.out_car_time', '>=', $newStart);
            $query = $query->where('ta.out_car_time', '>=', $newStart);
            $param['create_time_start'] = $create_time_start;
        }
        $newEnd = null;
        if (strtotime($create_time_end) && $create_time_end !== null && $create_time_end !== '') {
            $newEnd = date('Y-m-d H:i:s', strtotime($create_time_end));
            $query = $query->where('t.out_car_time', '<=', $newEnd);
            $query = $query->where('ta.out_car_time', '<=', $newEnd);
            $param['create_time_end'] = $create_time_end;
        }

        $query = $query->join($join)->where($where);
        $query = $query->field($field);


        $pageQuery = clone $query;
        $count = ceil(($pageQuery->count())/$each);
        $dataProvider = $query->page($pageNumber,$each)->order('t.create_time','DESC')->select();

		$this->assign('front',$param);

		$this->assign('OutList',$dataProvider);
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
