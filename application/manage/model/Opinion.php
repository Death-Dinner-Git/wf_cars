<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_opinion".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property integer $base_id
 * @property string $content
 * @property integer $readed
 * @property integer $type
 */
class Opinion extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'opinion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'base_id', 'readed', 'type'], 'integer'],
            [['remark', 'content'], 'string'],
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
            'base_id' => 'app', '反馈者的baseId',
            'content' => 'app', '内容',
            'readed' => 'app', '是否阅读  0:未读  1:已读',
            'type' => 'app', '是否阅读  0:司机 1:销售顾问',
        ];
    }
}
