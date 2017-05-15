<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: Sir Fu
// +----------------------------------------------------------------------
namespace app\common\model;

use think\Db;

/**
 * 公共模型(基于TP5新版Model)
 * @author Sir Fu
 */
class Model extends \think\Model
{

    /**
     * @return mixed
     */
    public static function getTablePrefix(){
        return config('database.prefix');
    }

}
