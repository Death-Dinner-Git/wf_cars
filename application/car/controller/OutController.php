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

use app\manage\model\OutCar;


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
		self::$_currentModel = Loader::model('OutCar');
		//获取城市、司机
		$this->_CityList = Loader::model('City')->lists();
		$this->_Driver = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where='');

	}

	/**
     * @安排出车列表
     * @return array
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
            [['wf_manager'=>'m'],['t.manager_id = m.id']],
            [['wf_department'=>'de'],['m.department_id = de.id']],
            [['wf_take_car_order'=>'ta'],['t.take_car_order_id = ta.id'],'LEFT'],
            [['wf_building_base'=>'b'],['ta.building_base_id = b.id'],'LEFT'],
            [['wf_car'=>'ca'],['ta.car_id = ca.id'],'LEFT'],
            [['wf_city'=>'ci'],['ta.city_id = ci.id'],'LEFT'],
            [['wf_driver'=>'dr'],['ta.driver_id = dr.id'],'LEFT'],
        ];
        $fields = [
            't.*,t.id as outid,t.out_car_time as outcartime,t.start_lat as startlat',
            'm.*,m.id as managerid,m.real_name as manager_name',
            'de.*,de.id as departid,de.name as department_name',
            'ta.*,ta.id as orderid',
            'b.*,b.id as buildid,b.name as build_name',
            'ca.*',
            'ci.*',
            'dr.*,dr.id as driverid,dr.real_name as driver_name',
        ];

        $field = implode(',',$fields);
        $query = self::$_currentModel->alias('t');

        if ($numberPlate && $numberPlate != ''){
            $query = $query->where('ca.number_plate','like','%'.$numberPlate.'%');
            $param['numberPlate'] = $numberPlate;
        }

        if ($managerName && $managerName != ''){
            $query = $query->where('m.real_name','like','%'.$managerName.'%');
            $param['managerName'] = $managerName;
        }

        if ($cityid && $cityid != ''){
            $query = $query->where('ta.city_id',$cityid);
            $param['cityid'] = $cityid;
        }

        if ($driverId && $driverId != ''){
            $query = $query->where('ta.driver_id',$driverId);
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
            $param['create_time_start'] = $create_time_start;
        }
        $newEnd = null;
        if (strtotime($create_time_end) && $create_time_end !== null && $create_time_end !== '') {
            $newEnd = date('Y-m-d H:i:s', strtotime($create_time_end));
            $query = $query->where('t.out_car_time', '<=', $newEnd);
            $param['create_time_end'] = $create_time_end;
        }

        $query = $query->join($join)->where($where);
        $query = $query->field($field);


        $pageQuery = clone $query;
        $count = ceil(($pageQuery->count())/$each);
        $OutList = $query->page($pageNumber,$each)->order('t.create_time','DESC')->select();
		
		$this->assign('front',$param);
		$this->assign('OutList',$OutList);
		$this->assign('count',$count);
		$this->assign('city',$this->_CityList);
		$this->assign('driver',$this->_Driver);
		$this->assign('empty',"<tr><td colspan='11'>暂时没有数据</td></tr>");
		return $this->fetch();
	}


	/**
	* @安排看房
	* @return array
	**/
	public function lookbuildAction(){

		$id = Request::instance()->param('id');
		
		$join = [
			 [['wf_manager'=>'m'],['out.manager_id=m.id'],'LEFT'],
			 [['wf_department'=>'de'],['m.department_id=de.id'],'LEFT'],
			 [['wf_building_base'=>'b'],['out.build_id=b.id'],'LEFT'],
			 [['wf_city'=>'c1'],['out.start_city_id=c1.id'],'LEFT'],
			 [['wf_city'=>'c2'],['out.end_city_id=c2.id'],'LEFT'],
		];
		$fields = [
		'out.*,out.id as outid',
		'de.*,de.id as depid',
		'b.*',
		'c1.*,c1.name as startcityname',
		'c2.*,c2.name as endcityname',
		];

		$field = implode(',',$fields);

		$OutDetail = self::$_currentModel::where('out.id','=',$id)->alias('out')->field($field)->join($join)->find();
		$ManagerList = Loader::model('Manager')->noPagelists($where='');
		$BuildingList = Loader::model('BuildingBase')->noPagelists($where='');
		
		
		if(Request::instance()->isAjax()){
			$data = Request::instance()->post();
			//检查该订单是否已经派车
			$isHave = Loader::model('TakeCarOrder')::where('out_car_id',$id)->value('order_status');
			if($isHave!=null && $isHave!='ordered'){
				return json(['data'=>null,'code'=>404,'message'=>'该派车单已经派车']);
			}
			//检查司机的状态
			$driverStatus = Loader::model('Driver')::where('id',$data['citydriver'])->value('status');
			//司机不是回程不能派车
			if($driverStatus!='come' && $driverStatus!='returned'){
				return json(['data'=>null,'code'=>404,'message'=>'当前司机不能派车']);
			}
			if($driverStatus=='back'){
				return json(['data'=>null,'code'=>404,'message'=>'司机已返程无法派车']);
			}
			//查询该司机最新的一笔订单的状态
			$driverCurrentOrderStatus = Loader::model('TakeCarOrder')::where('driver_id',$data['citydriver'])->field('order_status,MAX(end_time)')->find();
			if($driverCurrentOrderStatus['order_status']!=null && $driverCurrentOrderStatus['order_status']!='over'){
				return json(['data'=>null,'code'=>404,'message'=>'当前司机还有订单正在处理']);
			}
			//查询当前车辆使用情况
			$carCurrentOrderStatus = Loader::model('TakeCarOrder')::where('car_id',$data['citycar'])->field('order_status,MAX(end_time)')->find();
			if($carCurrentOrderStatus['order_status']!=null && $carCurrentOrderStatus['order_status']!='over'){
				return json(['data'=>null,'code'=>404,'message'=>'该车辆已被使用']);
			}
			
			$takeCarOrderData = array();
			$takeCarOrderData['manager_id'] = $data['managerId'];
			$takeCarOrderData['building_base_id'] = $data['buildingId'];
			$takeCarOrderData['car_id'] = $data['citycar'];
			$takeCarOrderData['remark'] = '未签到';
			$takeCarOrderData['driver_id'] = $data['citydriver'];
			$takeCarOrderData['city_id'] = $OutDetail['start_city_id'];
			$takeCarOrderData['booking_time'] = $OutDetail['out_car_time'];
			$takeCarOrderData['department_id'] = $OutDetail['depid'];
			$takeCarOrderData['end_lng'] = $data['endtLng'];
			$takeCarOrderData['end_lat'] = $data['endLat'];
			$takeCarOrderData['end_address'] = empty($data['end_address'])?$data['endaddress']:$data['end_address'];
			$takeCarOrderData['start_address'] = empty($data['start_address'])?$data['startaddress']:$data['start_address'];
			$takeCarOrderData['order_status'] = 'ordered';
			$takeCarOrderData['order_type'] = 'come';
			$takeCarOrderData['out_car_id'] = $data['id'];
			$takeCarOrderData['start_lat'] = $data['startLat'];
			$takeCarOrderData['start_lng'] = $data['startLng'];
			$takeCarOrderData['customerNum'] = $OutDetail['customer_num'];
			$takeCarOrderData['order_num'] = date('YmdHis');
			if($isHave!=null){
				$result = self::$_currentModel->updateTakeOrderCar($id,$takeCarOrderData);
			}else{
				$result = self::$_currentModel->insertTakeOrderCar($takeCarOrderData);
			}
			
			$outCarData = array();
			$outCarData['manager_id'] = $data['managerId'];
			$outCarData['build_id'] = $data['buildingId'];
			$outCarData['start_lat'] = $data['startLat'];
			$outCarData['start_lng'] = $data['startLng'];
			$outCarData['start_address'] = empty($data['start_address'])?$data['startaddress']:$data['start_address'];
			self::$_currentModel::where('id',$id)->update($outCarData);

			$driverData = array();
			$driverData['car_id'] = $data['citycar'];
			$driverData['status'] = 'come';
			Loader::model('Driver')::where('id',$data['citydriver'])->update($driverData);
			
			if($result){
				return json(['data'=>$result,'code'=>200,'message'=>'出车成功']);
			}else{
				return json(['data'=>null,'code'=>404,'message'=>'出车失败']);
			}

		}

		$this->assign('OutDetail',$OutDetail);
		$this->assign('BuildingList',$BuildingList);
		$this->assign('ManagerList',$ManagerList);
		$this->assign('city',$this->_CityList);
		$this->assign('driver',$this->_Driver);
		
		return $this->fetch();
	}


	/**
	* @根据城市选择车辆
	* @return array
	**/
	public function chooseCarAction(){
		$cityid = Request::instance()->post('id');
		$cityCarList = Loader::model('Car')->cityCar($cityid);
		if(empty($cityCarList)){
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
		$cityid = Request::instance()->post('id');
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
		return $this->fetch();
	}


	/**
	* @附近车辆Ajax
	* @return array
	**/
	public function nearcarsAjaxAction(){
		
		if(Request::instance()->isAjax()){
			$id = Request::instance()->param('id');
			$nearCars = self::$_currentModel->nearCars($id);
			if(empty($nearCars)){
				return json(['data'=>null,'code'=>404,'message'=>'获取失败']);
			}else{
				return json(['data'=>$nearCars,'code'=>200,'message'=>'获取成功']);
			}
			
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
		//return $this->fetch('out/showmark');
		return view('out/showmark');
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
