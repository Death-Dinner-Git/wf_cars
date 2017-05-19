<?php
/*
-----------------------------------
 我房同步数据
-----------------------------------
*/

namespace app\manage\controller;
use think\Controller;
use think\Loader;


class NewSynchronController extends Controller{

    /**
     * @description 部门同步
     * @return \think\response\Json
     */
	public function synchronDepartmentAction(){
		$param = $this->request->post();
		if($param){
            $addID = [];
            $param['data'] = !isset($param['data']) ? : '';
            $obj = json_decode($param['data'],true);
			foreach($obj as $key=>$value){
				$data['wofang_id'] = isset($value['id']) ? $value['id'] : null;
				$data['name'] = isset($value['name']) ? $value['name'] : null;
				$data['pid'] = isset($value['pid']) ? $value['pid'] : null;
				$data['level'] = isset($value['level']) ? $value['level'] : null;
				$data['orders'] = isset($value['orders']) ? $value['orders'] : null;
				$data['isGroup'] = isset($value['isGroup']) ? $value['isGroup'] : null;
				$data['status'] = isset($value['status']) ? $value['status'] : null;
				$data['create_time'] = date('Y:m:d H:i:s');
				$data['update_time'] = date('Y:m:d H:i:s');
				$addID[] = Loader::model('Department')->synchroDepartment($data);
			}
			return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
		}else{
			return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
		}
	}

    /**
     * @description 城市同步
     * @return \think\response\Json
     */
	public function synchronCityAction(){
        $param = $this->request->post();
        if($param){
            $addID = [];
            $param['data'] = !isset($param['data']) ? : '';
            $obj = json_decode($param['data'],true);
            foreach($obj as $key=>$value){
                $data['wofang_id'] = isset($value['id']) ? $value['id'] : null;
                $data['name'] = isset($value['name']) ? $value['name'] : null;
                $data['pid'] = isset($value['pid']) ? $value['pid'] : null;
                $data['sort'] = isset($value['orders']) ? $value['orders'] : null;
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
     * @description 楼盘同步
     * @return \think\response\Json
     */
	public function synchronBuildingBaseAction(){
        $param = $this->request->post();
        if($param){
            $addID = [];
            $param['data'] = !isset($param['data']) ? : '';
            $obj = json_decode($param['data'],true);
            foreach($obj as $key=>$value){
                $data['wofang_id'] = isset($value['id']) ? $value['id'] : null;
                $data['name'] = isset($value['title']) ? $value['title'] : null;
                $data['city_id'] = isset($value['city']) ? $value['city'] : null;
                $data['address'] = isset($value['address']) ? $value['address'] : null;
                $data['lng'] = isset($value['lng']) ? $value['lng'] : null;
                $data['lat'] = isset($value['lat']) ? $value['lat'] : null;
                $data['create_time'] = date('Y:m:d H:i:s');
                $data['update_time'] = date('Y:m:d H:i:s');
                $addID[] = Loader::model('BuildingBase')->synchroBuildingBase($data);
            }
            return json(['code'=>200,'message'=>'同步成功','data'=>$addID]);
        }else{
            return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
        }
	}

    /**
     * @description 同步用户
     * @return \think\response\Json
     */
	public function synchronUserAction(){
        $param = $this->request->post();
        if($param){
            $addID = [];
            $param['data'] = !isset($param['data']) ? : '';
            $obj = json_decode($param['data'],true);
            foreach($obj as $key=>$value){
                $data['wofang_id'] = isset($value['id']) ? $value['id'] : null;
                $data['real_name'] = isset($value['trueName']) ? $value['trueName'] : null;
                $data['phone'] = isset($value['tel']) ? $value['tel'] : null;
                $data['department_id'] = isset($value['deaperId']) ? $value['deaperId'] : null;
                $data['manager_type'] = isset($value['userType']) ? $value['userType'] : null;
                $data['base_user_id'] = isset($value['id']) ? $value['id'] : null;
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
            return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
        }
	}

    /**
     * @description 同步用车明细
     * @return \think\response\Json
     */
	public function synchronOutCarAction(){
        $param = $this->request->post();
        if($param){
            $addID = [];
            $param['data'] = !isset($param['data']) ? : '';
            $obj = json_decode($param['data'],true);
            foreach($obj as $key=>$value){
                $data['wofang_id'] = isset($value['id']) ? $value['id'] : null;
                $data['used_id'] = isset($value['userId']) ? $value['userId'] : null;
                $data['manager_id'] = isset($value['clientId']) ? $value['clientId'] : null;
                $data['customer_num'] = isset($value['clientNum']) ? $value['clientNum'] : null;
                $data['oil_cost'] = isset($value['oilCost']) ? $value['oilCost'] : null;
                $data['out_car_time'] = isset($value['outAt']) ? $value['outAt'] : null;
                $data['build_id'] = isset($value['buildingId']) ? $value['buildingId'] : null;
                $data['status'] = isset($value['status']) ? $value['status'] : null;
                $data['start_address'] = isset($value['carAddress']) ? $value['carAddress'] : null;
                $data['start_lng'] = isset($value['mapLng']) ? $value['mapLng'] : null;
                $data['start_lat'] = isset($value['mapLat']) ? $value['mapLat'] : null;
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
            return json(['code'=>404,'message'=>'同步失败','data'=>$param]);
        }
	}

}
