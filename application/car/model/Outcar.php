<?php
/*
-----------------------------------
 出车模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class OutCar extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	/**
	* @出车列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = OutCar::where($where)->page($pageNumber,$totalNumber)->alias('a')->join('wf_take_car_order b','a.take_car_order_id = b.id')->join('wf_manager c','b.manager_id = c.id')->join('wf_building_base d','b.building_base_id = d.id')->join('wf_department e','b.department_id = e.id')->join('wf_driver f','b.driver_id = f.id')->join('wf_car g','b.car_id = g.id')->field('a.*,a.id as outid,b.*,b.id as orderid,c.*,c.id as managerid,c.real_name as manager_name,d.*,d.id as buildid,d.name as build_name,e.*,e.id as departid,e.name as department_name,f.*,f.id as driverid,f.real_name as driver_name,g.*')->order('a.create_time desc')->select();

		foreach($lists as $key=>$value){
			$value['order_status_cn'] = $this->orderStatus($value['order_status']);
		}
	
		return $lists;
	}
	/**
	* @数据总数
	* @return array
	**/
	public function totalCount($where,$totalNumber){
		$count = OutCar::where($where)->alias('a')->join('wf_take_car_order b','a.take_car_order_id = b.id')->join('wf_manager c','b.manager_id = c.id')->join('wf_building_base d','b.building_base_id = d.id')->join('wf_department e','b.department_id = e.id')->join('wf_driver f','b.driver_id = f.id')->join('wf_car g','b.car_id = g.id')->count();
		return ceil(($count/$totalNumber));
	}


	/**
	* @出车详情
	* @return array
	**/
	public function detail($id){
		$detail = OutCar::where('id',$id)->find();
		return $detail;
	}


	/**
	* @安排出车
	* @return array
	**/
	public function updateTakeOrderCar($id,$data){
	 $result = Loader::model('TakeCarOrder')::where('id',$id)->update($data);
	 return $result;	
	}


	/**
	* @订单状态转换
	* @return array
	**/
	public function orderStatus($status){
		switch($status){
			case 'over':
			  return '订单完成';
			  break;  
			case 'cancel':
			  return '取消';
			  break;
			case 'success':
			  return '成功匹配';
			  break;
			case 'drivering':
			  return '订单进行中';
			  break;
			default:
			  return '未派车';
		}
	}
	/**
	* @订单类型转换
	* @return array
	**/
	public function carStatus($status){
		switch($status){
			case 'come':
			  return '去程';
			  break;  
			case 'back':
			  return '回程';
			  break;
		}
	}
	/**
	* @附近车辆
	* @return array
	**/
	public function nearCars($id){
		//查询现在的单子的起始位置坐标
		$location = OutCar::where('id',$id)->find();
		$carData =  Loader::model('Car')::query("select *,ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$location['start_lat']."*PI()/180-lat*PI()/180)/2),2)+COS(".$location['start_lat']."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$location['start_lng']."*PI()/180-lng*PI()/180)/2),2)))*1000) as juli from `wf_car` 
	 having juli < 5000 order by juli asc");//附近的车辆

		foreach($carData as $key=>$value){
			//take_car_order
			$carData[$key]['carToOrder'] = $this->_carToOrder($value['id']);
			//管理员名字
			$carData[$key]['real_name'] = Loader::model('Manager')::where('id',$carData[$key]['carToOrder']['manager_id'])->value('real_name');

			//当前未派单的经度
			$carData[$key]['out_lat'] = $location['start_lat'];
			//当前未派单的纬度
			$carData[$key]['out_lng'] = $location['start_lng'];

			//找负责该订单的司机
			$carData[$key]['driverToOrder'] = Loader::model('Driver')::where('id',$carData[$key]['carToOrder']['driver_id'])->find();
			//预看楼盘
			$carData[$key]['buildname'] = Loader::model('BuildingBase')::where('id',$carData[$key]['carToOrder']['building_base_id'])->value('name');
			//车辆的周围车辆
			$carData[$key]['car_near_car'] =  count(Loader::model('Car')::query("select ROUND(6378.138*2*ASIN(SQRT(POW(SIN((".$value['lat']."*PI()/180-lat*PI()/180)/2),2)+COS(".$value['lat']."*PI()/180)*COS(lat*PI()/180)*POW(SIN((".$value['lng']."*PI()/180-lng*PI()/180)/2),2)))*1000) as juli from `wf_car` 
	 having juli < 5000 order by juli asc"));
		}
		//如果该车没有派订单则删除
		foreach($carData as $key=>$value){
			if(!isset($value['carToOrder'])){
				unset($carData[$key]);
			}
		}
		
		return $carData;
	}

	/**
	* @附近车辆所属的订单状态
	* @return array
	**/
	protected function _carToOrder($car_id){
		$where['car_id'] = $car_id;
		//获取订单进行中的该车的订单
		$where['order_status'] = 'drivering';
		$result = Loader::model('TakeCarOrder')::where($where)->find();
		return $result;
	}

	/**
	* @删除出车需求
	* @return array
	**/
	public function delOut($id){
		$where['id'] = $id;
		$result = OutCar::where($where)->update(['is_delete'=>0]);
		return $result;
	}

	/**
	* @出车行驶记录
	* @return array
	**/
	public function takeOrderShow($id){
		$where['id'] = $id;
		$detail = Loader::model('TakeCarOrder')::where($where)->find();
		$detail['city_name'] = Loader::model('City')::where('id',$detail['city_id'])->value('name');//城市名
		$detail['build_name'] = Loader::model('BuildingBase')::where('id',$detail['building_base_id'])->value('name');//楼盘名称
		$detail['driver_name'] = Loader::model('Driver')::where('id',$detail['driver_id'])->value('real_name');//司机名
		$detail['number_plate'] = Loader::model('Car')::where('id',$detail['car_id'])->value('number_plate');//车牌号
		$detail['department_name'] = Loader::model('Department')::where('id',$detail['department_id'])->value('name');//所在部门
		$detail['sale_name'] = Loader::model('manager')::where('id',$detail['manager_id'])->value('real_name');//销售顾问
		//订单类型和状态
		$detail['order_type'] = $this->carStatus($detail['order_type']);
		$detail['order_status'] = $this->orderStatus($detail['order_status']);
		return $detail;
	}

	/**
	* @从附近车辆派车
	* @return array
	**/
	public function paiche($data){
		$takeCarOrder = $this->detail($data['id']);
		$where['id'] = $takeCarOrder['take_car_order_id'];
		$update['car_id'] = $data['carid'];
		$update['driver_id'] = $data['driverid'];
		$update['order_status'] = 'success';
		$result = Loader::model('TakeCarOrder')::where($where)->update($update);
		return $result;
	}

}