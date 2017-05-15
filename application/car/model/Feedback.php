<?php
/*
-----------------------------------
 评价模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class Feedback extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出


	/**
	* @评价列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = Feedback::where($where)->page($pageNumber,$totalNumber)->alias('a')->join('wf_take_car_order b','a.take_car_order_id = b.id')->join('wf_car c','b.car_id = c.id')->join('wf_driver d','b.driver_id = d.id')->join('wf_department e','b.department_id = e.id')->join('wf_manager f','b.manager_id = f.id')->field('a.*,c.number_plate,d.real_name as driver_name,e.name as dep_name,f.real_name as manager_name')->order('a.update_time desc')->select();
		return $lists;
	}
	/**
	* @数据总数
	* @return array
	**/
	public function totalCount($where,$totalNumber){
		$count = Feedback::where($where)->alias('a')->join('wf_take_car_order b','a.take_car_order_id = b.id')->join('wf_car c','b.car_id = c.id')->join('wf_driver d','b.driver_id = d.id')->join('wf_department e','b.department_id = e.id')->join('wf_manager f','b.manager_id = f.id')->count();
		return ceil(($count/$totalNumber));
	}

}

