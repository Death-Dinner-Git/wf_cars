<?php

namespace app\manage\model;

use app\common\model\Model;

/**
 * This is the model class for table "wf_driver".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $head_portrait
 * @property string $real_name
 * @property string $rongyun_token
 * @property string $sex
 * @property string $token
 * @property integer $base_user_id
 * @property string $driving_license
 * @property integer $level
 * @property integer $car_id
 * @property integer $city_id
 * @property integer $orderNum
 * @property string $status
 * @property integer $baseId
 * @property string $location
 * @property string $mobilephone
 * @property string $password
 */
class Driver extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'driver';
    }

    /**
     * @var string
     */
    protected $pk = 'id';

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [
        ['head_portrait', '0,255', '长度为1-32个字符', 'length'],
        ['real_name', '0,255', '长度为1-32个字符', 'length'],
        ['rongyun_token', '0,255', '长度为1-32个字符', 'length'],
        ['sex', '0,255', '长度为1-32个字符', 'length'],
        ['token', '0,255', '长度为1-32个字符', 'length'],
        ['driving_license', '0,255', '长度为1-32个字符', 'length'],
        ['status', '0,255', '长度为1-32个字符', 'length'],
        ['location', '0,255', '长度为1-32个字符', 'length'],
        ['mobilephone', '0,255', '长度为1-32个字符', 'length'],
        ['password', '0,255', '长度为1-32个字符', 'length'],
    ];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [];

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'app', 'ID',
            'create_time' => 'app', '创建时间',
            'is_delete' => 'app', '删除标识 默认值1',
            'remark' => 'app', '备注字段',
            'update_time' => 'app', '修改时间',
            'head_portrait' => 'app', '用户头像',
            'real_name' => 'app', '姓名',
            'rongyun_token' => 'app', '融云token',
            'sex' => 'app', 'Sex',
            'token' => 'app', '用户 APP端专用token UUID随机字符',
            'base_user_id' => 'app', 'Base User ID',
            'driving_license' => 'app', '驾照类型',
            'level' => 'app', '司机评价级别，默认等级5',
            'car_id' => 'app', 'Car ID',
            'city_id' => 'app', 'City ID',
            'orderNum' => 'app', '司机评价级别，默认等级5',
            'status' => 'app', '当前状态',
            'baseId' => 'app', 'Base ID',
            'location' => 'app', '司机签到时经纬度',
            'mobilephone' => 'app', 'Mobilephone',
            'password' => 'app', 'Password',
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
