<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "{{%car}}".
 *
 * @property integer $id
 * @property string $create_time
 * @property string $SIM
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $car_type
 * @property string $lat
 * @property string $latlng_update_time
 * @property string $lng
 * @property string $number_plate
 * @property integer $seat_num
 * @property integer $city_id
 * @property string $address
 * @property double $orientation_angle
 *
 * @property City $city
 * @property RepairCar[] $repairCars
 * @property TakeCarOrder[] $takeCarOrders
 * @property Trajectory[] $trajectories
 */
class Car extends Model
{
    /**
     * 数据库表名
     * 加格式‘{{%}}’表示使用表前缀，或者直接完整表名
     * @author Sir Fu
     */
    protected $table = '{{%car}}';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'car_type', 'lat', 'latlng_update_time', 'lng', 'number_plate', 'seat_num', 'city_id', 'address'], 'required'],
            [['create_time', 'update_time', 'latlng_update_time'], 'safe'],
            [['is_delete', 'seat_num', 'city_id'], 'integer'],
            [['remark'], 'string'],
            [['orientation_angle'], 'number'],
//            [['car_type', 'lat', 'lng', 'number_plate', 'address'], 'string', 'max' => 255],
//            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'app', 'id',
            'SIM' => 'app', 'SIM标识',
            'create_time' => 'app', '创建时间',
            'is_delete' => 'app', '删除标识  默认值1',
            'remark' => 'app', '备注字段',
            'update_time' => 'app', '修改时间',
            'car_type' => 'app', '车型',
            'lat' => 'app', '经度(百度地图',
            'latlng_update_time' => 'app', '经纬度最后一次更新时间',
            'lng' => 'app', '纬度(百度地图',
            'number_plate' => 'app', '车牌号',
            'seat_num' => 'app', '座位数',
            'city_id' => 'app', 'City ID',
            'address' => 'app', '地址',
            'orientation_angle' => 'app', '方向角，车辆朝向',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getCity()
    {
        return $this->belongsTo('City','city_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getRepairCars()
    {
        return $this->hasMany('RepairCar', 'car_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'car_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTrajectories()
    {
        return $this->hasMany('Trajectory', 'car_id' , 'id');
    }

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
