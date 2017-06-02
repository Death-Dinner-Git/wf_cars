<?php
/*
-----------------------------------
 出车表模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class TakeCarOrder extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出


	/**
	* @出车详情
	* @return array
	**/
	public function detail($id){
		$detail = TakeCarOrder::where('id','=',$id)->find();
		return $detail;
	}
	/**
	* @删除出车订单
	* @return array
	**/
	public function delOrder($id){
		$where['id'] = $id;
		$result = TakeCarOrder::where($where)->update(['is_delete'=>0]);
		return $result;
	}

	/**
	* @打车列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = TakeCarOrder::where($where)->page($pageNumber,$totalNumber)->alias('a')->join('wf_department b','a.department_id = b.id')->join('wf_manager c','a.manager_id = c.id')->join('wf_building_base d','a.building_base_id = d.id')->join('wf_car e','a.car_id = e.id')->join('wf_driver f','a.driver_id = f.id')->field('a.*,c.real_name as mang_name,b.name as dep_name,d.name as build_name')->order('a.create_time desc')->select();
		return $lists;
	}
	/**
	* @数据总数
	* @return array
	**/
	public function totalCount($where,$totalNumber){
		$count = TakeCarOrder::where($where)->alias('a')->join('wf_department b','a.department_id = b.id')->join('wf_manager c','a.manager_id = c.id')->join('wf_building_base d','a.building_base_id = d.id')->join('wf_car e','a.car_id = e.id')->join('wf_driver f','a.driver_id = f.id')->count();
		return ceil(($count/$totalNumber));
	}
	/**
	* @订单状态转换
	* @return array
	**/
	/*
	public function orderStatus($status){
		switch($status){
			case 'over':
			  return '订单完成';
			  break;  
			case 'cancel':
			  return '取消';
			  break;
			case 'ordered':
			  return '抢单中';
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
	*/
	/**
	* @订单类型转换
	* @return array
	**/
	/*
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
	*/


	/**
	* @出车行驶轨迹
	* @return array
	**/
	public function takeOrderShow($id){
		$where['id'] = $id;
		$detail = TakeCarOrder::where($where)->find();
		$detail['city_name'] = Loader::model('City')::where('id',$detail['city_id'])->value('name');//城市名
		$detail['build_name'] = Loader::model('BuildingBase')::where('id',$detail['building_base_id'])->value('name');//楼盘名称
		$detail['driver_name'] = Loader::model('Driver')::where('id',$detail['driver_id'])->value('real_name');//司机名
		$detail['number_plate'] = Loader::model('Car')::where('id',$detail['car_id'])->value('number_plate');//车牌号
		
		$sim = Loader::model('Car')::where('id',$detail['car_id'])->value('sim');//sim
		$detail['gps'] = Loader::model('Gps')::where('SIM',$sim)->field('lng,lat')->select();//车辆行驶轨迹
		
		$road = '[';
		foreach($detail['gps'] as $key => $value){
			$road .= '"'.$value['lng'].",".$value['lat'].'",';
		}
		$road .= ']';
		$detail['gps'] = $road;
		$detail['department_name'] = Loader::model('Department')::where('id',$detail['department_id'])->value('name');//所在部门
		$detail['sale_name'] = Loader::model('manager')::where('id',$detail['manager_id'])->value('real_name');//销售顾问

		return $detail;
	}


}