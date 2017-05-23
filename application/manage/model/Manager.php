<?php
namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\ManagerValidate;

/**
 * @description This is the model class for table "wf_manager".  扩展管理员
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $identification_cards
 * @property string $manager_type
 * @property string $phone
 * @property string $real_name
 * @property integer $base_user_id
 * @property string $token
 * @property integer $wofang_id
 * @property integer $department_id
 * @property string $head_portrait
 * @property string $sex
 * @property string $rongyun_token
 *
 * @property BaseUser $baseUser
 * @property Department $department
 * @property OutCar[] $outCars
 * @property RepairCar[] $repairCars
 * @property TakeCarOrder[] $takeCarOrders
 *
 * @property array $managerList
 *
 */
class Manager extends Model
{

    public $managerList = ['supperAdmin'=>'超级主管','manage'=>'主管','sales'=>'个人销售','derver'=>'司机']; //管理员类型

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'create_time',
        'is_delete',
        'remark',
        'update_time',
        'identification_cards',
        'manager_type',
        'phone',
        'real_name',
        'base_user_id',
        'token',
        'wofang_id',
        'department_id',
        'head_portrait',
        'sex',
        'rongyun_token',
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'manager';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = [
        ['wofang_id','unique:manager,wofang_id','我房网第三方Id已存在'],
        ['is_delete','require','删除标志 不能为空'],
        ['manager_type','require','管理员类型 不能为空'],
        ['base_user_id','require','基础用户ID 不能为空'],
        ['department_id','require','分销处ID 不能为空'],
        ['identification_cards','max:255',],
        ['manager_type','max:255',],
        ['phone','max:255',],
        ['real_name','max:255',],
        ['token','max:255',],
        ['head_portrait','max:255',],
        ['sex','max:255',],
        ['rongyun_token','max:255',],
//        ['base_user_id','exist:base_user,base_user_id',],
//        ['department_id','exist:department,department_id',],
    ];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [
    ];

    /**
     * @return array
     */
    public static function getManagerList(){
        $model = new Manager();
        return $model->managerList;
    }

    /**
     * @description 与基础用户表一对一关联
     * @return \think\model\relation\BelongsTo
     */
    public function getBaseUser()
    {
        return $this->belongsTo('BaseUser','base_user_id','id');
    }

    /**
     * @return \think\model\relation\BelongsTo
     */
    public function getDepartment()
    {
        return $this->belongsTo('Department','department_id','id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getOutCars()
    {
        return $this->hasMany('OutCar', 'manager_id', 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getRepairCars()
    {
        return $this->hasMany('RepairCar', 'manager_id', 'id');
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function getTakeCarOrders()
    {
        return $this->hasMany('TakeCarOrder', 'manager_id', 'id');
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return ManagerValidate::load();
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
    public function synchroManager($data){
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
