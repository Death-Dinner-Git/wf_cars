<?php
/*
-----------------------------------
 区域费用维护
-----------------------------------
*/
namespace app\regionprice\controller;
use app\common\controller\BaseController;
use phpDocumentor\Reflection\Types\Null_;
use think\Loader;
use think\Db;
use app\regionprice\model\Regionpricer;
use think\Request;
use think\Validate;
class ListController extends BaseController
{
    //区域时费
    protected $repairCarList;
    protected $page;
    protected $pageCount;
    protected $countSum;
    //当前操作的模型
    protected static $_currentModel;
    /**
     * @区域时费初始化
     * @return array
     **/
    public function _initialize(){
        //每页显示的笔数
        //显示最大的笔数 大于100应该执行分页了
        $this->pageCount = 100;
        self::$_currentModel = Loader::model('City');
    }

    /**
     * @区域时费列表
     * @return array
     **/
    public function ListAction($pageNumber = 1){
        //更新
        if(Request::instance()->isPost()){
            //成功与否的标识符
            $result = NULL;
            //数组的长度
            $dataLength = Request::instance()->param('dataLength');
            for($i=1;$i<=$dataLength;$i++){
                $cityId = 'id'.(string)$i;
                $cityId = Request::instance()->param($cityId);
                $timePriceName = 'timePrice'.(string)$i;
                $numPriceName = 'numPrice'.(string)$i;
                $timePrice = Request::instance()->param($timePriceName);
                $numPrice = Request::instance()->param($numPriceName);
                $citydate = date('Y-m-d H:i:s');
                $data = [
                    'update_time'=>$citydate,
                    'num_price'=>$numPrice,
                    'time_price'=>$timePrice
                ];
                // 验证数据的合法性
                $ValidateError = Loader::validate('RegionPrice');
                if(!$ValidateError->check($data)){
                    $result = $ValidateError->getError();
                    break;
                }
                //由于decimal类型值为空，会默认插入0 所以为空的值给NULL
                if($numPrice===''){
                    $numPrice = NULL;
                }
                if($timePrice===''){
                    $timePrice = NULL;
                }
                //先查询，没有更新的不走update
//                $results = NULL;
//                $where = ['id'=>$cityId,'num_price'=>$numPrice,'time_price'=>$timePrice];
//                $results = self::$_currentModel->where($where)->find();
//                if($results){
//                    continue;
//                }
                $data = [
                    'update_time'=>$citydate,
                    'num_price'=>$numPrice,
                    'time_price'=>$timePrice
                ];
                $updateData = self::$_currentModel->save($data,['id'=>$cityId]);
                if($updateData === false){
                    $result = '更新失败';
                    break;
                }
            }
            if(!$result){
                $result = '更新成功';
            }
            echo "<script>alert('".$result."')</script>";
        }
        //取得区域费时列表
        $this->regionpriceList = Db::view('City','id,is_delete,name,num_price,time_price')
            ->where('City.is_delete','=',1)
            ->page($pageNumber,$this->pageCount)->order('City.id')->select();
        $this->assign('regionpriceData',$this->regionpriceList);
        return view('regionPriceList');
    }


}