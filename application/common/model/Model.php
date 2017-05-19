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
use think\Loader;

/**
 * 公共模型(基于TP5新版Model)
 * @author Dinner
 */
class Model extends \think\Model
{

    /**
     * @return mixed
     */
    public static function getTablePrefix(){
        return config('database.prefix');
    }

    /**
     * 实例化（分层）模型
     * @param string $name         Model名称
     * @param string $layer        业务层名称
     * @param bool   $appendSuffix 是否添加类名后缀
     * @param string $common       公共模块名
     * @return Object | \think\Model
     * @throws \think\exception\ClassNotFoundException
     */
    public static function load($name = '', $layer = 'model', $appendSuffix = false, $common = 'common')
    {
        if ($name === ''){
            $name = get_called_class();
        }
        return Loader::model($name,$layer,$appendSuffix,$common);
    }

}
