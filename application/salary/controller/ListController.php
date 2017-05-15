<?php
/*
-----------------------------------
 工资结算
-----------------------------------
*/
namespace app\salary\controller;
use app\common\controller\BaseController;
use phpDocumentor\Reflection\Types\Null_;
use think\Loader;
use think\Db;
use app\salary\model\OutCar;
use think\Request;
class ListController extends BaseController
{
    protected $listData;
    protected $page;
    protected $pageCount;
    protected $countSum;
    //当前操作的模型
    protected static $_currentModel;
    /**
     * @初始化
     * @return array
     **/
    public function _initialize(){
        //每页显示的笔数
        $this->pageCount = 10;
        self::$_currentModel = Loader::model('OutCar');
    }

    /**
     *
     * @工资结算
     * @return array
     **/
    public function ListAction($pageNumber = 1){
        //取得工资结算列表
        $where['OutCar.is_delete'] = 1;
        if(Request::instance()->isPost()){
            if(!empty($_POST['driverId'])){
                $where['Driver.driverId'] = $_POST['driverId'];
            }
            if(!empty($_POST['create_time_start'])){
                $where['takeCarOrder.start_time'] = array('egt',$_POST['create_time_start']);
            }
            if(!empty($_POST['create_time_end'])){
                $where['takeCarOrder.end_time'] = array('elt',$_POST['create_time_end']);
            }
        }
        $this->listData = $this->salaryDataAction($where,$pageNumber);
        //总数据
        $this->countSum = $this->salarySumAction($where);
        //司机列表
        $driverList = Loader::model('Driver')->lists();
        $this->page  = ceil($this->countSum/$this->pageCount);
        $this->assign('count',$this->page);
        $this->assign('driverList',$driverList);
        $this->assign('salary',$this->listData);
        return view('salaryList');
    }

    /*
     * 编辑工资结算表
     */
    public function detailAction($id=1){
        $where = ['OutCar.id'=>$id];
        $salaryList = $this->salaryFindAction($where);
        //保存
        if(Request::instance()->isPost()){
            $outCarId = Request::instance()->param('outcarId');
            $other_money = Request::instance()->param('other_money');
            $sum_money = Request::instance()->param('sum_money');
            $remark = Request::instance()->param('remark');
            //更新的字段
            $data = ['other_money'=>$other_money,
                     'sum_money'=>$sum_money,
                     'remark'=>$remark,
                     'update_time'=>date('Y-m-d H:i:s')
            ];
            // 过滤post数组中的非数据表字段数据
            $ValidateError = Loader::validate('OutCar');
            if(!$ValidateError->check($data)){
                return json(['data'=>NULL,'code'=>404,'message'=>$ValidateError->getError()]);
            }
            if(self::$_currentModel->save($data,['id'=>$outCarId])){
                return json(['data'=>url('list'),'code'=>200,'message'=>'更新成功']);
            }else{
                return json(['data'=>NULL,'code'=>404,'message'=>'更新失败']);
            }
        }
        $this->assign('salaryList',$salaryList);
        return view('salaryListUpdate');
    }

    /*
     * 工资结算列表数据
     * 传入页数，查询的条件
     * 返回查询的数据集
     */
    public function salaryDataAction($where=['OutCar.is_delete'=>1],$pageNumber=1){
        $list = Db::view('OutCar','id,sign_time,sign_mileage,other_money,sum_money,order_money,driverTime')->alias('a')
            ->view('takeCarOrder',['id'=>'takeId','driver_mileage'],'takeCarOrder.id=OutCar.take_car_order_id')
            ->view('Car','number_plate','Car.id=takeCarOrder.car_id')
            ->where($where)
            ->page($pageNumber,$this->pageCount)->order('OutCar.id desc')->select();
        return $list;
    }
    /*
     * 工资结算的总笔数
     * 传入查询条件
     * 返回数据集
     */
    public function salarySumAction($where=['OutCar.is_delete'=>1]){
        $list = Db::view('OutCar','id,sign_time,sign_mileage,other_money,sum_money,order_money')
            ->view('takeCarOrder',['id'=>'takeId'],'takeCarOrder.id=OutCar.take_car_order_id')
            ->view('Car','number_plate','Car.id=takeCarOrder.car_id')
            ->where($where)
            ->count();
        return $list;
    }

    /*
     * 工资结算单笔数据
     * 返回查询的数据集
     */
    public function salaryFindAction($where=['OutCar.is_delete'=>1]){
        $list = Db::view('OutCar','id,sign_time,sign_mileage,other_money,sum_money,order_money,driverTime,remark')
            ->view('takeCarOrder',['id'=>'takeId','driver_mileage'],'takeCarOrder.id=OutCar.take_car_order_id')
            ->view('Car','number_plate','Car.id=takeCarOrder.car_id')
            ->view('Manager','real_name','OutCar.manager_id=Manager.id')
            ->view('City',['name'=>'city_name'],'City.id=takeCarOrder.city_id')
            ->where($where)
            ->find();
        return $list;
    }

    public function salaryDataCeAction($where=['OutCar.is_delete'=>1],$pageNumber=1){
        $list = Db::view('OutCar','id,sign_time,sign_mileage,other_money,sum_money,order_money,driverTime')->alias('a')
            ->view('takeCarOrder',['id'=>'takeId','driver_mileage'],'takeCarOrder.id=OutCar.take_car_order_id')
            ->view('Car','number_plate','Car.id=takeCarOrder.car_id')
            ->where($where)
            ->page($pageNumber,$this->pageCount)->order('OutCar.id desc')->fetchSql(true)->select();
        return $list;
    }
}