<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
// | 版权申明：零云不是一个自由软件，是零云官方推出的商业源码，严禁在未经许可的情况下
// | 拷贝、复制、传播、使用零云的任意代码，如有违反，请立即删除，否则您将面临承担相应
// | 法律责任的风险。如果需要取得官方授权，请联系官方http://www.lingyun.net
// +----------------------------------------------------------------------
namespace app\manage\controller;


use app\manage\controller\ManageController;
use app\manage\model\Car;
use app\manage\model\TakeCarOrder;
use app\manage\model\OutCar;
use app\manage\model\City;
use app\manage\model\Driver;
use app\manage\model\Department;
use think\Route;

class TravelController extends ManageController
{

    /**
     * @description 清单
     * @param int $pageNumber
     * @param string $department
     * @param int $startTime
     * @param int $endTime
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function listAction($pageNumber = 1,$department = '',$startTime = null, $endTime = null)
    {
        $each = 10;
        $query = TakeCarOrder::load()->alias('t');
        if ($department != ''){
            $query = $query->join([Department::tableName()=>'d'],['t.department_id = d.id'])->where('d.id',$department);
        }
        $newStart = null;
        if (strtotime($startTime) && $startTime !== null && $startTime !== '') {
            $newStart = date('Y-m-d H:i:s', strtotime($startTime));
            $query = $query->where('t.start_time', '>=', $newStart);
        }
        $newEnd = null;
        if (strtotime($endTime) && $endTime !== null && $endTime !== '') {
            $newEnd = date('Y-m-d H:i:s', strtotime($endTime));
            $query = $query->where('t.end_time', '<=', $newEnd);
        }

        $pageQuery = clone $query;
        $count = ceil(($pageQuery->count())/$each);
        $dataProvider = $query->page($pageNumber,$each)->order('t.update_time','DESC')->select();

        $out = rand(100,150);
        $driver = rand(150,250);
        $wait = rand(500,650);
        $this->assign('meta_title', "行车记录");
        $this->assign('out', $out);
        $this->assign('driver', $driver);
        $this->assign('wait', $wait);
        $this->assign('departmentList', Department::getAllDepartment());
        $this->assign('department', $department);
        $this->assign('startTime', $newStart);
        $this->assign('endTime', $newEnd);
        $this->assign('count', $count);
        $this->assign('dataProvider', $dataProvider);
        return view('travel/list');
    }


    /**
     * @description
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     */
    public function showCarAction()
    {
        $this->assign('meta_title', "全车展示");
        return view('travel/showCar');
    }

    /**
     * @description
     * @param  $id
     * @return string
     */
    public function liveAddressAction($id)
    {
        $startLng = 110;
        $startLat = 20.125;
        $endLng = 110;
        $endLat = 20.125;
        $dataProvider = TakeCarOrder::load()->where('car_id',$id)->order('update_time','DESC')->limit(1)->select();
        foreach($dataProvider as $key=>$value){
            $startLng = $value->start_lng;
            $startLat = $value->start_lat;
            $endLng = $value->getCar->lng;
            $endLat = $value->getCar->lat;
        }
        $this->assign('meta_title', "实时位置");
        $this->assign('startLng', $startLng);
        $this->assign('startLat', $startLat);
        $this->assign('endLng', $endLng);
        $this->assign('endLat', $endLat);
        $this->assign('id', $id);
        return view('travel/liveAddress');
    }

