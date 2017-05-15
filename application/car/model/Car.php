<?php
/*
-----------------------------------
 车辆模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class Car extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出


	/**
	* @车辆操作验证器
	* @parma array ***
	* @return string
	**/
	public function CarValidate($checkData){
		$validate = Loader::validate('Car');
		if(!$validate->scene('Car')->check($checkData)){
			return $validate->getError();
		}
	}

	/**
	* @获取指定城市所有的车
	* @return array
	**/
	public function cityCar($cityid){

		$cityCarList = Car::where('city_id','=',$cityid)->select();
		return $cityCarList;
	}

	/**
	* @车辆列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = Car::where($where)->page($pageNumber,$totalNumber)->alias('a')->join('wf_city b','a.city_id = b.id')->field('a.*,b.name')->order('a.update_time desc')->select();
		return $lists;
	}
	/**
	* @数据总数
	* @return array
	**/
	public function totalCount($where,$totalNumber){
		$count = Car::where($where)->alias('a')->join('wf_city b','a.city_id = b.id')->count();
		return ceil(($count/$totalNumber));
	}

	/**
	* @添加车辆
	* @return array
	**/
	public function add($cardata){
		//写入的数据
		$data = array (
			'number_plate'=>$cardata['numberPlate'],
			'car_type'=>$cardata['carType'],
			'city_id'=>$cardata['cityId'],
			'seat_num'=>$cardata['seatNum'],
			'address'=>$cardata['address'],
			'lat'=>$cardata['lat'],
			'lng'=>$cardata['lng'],
			'latlng_update_time'=>date('Y-m-d H:i:s',time())
		);
		$driver = self::create($data);
		return $driver->id;
	}

	/**
	* @获取车辆详情
	* @return array
	**/
	public function detail($id){
		$detail = Car::where('id',$id)->find();
		return $detail;
	}
	/**
	* @编辑车辆详情
	* @return array
	**/
	public function edit($cardata){
		//写入的数据
		$data = array (
			'number_plate'=>$cardata['numberPlate'],
			'car_type'=>$cardata['carType'],
			'city_id'=>$cardata['cityId'],
			'seat_num'=>$cardata['seatNum'],
			'address'=>$cardata['address'],
			'lat'=>$cardata['lat'],
			'lng'=>$cardata['lng'],
			'latlng_update_time'=>date('Y-m-d H:i:s',time())
		);
		$result = self::where('id',$cardata['id'])->update($data);
		return $result;
	}

	/**
	* @删除车辆
	* @return array
	**/
	public function delCar($id){
		$where['id'] = $id;
		$result = Car::where($where)->update(['is_delete'=>0]);
		return $result;
	}	

}