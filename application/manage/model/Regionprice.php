<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_regionprice".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $num_price
 * @property string $time_price
 * @property integer $city_id
 *
 * @property City $city
 */
class Regionprice extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'regionprice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'city_id'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'city_id'], 'integer'],
            [['remark'], 'string'],
            [['num_price', 'time_price'], 'number'],
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
            'create_time' => 'app', '创建时间',
            'is_delete' => 'app', '删除标识  默认值1',
            'remark' => 'app', '备注字段',
            'update_time' => 'app', '修改时间',
            'num_price' => 'app', '接单价',
            'time_price' => 'app', '每小时单价',
            'city_id' => 'app', 'City ID',
        ];
    }

    /**
     * @return \think\model\relation\BelongsTo|\think\model\relation\HasOne
     */
    public function getCity()
    {
        return $this->belongsTo('City','city_id','id');
    }
}
