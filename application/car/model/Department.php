<?php
/*
-----------------------------------
 部门模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;

class Department extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	protected $resultSetType = 'collection';

	/**
	* @部门列表
	* @return array
	**/
	public function lists($pageNumber,$totalNumber,$where){
		$lists = Department::where($where)->page($pageNumber,$totalNumber)->select();
		return $lists;
	}

}