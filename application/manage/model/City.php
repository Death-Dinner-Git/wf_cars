<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $name
 * @property string $num_price
 * @property integer $sort
 * @property string $time_price
 * @property integer $wofang_id
 * @property integer $pid
 *
 * @property BuildingBase[] $buildingBases
 * @property Car[] $cars
 * @property OutCar[] $outCars
 * @property OutCar[] $outCars0
 * @property Regionprice[] $regionprices
 * @property RepairCar[] $repairCars
 * @property TakeCarOrder[] $takeCarOrders
 */
class City extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'name', 'sort'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'sort', 'wofang_id', 'pid'], 'integer'],
            [['remark'], 'string'],
            [['num_price', 'time_price'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'app', 'id',
            'create_time' => 'app', '创建时间',
            'is_delete' => 'app', '删除标识  默认值1',
            'remark' => 'app', '备注字段',
            'update_time' => 'app', '修改时间',
            'name' => 'app', '城市名称',
            'num_price' => 'app', '每次单价',
            'sort' => 'app', '排序',
            'time_price' => 'app', '每小时单价',
            'wofang_id' => 'app', '我房网第三方Id',
            'pid' => 'app', '上级城市',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getBuildingBases()
    {
        return $this->hasMany('BuildingBase', 'city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getCars()
    {
        return $this->hasMany('Car', 'city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOutCars()
    {
        return $this->hasMany('OutCar', 'start_city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOutCars0()
    {
        return $this->hasMany('OutCar', 'end_city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getRegionprices()
    {
        return $this->hasMany('Regionprice', 'city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getRepairCars()
    {
        return $this->hasMany('RepairCar', 'city_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'city_id' , 'id');
    }
}
