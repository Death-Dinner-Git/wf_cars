<?php

namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\OutCarValidate;

/**
 * This is the model class for table "{{%out_car}}".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property integer $customer_num
 * @property string $oil_cost
 * @property string $out_car_time
 * @property integer $request_id
 * @property string $sign_time
 * @property integer $used_id
 * @property integer $wofang_id
 * @property integer $build_id
 * @property integer $end_city_id
 * @property integer $manager_id
 * @property integer $start_city_id
 * @property integer $take_car_order_id
 * @property double $back_mileage
 * @property double $from_mileage
 * @property string $back_date
 * @property string $back_org_date
 * @property double $back_org_mileage
 * @property string $other_money
 * @property string $sum_money
 * @property double $sign_mileage
 * @property string $start_address
 * @property string $start_lat
 * @property string $start_lng
 * @property string $driverTime
 * @property string $order_money
 * @property integer $pre_outCar_id
 *
 * @property BuildingBase $build
 * @property Manager $manager
 * @property TakeCarOrder $takeCarOrder
 * @property City $startCity
 * @property City $endCity
 */
class OutCar extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'out_car';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [
        ['out_car_time','require',],
        ['oil_cost','max:255',],
        ['start_address','max:255',],
        ['start_lat','max:255',],
        ['start_lng','max:255',],
    ];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'out_car_time'], 'required'],
            [['create_time', 'update_time', 'out_car_time', 'sign_time', 'back_date', 'back_org_date'], 'safe'],
            [['is_delete', 'customer_num', 'request_id', 'used_id', 'wofang_id', 'build_id', 'end_city_id', 'manager_id', 'start_city_id', 'take_car_order_id', 'pre_outCar_id'], 'integer'],
            [['remark'], 'string'],
            [['back_mileage', 'from_mileage', 'back_org_mileage', 'other_money', 'sum_money', 'sign_mileage', 'driverTime', 'order_money'], 'number'],
            [['oil_cost', 'start_address', 'start_lat', 'start_lng'], 'string', 'max' => 255],
//            [['build_id'], 'exist', 'skipOnError' => true, 'targetClass' => BuildingBase::className(), 'targetAttribute' => ['build_id' => 'id']],
//            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::className(), 'targetAttribute' => ['manager_id' => 'id']],
//            [['take_car_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => TakeCarOrder::className(), 'targetAttribute' => ['take_car_order_id' => 'id']],
//            [['start_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['start_city_id' => 'id']],
//            [['end_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['end_city_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' =>  'id',
            'create_time' =>  '创建时间',
            'is_delete' =>  '删除标识  默认值1',
            'remark' =>  '备注字段',
            'update_time' =>  '修改时间',
            'customer_num' =>  '客户人数',
            'oil_cost' =>  '加油费用',
            'out_car_time' =>  '出车时间',
            'request_id' =>  '派车要求id',
            'sign_time' =>  '签到时间',
            'used_id' =>  '派车用途id',
            'wofang_id' =>  '我房网第三方Id',
            'build_id' =>  'Build ID',
            'end_city_id' =>  'End City ID',
            'manager_id' =>  'Manager ID',
            'start_city_id' =>  'Start City ID',
            'take_car_order_id' =>  'Take Car Order ID',
            'back_mileage' =>  '返程行驶公里数',
            'from_mileage' =>  '去程行驶公里数',
            'back_date' =>  '还车时间',
            'back_org_date' =>  '还车时间',
            'back_org_mileage' =>  '回公司公里数',
            'other_money' =>  '其他费用',
            'sum_money' =>  '总费用',
            'sign_mileage' =>  '出车公里数',
            'start_address' =>  '始发地',
            'start_lat' =>  '始发经度(百度地图',
            'start_lng' =>  '始发纬度(百度地图',
            'driverTime' =>  '驾驶时间',
            'order_money' =>  '派车总费用',
            'pre_outCar_id' =>  '上一个派车单',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getBuild()
    {
        return $this->belongsTo('BuildingBase','build_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getManager()
    {
        return $this->belongsTo('Manager', 'manager_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getTakeCarOrder()
    {
        return $this->belongsTo('TakeCarOrder', 'take_car_order_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getStartCity()
    {
        return $this->belongsTo('City', 'start_city_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getEndCity()
    {
        return $this->belongsTo('City', 'end_city_id','id');
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return OutCarValidate::load();
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

    /**
     * @description 同步我房后台数据
     * @param $data
     * @return string|int
     */
    public function synchroOutCar($data){
        $validate = self::getValidate();

        //设定场景
        $validate->scene('sync');

        $identity = isset($data['wofang_id']) ? $data['wofang_id'] : '0';
        if($validate->check($data)){
            //插入
            $result = self::create($data);
            if ($result){
                $ret = !empty($identity) ? 'success-'.$identity : '0';
            }
        }else{
            //更新
            $validate->scene('syncUpdate');
            if($validate->check($data)){
                $where = ['wofang_id'=>(!empty($identity) ? $identity : '0')];
                $result = self::update($data,$where);
                if ($result){
                    $ret = !empty($identity) ? 'update-'.$identity : '0';
                }
            }
        }

        if (!isset($ret)){
            // 验证失败 输出提示信息
            $ret = !empty($identity) ? 'fail-'.$identity : '0';
        }

        return $ret;
    }

}
