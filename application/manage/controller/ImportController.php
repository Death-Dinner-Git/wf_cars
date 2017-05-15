<?php

namespace app\manage\controller;

use think\Controller;
use \app\common\components\Helper;
use \app\manage\model\Car;

/**
 * @description The module Index base controller
 * Class Common extends \think\Controller
 */
class ImportController extends Controller
{

    /**
     * @description before action function
     */
    protected function _initialize()
    {

    }

    /**
     * @description SIM
     * @author Sir Fu
     */
    public function importAction()
    {
        $fileName = ROOT_PATH.'public/sim_type.xls';

        $result = Helper::importByPhpExcel($fileName);
        $data = isset($result[0]) ? $result[0] : [];

        foreach ($data as $key => $value){
            if ($key== '1'){
                continue;
            }
            $model = Car::get()->where(['number_plate'=>$value['B']])->find();
            if ($model){
                $model->SIM = $value['D'];
//                $model->isUpdate(true)->save();
            }
        }

        exit();
    }
}