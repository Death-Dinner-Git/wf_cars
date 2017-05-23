<?php
namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\BaseUserValidate;

/**
 * @description This is the model class for table "wf_base_user.  基础管理员
 * @author Sir Fu
 *
 * @property integer $id
 * @property string $create_time
 * @property integer $is_delete
 * @property string $remark
 * @property string $update_time
 * @property string $password
 * @property string $username
 * @property integer $VERSION_NUM
 *
 */
class BaseUser extends Model
{
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
        'password',
        'username',
        'VERSION_NUM',
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'base_user';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = array(
        array('create_time', 'require', '创建时间 不能为空'),
        array('is_delete', 'require', '删除标志 不能为空'),
        array('update_time', 'require', '更新时间 不能为空'),
        array('username', 'require', '登录名 不能为空'),
        array('VERSION_NUM', 'require', '版本 不能为空'),
//        [['create_time', 'update_time', 'username', 'VERSION_NUM'], 'require'],
//        [['create_time', 'update_time'], 'safe'],
//        [['is_delete', 'VERSION_NUM'], 'integer'],
//        [['remark'], 'string'],
//        [['password', 'username'], 'string', 'max' => 255],
    );

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = array(
    );

    /**
     * @return \think\model\relation\HasOne
     */
    public function getManagers()
    {
        return $this->hasOne('Manager', 'base_user_id', 'id');
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BaseUserValidate::load();
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
    public function synchroBaseUser($data){
        $validate = self::getValidate();

        //设定场景
        $validate->scene('sync');

        $identity = isset($data['username']) ? $data['username'] : '0';
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
                $where = ['username'=>(!empty($identity) ? $identity : '0')];
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
