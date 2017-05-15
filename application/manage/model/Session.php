<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_session".
 *
 * @property string $session_id
 * @property integer $session_expire
 * @property resource $session_data
 * @property integer $uid
 * @property integer $update_time
 */
class Session extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['session_id', 'session_expire', 'uid', 'update_time'], 'required'],
            [['session_expire', 'uid', 'update_time'], 'integer'],
            [['session_data'], 'string'],
            [['session_id'], 'string', 'max' => 255],
            [['session_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'session_id' => 'app', 'Session ID',
            'session_expire' => 'app', 'Session Expire',
            'session_data' => 'app', 'Session Data',
            'uid' => 'app', '用户ID',
            'update_time' => 'app', '更新时间',
        ];
    }
}
