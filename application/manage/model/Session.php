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
