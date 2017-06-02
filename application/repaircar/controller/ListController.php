<?php
/*
-----------------------------------
 车辆维护
-----------------------------------
*/
namespace app\repaircar\controller;
use app\car\model\Car;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;
use think\Db;
class ListController extends BaseController
{
    //车辆维护分页
    protected $repairCarList;
    protected $page;
    protected $pageCount;
    protected $countSum;
    //当前操作的模型
    protected static $_currentModel;
    /**
     * @车辆维护初始化
     * @return array
     **/
    public function _initialize(){
        //每页显示数据
        $this->pageCount = 10;
        self::$_currentModel = Loader::model('RepairCar');
    }

    /**
     * @车辆维护列表
     * @return array
     **/
    public function ListAction($pageNumber = 1){
        $where['RepairCar.is_delete'] = 1;
        //查询
        $number_plate = '';
        $city_id = '';
        $start_time = '';
        $end_time = '';
        if (request()->request('numberPlate')){
            $key = trim(request()->request('numberPlate'));
            $where['Car.number_plate'] = $key;
            $number_plate = $key;
        }
        if (request()->request('cityId')){
            $key = trim(request()->request('cityId'));
            $where['City.id'] = $key;
            $city_id = $key;
        }
        if (request()->request('create_time_start')){
            $key = trim(request()->request('create_time_start'));
            $where['RepairCar.start_time'] = array('egt',$key);
            $start_time = $key;
        }
        if (request()->request('create_time_end')){
            $key = trim(request()->request('create_time_end'));
            $where['RepairCar.end_time'] = array('elt',$key);
            $end_time = $key;
        }
        //取得车辆维护管理列表
        $this->repairCarList = Db::view('RepairCar',"id,start_time,end_time,fee,project,reason,repairType,shop_name")
            ->view('City',['name'=>'cityName'],'City.id=RepairCar.city_id')
            ->view('Car','number_plate','Car.id=RepairCar.car_id')
            ->view('Manager','real_name','Manager.id=RepairCar.manager_id')
            ->where($where)
            ->page($pageNumber,10)->order('start_time desc')->select();
        //总数据
        $this->countSum = Db::view('RepairCar','id')
            ->view('City',['id'=>'cityId'],'City.id=RepairCar.city_id','LEFT')
            ->view('Car',['id'=>'carId'],'Car.id=RepairCar.car_id','LEFT')
            ->view('Manager',['id'=>'managerId'],'Manager.id=RepairCar.manager_id','LEFT')
            ->where($where)
            ->count();
        $this->page  = ceil($this->countSum/$this->pageCount);
        $repairCarData = $this->repairCarList;
        for($i=0;$i<count($repairCarData);$i++){
            $repairCarData[$i]['repairType'] = $this->repairTypeTrans($repairCarData[$i]['repairType']);
        }
        //查询的条件
        $this->assign('number_plate',$number_plate);
        $this->assign('city_id',$city_id);
        $this->assign('start_time',$start_time);
        $this->assign('end_time',$end_time);
        //城市
        $cityList = Loader::model('City')->lists();
        $this->assign('count',$this->page);
        $this->assign('repairCarData',$repairCarData);
        $this->assign('cityListData',$cityList);
        return view('repairCarList');
    }

    /*
     * 维护类型转换
     * return string
     */
    public function repairTypeTrans($repairType){
        switch($repairType){
            case 'regular':
                return '定期维护';
                break;
            default:
                return '其他维护';
                break;
        }
    }

