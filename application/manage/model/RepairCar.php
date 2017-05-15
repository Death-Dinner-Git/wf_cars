<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_repair_car".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $end_time
 * @property string $fee
 * @property string $project
 * @property string $reason
 * @property string $repairType
 * @property string $shop_name
 * @property string $start_time
 * @property integer $car_id
 * @property integer $city_id
 * @property integer $manager_id
 *
 * @property City $city
 * @property Manager $manager
 * @property Car $car
 */
class RepairCar extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'repair_car';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'end_time', 'repairType', 'start_time', 'car_id', 'city_id', 'manager_id'], 'required'],
            [['create_time', 'update_time', 'end_time', 'start_time'], 'safe'],
            [['is_delete', 'car_id', 'city_id', 'manager_id'], 'integer'],
            [['remark', 'reason'], 'string'],
            [['fee'], 'number'],
            [['project', 'repairType', 'shop_name'], 'string', 'max' => 255],
//            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'id']],
//            [['manager_id'], 'exist', 'skipOnError' => true, 'targetClass' => Manager::className(), 'targetAttribute' => ['manager_id' => 'id']],
//            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Car::className(), 'targetAttribute' => ['car_id' => 'id']],
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
            'end_time' => 'app', '维护结束时间',
            'fee' => 'app', '维护费用',
            'project' => 'app', '维护项目',
            'reason' => 'app', '维护原因',
            'repairType' => 'app', '维护类型',
            'shop_name' => 'app', '维护店名称',
            'start_time' => 'app', '维护开始时间',
            'car_id' => 'app', 'Car ID',
            'city_id' => 'app', 'City ID',
            'manager_id' => 'app', 'Manager ID',
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
     * @return \think\model\relation\BelongsTo
     */
    public function getManager()
    {
        return $this->belongsTo('Manager','manager_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getCar()
    {
        return $this->belongsTo('Car','car_id','id');
    }
}
