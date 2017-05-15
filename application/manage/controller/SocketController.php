<?php
/*
-----------------------------------
 代码解析
-----------------------------------
*/
namespace app\manage\controller;

use think\Controller;
use app\common\components\decode\SocketConnect;
use app\common\components\decode\SocketSwitch;

class SocketController extends Controller{

    public function startAction(){
        //设置连接信息
        $serviceSocket = SocketConnect::getInstance();
      //开启socket服务端
       $serviceSocket->start_server();
    }


}