    /**
     * @description
     * @return \think\response\Json
     */
    public function carLocalAction()
    {
        $car = [];
        $status = ['cancel'=>'未派车','ordered'=>'抢单中','over'=>'未签到','drivering'=>'行驶中','waitOrder'=>'等待抢单'];
        $dataProvider = TakeCarOrder::load()->order('update_time','DESC')->order('car_id','ASC')->group('car_id')->limit(300)->select();
        foreach($dataProvider as $key=>$value){
            $tmp = [];
            $tmp['id'] = $value->id;
            $tmp['userId'] = $value->car_id;
            $tmp['carId'] = $value->car_id;
            $tmp['userName'] = 'XX师傅';
            $tmp['numberPlate'] = $value->getCar->number_plate;
            $tmp['endAddress'] = $value->end_address;
            $tmp['building_name'] = $value->getBuildingBase->name;
            $tmp['latlngUpdateTime'] = $value->getCar->latlng_update_time;
            $tmp['lng'] = $value->getCar->lng;
            $tmp['lat'] = $value->getCar->lat;
            $tmp['statusZH'] = $status[$value->order_status];
            $tmp['status'] = $value->order_status;
            $car[] = $tmp;
        }
        return json(['code'=>'1','result'=>$car]);
    }

    /**
     * @description
     * @param int $driverId
     * @param int $time
     * @return \think\response\Json
     */
    public function carInfoAction($driverId = 0, $time = 0)
    {
        $res = ['code'=>'0'];
        $driverId = intval($driverId);
        $time = intval($time);
        if ($driverId <=0 || $time <= 0){
            return json($res);
        }
        $car = [];
        $status = ['cancel'=>'未派车','ordered'=>'抢单中','over'=>'未签到','drivering'=>'行驶中','waitOrder'=>'等待抢单'];
        $dataProvider = TakeCarOrder::load()->where('car_id',$driverId)->order('update_time','DESC')->limit(1)->select();
        foreach($dataProvider as $key=>$value){
            $tmp = [];
            $tmp['id'] = $value->id;
            $tmp['userId'] = $value->driver_id;
            $tmp['carId'] = $value->car_id;
            $tmp['userName'] = 'XX师傅';
            $tmp['numberPlate'] = $value->getCar->number_plate;
            $tmp['endAddress'] = $value->end_address;
            $tmp['building_name'] = $value->getBuildingBase? $value->getBuildingBase->name : '';
            $tmp['latlngUpdateTime'] = $value->getCar->latlng_update_time;
            $tmp['lng'] = $value->getCar->lng;
            $tmp['lat'] = $value->getCar->lat;
            $tmp['statusZH'] = $status[$value->order_status];
            $tmp['status'] = $value->order_status;
            $car[] = $tmp;
        }
        return json(['code'=>'1','result'=>$car]);
    }


    /**
     * @description
     * @param  $id
     * @param  $time
     * @return string
     */
    public function lineAction($id = 0,$time = 0)
    {
        if (request()->isAjax()){
            $ret = ['code'=>'1','result'=>[],'time'=>time().'000'];

            $query = \app\manage\model\Gps::load()->where('SIM','1064849399578')->order('update_time','ASC');
            if (!empty($time)){
                $ret['time'] = $time;
                $time = date('Y-m-d H:i:s',strtotime(substr($time,0,10)));
                $query->whereTime('update_time', '>=', $time);
            }
            $dataProvider = $query->limit(10)->select();
            if ($dataProvider){
                $result = [];
                foreach ($dataProvider as $key => $model){
                    $item = [];
                    $item['id'] = $model->id;
                    $item['lng'] = $model->lng;
                    $item['lat'] = $model->lat;
                    $result[] = $item;
                }
                $ret['result'] = $result;
            }
            return json($ret);
        }

        $startLng = 110;
        $startLat = 20.125;
        $endLng = 110;
        $endLat = 20.125;
        $start = \app\manage\model\Gps::load()->where('SIM','1064849399538')->order('update_time','ASC')->limit(1)->select();
        $end = \app\manage\model\Gps::load()->where('SIM','1064849399538')->order('update_time','DESC')->limit(1)->select();
        foreach($start as $key=>$value){
            $startLng = $value->lng;
            $startLat = $value->lat;
        }
        foreach($end as $key=>$value){
            $endLng = $value->lng;
            $endLat = $value->lat;
        }
        $this->assign('meta_title', "实时");
        $this->assign('startLng', $startLng);
        $this->assign('startLat', $startLat);
        $this->assign('endLng', $endLng);
        $this->assign('endLat', $endLat);
        $this->assign('id', $id);
        return view('travel/line');
    }
}
