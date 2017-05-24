<?php

namespace app\manage\model;

use app\common\model\Model;

class Gps extends Model
{

    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%gps}}';

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'SIM',
        'alarm_flag',
        'status',
        'lng',
        'lat',
        'high',
        'speed',
        'orientation',
        'create_time',
        'remark',
        'is_delete',
        'update_time',
    ];

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [];



    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return CarValidate::load();
    }

    /**
     * @param $data
     * @param string $scene
     * @return bool
     */
    public static function check($data,$scene = ''){
        $validate = self::getValidate();

        //设定场景
        if (is_string($scene) && $scene !== ''){
            $validate->scene($scene);
        }

        return $validate->check($data);
    }
}