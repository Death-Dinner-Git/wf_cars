<?php
/*
-----------------------------------
 抢单管理
-----------------------------------
*/
namespace app\car\controller;
use app\common\controller\BaseController;
use think\Loader;
use think\Request;

use app\car\model\TakeCarOrder;
use app\car\model\Car;
use app\car\model\City;
use app\car\model\Manager;
use app\car\model\Driver;
use app\car\model\BuildingBase;
use app\car\model\Department;

class TakecarorderController extends BaseController{

	protected static $_currentModel;//当前操作模型
	protected $_DriverList;
	protected $_DepartmentList;
		
	/**
	* @初始化
	* @return array
	**/
	protected function _initialize(){
		parent::_initialize();
		self::$_currentModel = Loader::model('TakeCarOrder');
		//获取司机、部门
		$this->_DriverList = Loader::model('Driver')->lists($pageNumber='',$totalNumber='',$where='');
		$this->_DepartmentList = Loader::model('Department')->lists($pageNumber='',$totalNumber='',$where='');
	}


    /**
     * @description 打车列表
     * @param int $pageNumber
     * @param int $totalNumber
     * @param null $numberPlate
     * @param null $managerName
     * @param null $cityid
     * @param null $driverId
     * @param null $status
     * @param null $create_time_start
     * @param null $create_time_end
     * @return mixed
     */
    public function listAction($pageNumber=1,$totalNumber=10,$numberPlate = null,$managerName = null, $cityid = null,$driverId = null, $status = null, $create_time_start = null, $create_time_end = null)
    {

        $each = 10;
        $where = ['t.is_delete'=>'1'];
        $param = [
            'numberPlate'=>'',
            'managerName'=>'',
            'cityid'=>'',
            'driverId'=>'',
            'status'=>'',
            'create_time_start'=>'',
            'create_time_end'=>'',
        ];
        $join = [
            [[Manager::tableName()=>'m'],['t.manager_id = m.id']],
            [[Department::tableName()=>'de'],['m.department_id = de.id']],
            [[BuildingBase::tableName()=>'b'],['t.building_base_id = b.id'],'LEFT'],
            [[Car::tableName()=>'ca'],['t.car_id = ca.id'],'LEFT'],
            [[City::tableName()=>'ci'],['t.city_id = ci.id'],'LEFT'],
            [[Driver::tableName()=>'dr'],['t.driver_id = dr.id'],'LEFT'],
        ];
        $fields = [
            'm.*,m.id as managerid,m.real_name as manager_name',
            'de.*,de.id as departid,de.name as department_name',
            't.*,t.id as orderid',
            'b.*,b.id as buildid,b.name as build_name',
            'ca.*',
            'ci.*',
            'dr.*,dr.id as driverid,dr.real_name as driver_name',
        ];

        $field = implode(',',$fields);
        $query = TakeCarOrder::load()->alias('t');

        if ($numberPlate && $numberPlate != ''){
            $query = $query->where('ca.number_plate',$numberPlate);
            $param['numberPlate'] = $numberPlate;
        }

        if ($managerName && $managerName != ''){
            $query = $query->where('m.real_name',$managerName);
            $param['managerName'] = $managerName;
        }

        if ($cityid && $cityid != ''){
            $query = $query->where('t.city_id',$cityid);
            $param['cityid'] = $cityid;
        }

        if ($driverId && $driverId != ''){
            $query = $query->where('dr.driver_id',$driverId);
            $param['driverId'] = $driverId;
        }

        if ($status && $status != ''){
            $statuses = 'cancel';
            if ($status == '1'){
                $query = $query->where('t.take_car_order_id',null);
            }else{
                $query = $query->where('t.order_status','not in', $statuses);
            }
            $param['status'] = $status;
        }
        $newStart = null;
        if (strtotime($create_time_start) && $create_time_start !== null && $create_time_start !== '') {
            $newStart = date('Y-m-d H:i:s', strtotime($create_time_start));
            $query = $query->where('t.out_car_time', '>=', $newStart);
            $query = $query->where('t.out_car_time', '>=', $newStart);
            $param['create_time_start'] = $create_time_start;
        }
        $newEnd = null;
        if (strtotime($create_time_end) && $create_time_end !== null && $create_time_end !== '') {
            $newEnd = date('Y-m-d H:i:s', strtotime($create_time_end));
            $query = $query->where('t.out_car_time', '<=', $newEnd);
            $query = $query->where('t.out_car_time', '<=', $newEnd);
            $param['create_time_end'] = $create_time_end;
        }

        $query = $query->join($join)->where($where);
        $query = $query->field($field);


        $pageQuery = clone $query;
        $count = ceil(($pageQuery->count())/$each);
        $dataProvider = $query->page($pageNumber,$each)->order('t.create_time','DESC')->select();


        $this->assign('front',$param);
        $this->assign('TakeCarOrderList',$dataProvider);//列表
        $this->assign('count',$count);//页码

        $this->assign('driver',$this->_DriverList);//司机
        $this->assign('department',$this->_DepartmentList);//部门
        return $this->fetch();
    }


    /**
	* @打车列表
	* @return array
	**/
	public function listsAction($pageNumber=1,$totalNumber=10){
		
		$where = array();
		$front = array('numberPlate'=>'','managerName'=>'','driverId'=>'','departmentid'=>'','orderstatus'=>'','ordertype'=>'','ordertype'=>'','create_time_start'=>'','create_time_end'=>'');
		if(Request::instance()->isGet()){
			if(!empty($_GET['numberPlate'])){
				$where['e.number_plate'] = $_GET['numberPlate'];
				$front['numberPlate'] = $_GET['numberPlate'];
			}
			if(!empty($_GET['managerName'])){
				$where['c.real_name'] = $_GET['managerName'];
				$front['managerName'] = $_GET['managerName'];
			}
			if(!empty($_GET['driverId'])){
				$where['f.id'] = $_GET['driverId'];
				$front['driverId'] = $_GET['driverId'];
			}
			if(!empty($_GET['departmentid'])){
				$where['b.id'] = $_GET['departmentid'];
				$front['departmentid'] = $_GET['departmentid'];
			}
			if(!empty($_GET['orderstatus'])){
				$where['a.order_status'] = $_GET['orderstatus'];
				$front['orderstatus'] = $_GET['orderstatus'];
			}
			if(!empty($_GET['ordertype'])){
				$where['a.order_type'] = $_GET['ordertype'];
				$front['ordertype'] = $_GET['ordertype'];
			}
			if(!empty($_GET['create_time_start'])){
				$where['a.start_time'] = array('egt',$_GET['create_time_start']);
				$front['create_time_start'] = $_GET['create_time_start'];
			}
			if(!empty($_GET['create_time_end'])){
				$where['a.end_time'] = array('elt',$_GET['create_time_end']);
				$front['create_time_end'] = $_GET['create_time_end'];
			}
		}


		$TakeCarOrderList = self::$_currentModel->lists($pageNumber,$totalNumber,$where);
		$count = self::$_currentModel->totalCount($where,$totalNumber);
		
		$this->assign('TakeCarOrderList',$TakeCarOrderList);//列表
		$this->assign('count',$count);//页码

		$this->assign('driver',$this->_DriverList);//司机
		$this->assign('department',$this->_DepartmentList);//部门
		$this->assign('front',$front);//筛选条件
		
		return $this->fetch();
	}

	/**
	*@查看行驶记录
	*@return array
	**/
	public function showrecordAction(){
		$id = Request::instance()->param('id');
		$result = self::$_currentModel->takeOrderShow($id);
		$this->assign('record',$result);
		return $this->fetch();
	}

}