    /*
     * 添加车辆维护
     */
    public function addAction(){
        if(Request::instance()->isAjax()){
            $data = Request::instance()->param();
            $data['create_time'] = date('Y-m-d H:i:s');
            $data['update_time'] = date('Y-m-d H:i:s');
            //车辆列表
            $carList = Loader::model('Car')->lists();
            $this->assign('carData',$carList);
            $ValidateError = Loader::validate('RepairCar');
            if(!$ValidateError->check($data)){
                return json(['data'=>NULL,'code'=>405,'message'=>$ValidateError->getError()]);
            }
            if(self::$_currentModel->isUpdate(false)->allowField(true)->save($data)){
                return json(['data'=>url('list'),'code'=>200,'message'=>'添加成功']);
            }else{
                return json(['data'=>NULL,'code'=>404,'message'=>'添加失败']);
            }
        }
        else{
            //车辆列表
            $carList[0]['id'] = false;
            $carList[0]['number_plate'] = "选择车辆";
            $this->assign('carData',$carList);
        }
        //车辆维护列表
        $repairCarList = Loader::model('RepairCar')->lists();
        $this->assign('repairCarData',$repairCarList);
        //管理员列表
        $managerList = Loader::model('Manager')->lists();
        $this->assign('managerData',$managerList);
        //城市列表
        $cityList = Loader::model('City')->lists();
        $this->assign('cityData',$cityList);
        return $this->fetch("list/repairCarListAdd");
    }

    /*
     * 编辑车辆维护信息
     */
    public function detailAction($id=1){
        if(Request::instance()->isAjax()){
            $data = Request::instance()->param();
            $data['update_time'] = date('Y-m-d H:i:s');
            $ValidateError = Loader::validate('RepairCar');
            //更新的字段
            $updateData = [
                'update_time',
                'start_time',
                'end_time',
                'car_id',
                'city_id',
                'reason',
                'project',
                'fee',
                'shop_name',
                'manager_id'
            ];
            if(!$ValidateError->check($data)){
                return json(['data'=>NULL,'code'=>405,'message'=>$ValidateError->getError()]);
            }
            if(self::$_currentModel->isUpdate(true)->allowField($updateData)->save($data,['id'=>$data['repairId']])){
                return json(['data'=>url('list'),'code'=>200,'message'=>'编辑成功']);
            }else{
                return json(['data'=>NULL,'code'=>404,'message'=>'编辑失败']);
            }
        }
        $where=['RepairCar.is_delete'=>1,'RepairCar.id'=>$id];
        //取得车辆维护管理列表
        $this->repairCarList = Db::view('RepairCar',"id,start_time,end_time,fee,project,reason,repairType,shop_name")
            ->view('City',['name'=>'cityName','id'=>'cityId'],'City.id=RepairCar.city_id')
            ->view('Car',['number_plate','id'=>'carId'],'Car.id=RepairCar.car_id')
            ->view('Manager',['real_name','id'=>'managerId'],'Manager.id=RepairCar.manager_id')
            ->where($where)
            ->find();
        //城市列表
        $cityList = Loader::model('City')->lists();
        $this->assign('cityData',$cityList);
        //城市的车辆
        $carList = $this->chooseCaraAction($this->repairCarList['cityId']);
        $this->assign('carData',$carList);
        //管理员列表
        $managerList = Loader::model('Manager')->lists();
        $this->assign('managerData',$managerList);
        $this->assign('list',$this->repairCarList);
        return $this->fetch("list/repairCarListDetail");
    }
    public function chooseCarAction($cityid=1){
        if(Request::instance()->isPost()){
            $cityid = $_POST['id'];
        }
        $cityCarList = Loader::model('Car')->cityCar($cityid);
        if($cityCarList->isEmpty()){
            return json(['data'=>null,'code'=>404,'message'=>'此城市暂无车辆']);
        }else{
            return json(['data'=>$cityCarList,'code'=>200,'message'=>'获取成功']);
        }
    }
    //还不知道怎么解析json数据
    public function chooseCaraAction($cityid=1){
        if(Request::instance()->isPost()){
            $cityid = $_POST['id'];
        }
        $cityCarList = Loader::model('Car')->cityCar($cityid);
        if($cityCarList->isEmpty()){
            return NULL;
        }else{
            return $cityCarList;
        }
    }
    /**
     * 删除维护列表
     * return array
     **/
    public function deleteDataAction($id=0){
        if(Request::instance()->isAjax()){
            $id = Request::instance()->param('id');
            $result = self::$_currentModel->destroy(['id'=>$id]);
            if($result){
                return json(['data'=>url('list'),'code'=>200,'message'=>'删除成功']);
            }else{
                return json(['data'=>null,'code'=>404,'message'=>'删除失败']);
            }
        }
    }
}