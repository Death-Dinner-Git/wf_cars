<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_trajectory".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $lat
 * @property string $lng
 * @property integer $car_id
 *
 * @property Car $car
 */
class Trajectory extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'trajectory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'lat', 'lng', 'car_id'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'car_id'], 'integer'],
            [['remark'], 'string'],
            [['lat', 'lng'], 'string', 'max' => 255],
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
            'lat' => 'app', '经度(百度地图)',
            'lng' => 'app', '纬度(百度地图)',
            'car_id' => 'app', 'Car ID',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getCar()
    {
        return $this->belongsTo('Car','car_id','id');
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
