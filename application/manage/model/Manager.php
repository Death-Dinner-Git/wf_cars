<?php
namespace app\manage\model;

use app\common\model\Model;

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
    public $managerList = ['manage'=>'主管','sales'=>'个人销售','supperAdmin'=>'超级主管']; //管理员类型

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
    protected $_validate = array(
        array('create_time', 'require', '创建时间 不能为空'),
        array('is_delete', 'require', '删除标志 不能为空'),
        array('update_time', 'require', '更新时间 不能为空'),
        array('manager_type', 'require', '管理员类型 不能为空'),
        array('base_user_id', 'require', '基础用户ID 不能为空'),
        array('department_id', 'require', '分销处ID 不能为空'),
//        [['is_delete', 'base_user_id', 'wofang_id', 'department_id'], 'integer','不是数字'],
//        [['remark'], 'string'],
//        [['identification_cards', 'manager_type', 'phone', 'real_name', 'token', 'head_portrait', 'sex', 'rongyun_token'], 'string', 'max' => 255],
//        [['base_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => 'BaseUser::className()', 'targetAttribute' => ['base_user_id' => 'id']],
//        [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => 'Department::className()', 'targetAttribute' => ['department_id' => 'id']],
    );

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
}
