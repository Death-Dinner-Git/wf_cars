<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_department".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property integer $isGroup
 * @property integer $level
 * @property string $name
 * @property integer $orders
 * @property integer $pid
 * @property integer $status
 * @property integer $wofang_id
 * @property string $lat
 * @property string $lng
 *
 * @property Manager[] $managers
 * @property TakeCarOrder[] $takeCarOrders
 */
class Department extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'isGroup', 'level', 'name', 'orders', 'pid', 'status'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'isGroup', 'level', 'orders', 'pid', 'status', 'wofang_id'], 'integer'],
            [['remark'], 'string'],
            [['name', 'lat', 'lng'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'app', 'ID',
            'create_time' => 'app', 'Create Time',
            'is_delete' => 'app', 'Is Delete',
            'remark' => 'app', 'Remark',
            'update_time' => 'app', 'Update Time',
            'isGroup' => 'app', 'Is Group',
            'level' => 'app', 'Level',
            'name' => 'app', 'Name',
            'orders' => 'app', 'Orders',
            'pid' => 'app', 'Pid',
            'status' => 'app', 'Status',
            'wofang_id' => 'app', '我房网第三方Id',
            'lat' => 'app', '经度(百度地图)',
            'lng' => 'app', '纬度(百度地图)',
        ];
    }

    /**
     * @description 获取全部办事处
     * @param $key
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public static function getAllDepartment($key = 'department')
    {
        $key = $key === 'department' ? :'department';
        $key = __METHOD__.'_'.$key;
        $res = Department::get()->cache(md5($key),1800)->select();
        return $res;
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getManagers()
    {
        return $this->hasMany('RepairCar', 'department_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'department_id' , 'id');
    }
}
