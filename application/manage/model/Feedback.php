<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_feedback".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property integer $level
 * @property integer $take_car_order_id
 *
 * @property TakeCarOrder $takeCarOrder
 * @property TakeCarOrder[] $takeCarOrders
 */
class Feedback extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'feedback';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'level'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'level', 'take_car_order_id'], 'integer'],
            [['remark'], 'string'],
//            [['take_car_order_id'], 'exist', 'skipOnError' => true, 'targetClass' => TakeCarOrder::className(), 'targetAttribute' => ['take_car_order_id' => 'id']],
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
            'level' => 'app', '评价级别',
            'take_car_order_id' => 'app', 'Take Car Order ID',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo|\think\model\relation\HasOne
     */
    public function getTakeCarOrder()
    {
        return $this->belongsTo('TakeCarOrder','take_car_order_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'feedback_id' , 'id');
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
