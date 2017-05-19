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
		$obj = json_decode($param['data']);
		if($param){
			foreach($obj as $key=>$value){
				$data['wofang_id'] = $value->id;
				$data['name'] = $value->name;
				$data['pid'] = $value->pid;
				$data['level'] = $value->level;
				$data['orders'] = $value->orders;
				$data['isGroup'] = $value->isGroup;
				$data['status'] = $value->status;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$data['status'] = $value->status;
				$addID[] = Loader::model('Department')->synchroDepartment($data);
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
		}else{
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
		}
	}

	/**
	*@城市同步
	**/
	public function synchronCityAction(){
		$param = $this->request->post();
		$obj = json_decode($param['data']);
		if($param){
			foreach($obj as $key=>$value){
				$data['wofang_id'] = $value->id;
				$data['name'] = $value->name;
				$data['pid'] = $value->pid;
				//$data['level'] = $value->level;
				$data['sort'] = $value->orders;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$addID[] = Loader::model('City')->synchroCity($data);
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);

		}else{
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
		}		
	}

	/**
	*@楼盘同步
	**/
	public function synchronBuildingBaseAction(){
		$param = $this->request->post();
		$obj = json_decode($param['data']);
		if($param){
			foreach($obj as $key=>$value){
				$data['wofang_id'] = $value->id;
				$data['name'] = $value->title;
				//$data['is_delete'] = $value->status;
				//$data['city_id'] = $value->city;
				$data['address'] = $value->address;
				$data['lng'] = $value->lng;
				$data['lat'] = $value->lat;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$data['city_id'] = Loader::model('City')::where('wofang_id',$value->city)->value('id');
				$addID[] = Loader::model('BuildingBase')->synchroBuildingBase($data);
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
		}else{
			return json(['code'=>200,'message'=>'同步失败','data'=>$param]);
		}
	}

	/**
	*@同步用户
	**/
	public function synchronUserAction(){
		$param = $this->request->post();
		$obj = json_decode($param['data']);
		if($param){
			foreach($obj as $key=>$value){
				$data['wofang_id'] = $value->id;
				//$data['name'] = $value->username;
				//$data['is_delete'] = $value->password;
				$data['real_name'] = $value->trueName;
				$data['phone'] = $value->tel;
				$data['department_id'] = $value->deaperId;
				//$data['status'] = $value->status;
				//$data['lat'] = $value->isPerson;
				//$data['lat'] = $value->roleId;
				$data['manager_type'] = $value->userType;
				$data['base_user_id'] = $value->id;

				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$addID[] = Loader::model('Manager')->synchroMag($data);
			}
			if(count($param) == count($addID)){
				return json(['code'=>200,'message'=>'完全同步成功','data'=>$param]);
			}else{
				return json(['code'=>200,'message'=>'部分同步成功','data'=>$param]);
			}
		}else{
			return json(['code'=>200,'message'=>'同步失败','data'=>$param]);
		}
	}

	/**
	*@同步用车明细
	**/
	public function synchronOutCarAction(){
		$param = $this->request->post();
		$obj = json_decode($param['data']);
		if($param){
			foreach($obj as $key=>$value){
				$data['wofang_id'] = $value->id;
				$data['used_id'] = $value->userId;
				$data['manager_id'] = $value->clientId;
				//$data['start_address'] = $value->fromCity;
				//$data['real_name'] = $value->aimCity;
				$data['customer_num'] = $value->clientNum;
				$data['oil_cost'] = $value->oilCost;
				$data['out_car_time'] = $value->outAt;
				$data['build_id'] = $value->buildingId;
				$data['status'] = $value->status;
				//$data['request_id'] = $value->getArrangeId;
				$data['start_address'] = $value->carAddress;
				$data['start_lng'] = $value->mapLng;
				$data['start_lat'] = $value->mapLat;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$addID[] = Loader::model('OutCar')->synchroOutCar($data);
			}
			if(count($param) == count($addID)){
				return json(['code'=>200,'message'=>'完全同步成功','data'=>$param]);
			}else{
				return json(['code'=>200,'message'=>'部分同步成功','data'=>$param]);
			}
		}else{
			return json(['code'=>200,'message'=>'同步失败','data'=>$param]);
		}
	}

}
