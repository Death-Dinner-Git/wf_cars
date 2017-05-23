<?php

namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\DepartmentValidate;

/**
 * This is the model class for table "wf_department".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property integer $isGroup
 * @property integer $level
 * @property string $name
 * @property integer $orders
 * @property integer $pid
 * @property integer $status
 * @property integer $wofang_id
 * @property string $lat
 * @property string $lng
 *
 * @property Manager[] $managers
 * @property TakeCarOrder[] $takeCarOrders
 */
class Department extends Model
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'department';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [
        ['isGroup','require',],
        ['level','require',],
        ['name','require',],
        ['orders','require',],
        ['pid','require',],
        ['status','require',],
        ['name','max:255',],
        ['lat','max:255',],
        ['lng','max:255',],
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
            [['create_time', 'update_time', 'isGroup', 'level', 'name', 'orders', 'pid', 'status'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'isGroup', 'level', 'orders', 'pid', 'status', 'wofang_id'], 'integer'],
            [['remark'], 'string'],
            [['name', 'lat', 'lng'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'app', 'ID',
            'create_time' => 'app', 'Create Time',
            'is_delete' => 'app', 'Is Delete',
            'remark' => 'app', 'Remark',
            'update_time' => 'app', 'Update Time',
            'isGroup' => 'app', 'Is Group',
            'level' => 'app', 'Level',
            'name' => 'app', 'Name',
            'orders' => 'app', 'Orders',
            'pid' => 'app', 'Pid',
            'status' => 'app', 'Status',
            'wofang_id' => 'app', '我房网第三方Id',
            'lat' => 'app', '经度(百度地图)',
            'lng' => 'app', '纬度(百度地图)',
        ];
    }

    /**
     * @description 获取全部办事处
     * @param $key
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public static function getAllDepartment($key = 'department')
    {
        $key = $key === 'department' ? :'department';
        $key = __METHOD__.'_'.$key;
        $res = Department::load()->cache(md5($key),1800)->select();
        return $res;
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getManagers()
    {
        return $this->hasMany('Manager', 'department_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'department_id' , 'id');
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return DepartmentValidate::load();
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
    public function synchroDepartment($data){
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
