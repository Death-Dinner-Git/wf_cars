<?php

namespace app\manage\controller;

use think\Controller;
use \app\common\components\decode\Decode;

/**
 * @description The module Index base controller
 * Class Common extends \think\Controller
 */
class DecodeController extends Controller
{

    /**
     * @description before action function
     */
    protected function _initialize()
    {

    }

    /**
     * @description 解析
     * @author Sir Fu
     */
    public function decodeAction()
    {
        $data = file_get_contents(ROOT_PATH.'public/static/socket/data.log');
        $hex= \app\common\components\decode\DecodeHelper::StrToBin($data);
        file_put_contents(ROOT_PATH.'public/static/socket/hexData.log',$hex);
        echo '<pre>';
        $result = Decode::run($hex,false,false);
        var_dump($result);
        var_dump(Decode::$_allType);
        echo '</pre>';

//        $model = new Decode();
//        foreach ($model->decryptData($hex) as $item){
//            $result = $model->decrypt($item);
//            if ($result !== false && is_array($result)){
//                $className = isset($result['className']) ? $result['className'] : false;
//                $pack = isset($result['packTrans']) ? $result['packTrans'] : [];
//                $model->save($className,$pack,$result);
//            }
//        }
        return '';
    }

    /**
     * @description 解析
     * @author Sir Fu
     */
    public function startAction()
    {
        //设置连接信息
        $serviceSocket = \app\common\components\decode\SocketConnect::getInstance();
        //开启socket服务端
        $serviceSocket->start_server();
    }

}