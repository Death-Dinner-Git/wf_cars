<?php
/*
-----------------------------------
 楼盘模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;

class BuildingBase extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	protected $resultSetType = 'collection';
	
	/**
	* @楼盘列表(分页)
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = BuildingBase::where($where)->page($pageNumber,$totalNumber)->select();
		return $lists;
	}	
	/**
	* @楼盘列表(不分页)
	* @return array
	**/
	public function noPagelists($where){
		$lists = BuildingBase::where($where)->select();
		return $lists;
	}
}