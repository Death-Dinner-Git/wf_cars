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
				if($value->status == 5){
					$data['status'] = 1;
				}else{
					$data['status'] = 2;
				}
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
				$data['sort'] = $value->orders;
				$data['is_delete'] = 1;
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
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
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
				
				$baseuserdata['username'] = $value->username;
				$baseuserdata['password'] = md5(888888);
				$baseuserdata['remark'] = $value->password;
				$baseuserdata['VERSION_NUM'] = 0;
				$baseuserdata['create_time'] = date('Y:m:d H:i:s');
				$baseuserdata['update_time'] = date('Y:m:d H:i:s');				
				$addID[] = Loader::model('BaseUser')->synchroBaseUser($baseuserdata);
				$data['base_user_id'] = Loader::model('BaseUser')::where('username',$value->username)->value('id');
				$data['wofang_id'] = $value->id;
				$data['real_name'] = $value->trueName;
				$data['phone'] = $value->tel;
				if($value->status==5){
					$data['status'] = 1;
				}else{
					$data['status'] = 2;
				}
				$data['manager_type'] = $value->userType;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$data['department_id'] = 
				Loader::model('Department')::where('wofang_id',$value->deaperId)->value('id');
				$addID[] = Loader::model('Manager')->synchroManager($data);
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
		}else{
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
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
				$data['build_id'] = Loader::model('BuildingBase')::where('wofang_id',$value->buildingId)->value('id');
				$data['start_city_id'] = Loader::model('City')::where('wofang_id',$value->fromCity)->value('id');
				$data['end_city_id'] = Loader::model('City')::where('wofang_id',$value->aimCity)->value('id');
				$data['manager_id'] = Loader::model('Manager')::where('wofang_id',$value->userId)->value('id');
				$data['customer_num'] = $value->clientNum;
				$data['oil_cost'] = $value->oilCost;
				$data['out_car_time'] = $value->outAt;
				$data['start_address'] = $value->carAddress;
				$data['start_lng'] = $value->mapLng;
				$data['start_lat'] = $value->mapLat;
				$data['request_id'] = $value->askType;
				$data['is_delete'] = 1;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$addID[] = Loader::model('OutCar')->synchroOutCar($data);
				
				$take_car_order_id = Loader::model('OutCar')::where('wofang_id',$value->id)->value('take_car_order_id');
				if($take_car_order_id){
					$takeordercardata['booking_time'] = $value->outAt;
					$addID[] = Loader::model('TakeCarOrder')->synchroTakeCarOrder($takeordercardata);
				}
				
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
		}else{
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
		}
	}

}
