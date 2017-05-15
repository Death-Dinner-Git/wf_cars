<?php
namespace app\manage\model;

use app\common\model\Model;

/**
 * @description TThis is the model class for table "wf_menu".  菜单模型
 * @author Sir Fu
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $route
 * @property integer $order
 * @property string $type
 * @property string $data
 *
 */
class Menu extends Model
{
    const TYPE_SYS = '1';   //type默认为1，表示网站后台系统菜单，
    const TYPE_APP = '2';    //type可能值为2，表示前端菜单，

//    /**
//     * 数据库表名
//     * @author Sir Fu
//     */
//    protected $table = 'menu';

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'name',
        'parent',
        'route',
        'order',
        'type',
        'data',
    ];

    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'menu';
    }

    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = array(
        array('name', 'require', '名称 不能为空'),
        array('name', '0,128', '名称 长度为0-128位',  'length', ),
        array('route', '0,256', '路由地址 长度为0-256位',  'length', ),
        array('parent', 'number', '父级需要是 数值', ),
        array('order', 'number', '排序是 数值', ),
        array('type', self::TYPE_SYS.','.self::TYPE_APP, '类型 只能是'.self::TYPE_SYS.'或'.self::TYPE_APP,  'between', ),
    );

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = array(
    );

    /**
     *
     */
    public static function getField(){
        $model = new Menu();
        return $model->field;
    }

}
