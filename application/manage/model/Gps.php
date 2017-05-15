<?php

namespace app\manage\model;

use app\common\model\Model;

class Gps extends Model {

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
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'gps';
    }

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


}