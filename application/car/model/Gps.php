<?php
/*
-----------------------------------
 GPS模型
-----------------------------------
*/
namespace app\car\model;
use app\common\model\Model;
use think\Loader;

class Gps extends Model{

	protected $autoWriteTimestamp = 'datetime';//自动写入
	protected $dateFormat = 'Y-m-d H:i:s';//自动格式输出
	


}