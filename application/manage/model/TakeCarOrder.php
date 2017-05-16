<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_take_car_order".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $booking_time
 * @property integer $driver_mileage
 * @property integer $driver_time
 * @property string $end_lat
 * @property string $end_lng
 * @property string $end_time
 * @property string $order_status
 * @property string $order_type
 * @property integer $out_car_id
 * @property string $out_car_time
 * @property string $start_lat
 * @property string $start_lng
 * @property string $start_time
 * @property integer $VERSION_NUM
 * @property integer $car_id
 * @property integer $driver_id
 * @property integer $feedback_id
 * @property integer $manager_id
 * @property string $end_address
 * @property string $order_num
 * @property string $start_address
 * @property string $num_price
 * @property string $order_money
 * @property string $time_price
 * @property integer $city_id
 * @property integer $department_id
 * @property integer $building_base_id
 * @property double $salesMileage_mileage
 * @property double $driver_start_mileage
 * @property integer $customerNum
 *
 * @property Feedback[] $feedbacks
 * @property OutCar[] $outCars
 * @property Feedback $feedback
 * @property City $city
 * @property Car $car
 * @property Manager $manager
 * @property Department $department
 * @property BuildingBase $buildingBase
 */
class TakeCarOrder extends Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'take_car_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'booking_time', 'order_status', 'order_type', 'start_lat', 'start_lng', 'VERSION_NUM', 'manager_id', 'order_num', 'start_address'], 'required'],
            [['create_time', 'update_time', 'booking_time', 'end_time', 'out_car_time', 'start_time'], 'safe'],
            [['is_delete', 'driver_mileage', 'driver_time', 'out_car_id', 'VERSION_NUM', 'car_id', 'driver_id', 'feedback_id', 'manager_id', 'city_id', 'department_id', 'building_base_id', 'customerNum'], 'integer'],
            [['remark'], 'string'],
            [['num_price', 'order_money', 'time_price', 'salesMileage_mileage', 'driver_start_mileage'], 'number'],
            [['end_lat', 'end_lng', 'order_status', 'order_type', 'start_lat', 'start_lng', 'end_address', 'order_num', 'start_address'], 'string', 'max' => 255],
//            [['feedback_id'], 'exist', 'skipOnError' => true, 'targetClass' => Feedback::className(), 'targetAttribute' => ['feedback_id' => 'id']],
//            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
//            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Car::className(), 'targetAttribute' => ['car_id' => 'id']],
//            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::className(), 'targetAttribute' => ['manager_id' => 'id']],
//            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::className(), 'targetAttribute' => ['department_id' => 'id']],
//            [['building_base_id'], 'exist', 'skipOnError' => true, 'targetClass' => BuildingBase::className(), 'targetAttribute' => ['building_base_id' => 'id']],
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
            'booking_time' => 'app', '预约出行时间',
            'driver_mileage' => 'app', '行驶公里数',
            'driver_time' => 'app', '行驶分钟数',
            'end_lat' => 'app', '终点经度(百度地图)',
            'end_lng' => 'app', '终点纬度(百度地图)',
            'end_time' => 'app', '结束时间',
            'order_status' => 'app', '订单状态',
            'order_type' => 'app', '订单类型',
            'out_car_id' => 'app', '出车单id',
            'out_car_time' => 'app', '下车时间',
            'start_lat' => 'app', '始发经度(百度地图)',
            'start_lng' => 'app', '始发纬度(百度地图)',
            'start_time' => 'app', '开始时间',
            'VERSION_NUM' => 'app', '版本号',
            'car_id' => 'app', 'Car ID',
            'driver_id' => 'app', 'Driver ID',
            'feedback_id' => 'app', 'Feedback ID',
            'manager_id' => 'app', 'Manager ID',
            'end_address' => 'app', '目的地',
            'order_num' => 'app', '订单编号',
            'start_address' => 'app', '始发地',
            'num_price' => 'app', '每次单价',
            'order_money' => 'app', '订单总金额',
            'time_price' => 'app', '每小时单价',
            'city_id' => 'app', 'City ID',
            'department_id' => 'app', 'Department ID',
            'building_base_id' => 'app', 'Building Base ID',
            'salesMileage_mileage' => 'app', '销售顾问填写公里数',
            'driver_start_mileage' => 'app', '行驶公里数-开始',
            'customerNum' => 'app', '人数',
        ];
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getFeedbacks()
    {
        return $this->hasMany('Feedback', 'take_car_order_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOutCars()
    {
        return $this->hasMany('OutCar', 'take_car_order_id' , 'id');
    }

    /**
     * @return \think\model\relation\BelongsTo|\think\model\relation\HasOne
     */
    public function getFeedback()
    {
        return $this->belongsTo('Feedback','feedback_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getCity()
    {
        return $this->belongsTo('City','city_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getCar()
    {
        return $this->belongsTo('Car','car_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getDriver()
    {
        return $this->belongsTo('Driver','driver_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getManager()
    {
        return $this->belongsTo('Manager','manager_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getDepartment()
    {
        return $this->belongsTo('Department','department_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getBuildingBase()
    {
        return $this->belongsTo('BuildingBase','building_base_id','id');
    }

    /**
     * @description 获取附近的车辆
     * @param int $lat 纬度
     * @param int $lng 经度
     * @return int
     */
    public static function getNearby($lat = 0,$lng = 0)
    {
        $lat = (int)$lat;
        $lng = (int)$lng;
        if(empty($lat) || empty($lng) || $lat <=0 || $lng <= 0){
            return 0;
        }
        $distinct = 6;
        $latNum = number_format($distinct/111,6,".","");
        $lngNum = number_format($distinct/(111*cos($lat)),6,".","");
        return Car::load()->where('lat','>=',($lat-$latNum))->where('lat','<=',($lat+$latNum))->where('lng','>=',($lng-$lngNum))->where('lng','<=',($lng+$lngNum))->count();
    }

    /**
     * @description 获取车辆接单次数
     * @param int $carId  car id
     * @return int
     */
    public static function getOrderByCarId($carId = 0)
    {
        $carId = (int)$carId;
        if(empty($carId) || $carId <=0 ){
            return 0;
        }
        return TakeCarOrder::load()->where('car_id',$carId)->count();
    }
}
