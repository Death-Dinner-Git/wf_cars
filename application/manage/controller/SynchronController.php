<?php
/*
-----------------------------------
 我房同步数据
-----------------------------------
*/

namespace app\manage\controller;

use think\Controller;
use think\Loader;


class SynchronController extends Controller{


	/**
	*@部门同步
	**/
	public function synchronDepartmentAction(){
		$param = $this->request->post();
		file_put_contents(APP_PATH.'Department.log',$param,FILE_APPEND);
		return json(['code'=>200,'message'=>'成功','data'=>$param]);
	}

	/**
	*@城市同步
	**/
	public function synchronCityAction(){
		
		$param = $this->request->post();
		file_put_contents(APP_PATH.'City.log',$param,FILE_APPEND);
	}

	/**
	*@楼盘同步
	**/
	public function synchronBuildingBaseAction(){
		
		$param = $this->request->post();
		file_put_contents(APP_PATH.'Building.log',$param,FILE_APPEND);
	}

	/**
	*@同步用户
	**/
	public function synchronUserAction(){
		
		$param = $this->request->post();
		file_put_contents(APP_PATH.'User.log',$param,FILE_APPEND);
	}

	/**
	*@同步用车明细
	**/
	public function synchronOutCarAction(){
		
		$param = $this->request->post();
		file_put_contents(APP_PATH.'OutCar.log',$param,FILE_APPEND);
	}

}
