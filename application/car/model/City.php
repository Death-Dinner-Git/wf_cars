<?php
/*
-----------------------------------
 城市模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;

class City extends Model{

	public function lists(){

		$lists =  City::all();
		return $lists;
	}

}