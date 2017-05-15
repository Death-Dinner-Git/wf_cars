<?php
/*
-----------------------------------
 Manager模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;

class Manager extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	protected $resultSetType = 'collection';
	
	/**
	* @销售顾问列表(分页)
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = Manager::where($where)->page($pageNumber,$totalNumber)->select();
		return $lists;
	}
	/**
	* @销售顾问列表(不分页)
	* @return array
	**/
	public function noPagelists($where){
		$lists = Manager::where($where)->select();
		return $lists;
	}
}