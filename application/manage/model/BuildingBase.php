<?php


namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\BuildingBaseValidate;

/**
 * This is the model class for table "{{%building_base}}".
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $address
 * @property string $lat
 * @property string $lng
 * @property string $name
 * @property integer $wofang_id
 * @property integer $city_id
 *
 * @property City $city
 * @property OutCar[] $outCars
 * @property TakeCarOrder[] $takeCarOrders
 */
class BuildingBase extends Model
{

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'building_base';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [
        ['isGroup','require',],
//        ['city_id','unique:city,city_id'],
        ['address','max:255',],
        ['lat','max:255',],
        ['lng','max:255',],
        ['name','max:255',],
    ];

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_time', 'update_time', 'address', 'lat', 'lng', 'name', 'city_id'], 'required'],
            [['create_time', 'update_time'], 'safe'],
            [['is_delete', 'wofang_id', 'city_id'], 'integer'],
            [['remark'], 'string'],
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
            'address' => 'app', '楼盘地址',
            'lat' => 'app', '经度(百度地图)',
            'lng' => 'app', '纬度(百度地图)',
            'name' => 'app', '楼盘名',
            'wofang_id' => 'app', '我房网第三方Id',
            'city_id' => 'app', 'City ID',
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
     * @return \think\model\relation\HasMany
     */
    public function getOutCars()
    {
        return $this->hasMany('OutCar', 'build_id' , 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'building_base_id' , 'id');
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BuildingBaseValidate::load();
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
    public function synchroBuildingBase($data){
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
