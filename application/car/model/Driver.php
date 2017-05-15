<?php
/*
-----------------------------------
 司机模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class Driver extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	protected $resultSetType = 'collection';
	/**
	* @司机操作验证器
	* @parma array ***
	* @return string
	**/
	public function DriverValidate($checkData){
		$validate = Loader::validate('Driver');
		if(!$validate->scene('Driver')->check($checkData)){
			return $validate->getError();
		}
	}

	/**
	* @司机列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){

		$lists = Driver::where($where)->page($pageNumber,$totalNumber)->select();
		return $lists;
	}
	/**
	* @数据总数
	* @return array
	**/
	public function totalCount($where,$totalNumber){
		$count = Driver::where($where)->count();
		return ceil(($count/$totalNumber));
	}

	/**
	* @添加司机
	* @return array
	**/
	public function add($driverdata){
		//写入的数据
		$data = array (
			'real_name'=>$driverdata['username'],
			'mobilephone'=>$driverdata['phone'],
			'city_id'=>$driverdata['cityid'],
			'driving_license'=>$driverdata['drivingLicense'],
			'password'=>$driverdata['password']
		);
		$driver = self::create($data);
		return $driver->id;
	}
	/**
	* @根据城市id获取城市名称
	* @return array
	**/
	public  function turnCityName($cityid){
		$CityData = Loader::model('City')->where('wofang_id',$cityid)->value('name');
		return $CityData;
	}

	/**
	* @获取司机详情
	* @return array
	**/
	public function detail($id){
		$detail = Driver::where('id',$id)->find();
		return $detail;
	}
	
	/**
	* @编辑司机详情
	* @return array
	**/
	public function edit($driverdata){
		//写入的数据
		$data = array (
			'real_name'=>$driverdata['username'],
			'mobilephone'=>$driverdata['phone'],
			'city_id'=>$driverdata['cityid'],
			'driving_license'=>$driverdata['drivingLicense'],
			'password'=>$driverdata['password']
		);
		$result = self::where('id',$driverdata['id'])->update($data);
		return $result;
	}
	
	/**
	* @删除司机
	* @return array
	**/
	public function delDriver($id){
		$where['id'] = $id;
		$result = Driver::where($where)->update(['is_delete'=>0]);
		return $result;
	}

}