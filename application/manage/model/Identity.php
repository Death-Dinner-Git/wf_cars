<?php
namespace app\manage\model;

use app\common\model\Model;
use app\manage\validate\IdentityValidate;
use app\manage\model\Manager;

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
 */
class Identity extends Model
{

    public $table = 'wf_manager';

    //登录请求路由
    public $loginUrl = 'manage/login/login';

    //所有账号类型
    private static $managerList = ['supperAdmin'=>'超级主管','manage'=>'主管','sales'=>'个人销售','derver'=>'司机'];
    //允许登录账号类型
    private static $allowList = ['manage','supperAdmin'];
    //允许登录账号 匹配类型
    private static $allowFind = ['username','phone'];
    //密码加密前缀
    private static $passwordPrefix = '';
    //密码加密后缀
    private static $passwordSuffix = '';
    //加密方式；可选参数1和2；默认是1；
    private static $encryptType = '1';
    //加密方式；可选参数1和2；默认是1；
    private static $login_time = 'update_time';
    //是否开启登录IP记录
    private static $isLog = false;
    //用户加密SSL
    private $_useLibreSSL;
    //随机字符
    private $_randomFile;

    //校验码
    public $__token__;
    //用户名
    public $username;
    //密码
    public $password;
    //重设密码
    public $password_rep;
    //记住我
    public $rememberMe;
    //记住我
    public $thisTime = '';

    /**
     * @var \app\manage\model\Identity
     */
    protected $_identity;
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

    // 追加属性
    protected $append = [
        'password',
        'password_rep',
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

        ['username', 'require', '用户名不能为空', 'regex',],
        ['username', '3,32', '用户名长度为1-32个字符', 'length', ],
        ['username', 'unique:base_user,username', '用户名被占用',  'unique',],
        ['username', '/^(?!_)(?!\d)(?!.*?_$)[\w]+$/', '用户名只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！',  'regex', ],
        ['password', 'require', '密码不能为空',  'regex', ],
        ['password', '6,30', '密码长度为6-30位',  'length'],
        ['password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成',  'regex',],

        // 验证注册来源
        ['reg_type', 'require', '注册来源不能为空', 'regex',],
    ];

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = [
        ['score', '0', ],
        ['money', '0', ],
        ['reg_ip', 'get_client_ip',  'function', 1],
        ['password', 'user_md5',  'function'],
        ['create_time', 'time',  'function'],
        ['update_time', 'time',  'function'],
        ['status', '1',],
    ];

    /**
     * @return array
     */
    public static function getManagerList()
    {
        return self::$managerList;
    }

    /**
     * @return array
     */
    public static function getAllowList()
    {
        return self::$allowList;
    }

    /**
     * @return array
     */
    public static function getAllowFind()
    {
        return self::$allowFind;
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     * @return Identity|bool
     */
    public function signUp()
    {
        $res = false;
        $data = [
            'username'=>$this->getData('username'),
            'password'=>$this->getData('password'),
        ];
        $validate = self::getValidate();
        $validate->scene('signUp');
        if( $validate->check($data)){
            $this->thisTime = time();
            $newPassword = $this->getJoinPassword($this->getData('password'));
            $db= $this->save([
                'uid'=>rand(100000,999999),
                'username'=>$this->getData('username'),
                'password_hash'=>$this->generateHash($newPassword),
                'registered_at'=>$this->thisTime,
                'logined_at'=>$this->thisTime,
                'updated_at'=>$this->thisTime,
            ]);  //这里的save()执行的是添加
            if ($db){
                $res = $this;
            }
        }

        return $res;

    }

    /**
     * @description Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     * @param int $duration
     * @return Identity|bool|string
     */
    public function login( $duration = 0)
    {
        $this->username = trim($this->username);
        $this -> data([
            'username'=>$this->username,
            'password'=>$this->password,
            '__token__'=>input('__token__'),
        ]);
        $validate = self::getValidate();
        $validate->scene('login');
        $ret = true;
        if( $validate->check($this -> data)){
            if ($identity = $this->findIdentity()){
                if ( true || $this->validatePassword($this->password)){
                    if ($this->log()){
                        $login_time = self::$login_time;
                        $this->$login_time = date('Y-m-d H:i:s');

                        $this->password = '888888';
                        $enPassword = $this->setPassword($this->password);

                        //这里的save()执行的是更新
                        $result = $identity->load()
                            ->alias('t')
                            ->join(BaseUser::tableName().' b','t.base_user_id = b.id')
                            ->where(['b.username'=>$this->username])
                            ->where('t.manager_type','in',self::$allowList)
                            ->update([
                                'b.password'=>$enPassword,
                                't.update_time'=>$this->$login_time
                            ]);
                        if($result){
                            //if true, default keep one week online;
                            $default = $this->rememberMe ? config('identity._rememberMe_duration') : ( config('identity._default_duration') ? config('identity._default_duration') : 0 ) ;
                            $duration = $duration ? $duration : $default ;
                            $ret = $this->setIdentity($identity, $duration);
                        }else{
                            $ret = '服务出错';
                        }
                    }else{
                        $ret = '未发现会员';
                    }
                }else{
                    $ret = '密码错误';
                }
            }else{
                $ret = '未存在此账号';
            }
        }else{
            $ret = $validate->getError();
        }
        return $ret;

    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public static function logout()
    {
        session(config('identity._identity'),null);
        session(config('identity._auth_key'), null);
        session(config('identity._duration'),null);
        return true;
    }

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return IdentityValidate::load();
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
     * 根据用户ID获取用户信息
     * @param  integer $id 用户ID
     * @param  string $field
     * @return array  用户信息
     * @author Sir Fu
     */
    public function getIdentityInfo($id = null, $field = null)
    {
        if (!$id) {
            return false;
        }
        if (is_dir(APP_DIR . 'user') && (D('Admin/Module')->where('name="Identity" and status="1"')->count())) {
            $user_info = D('Identity/Identity')->detail($id);
        } else {
            $user_info = $this->find($id);
        }
        unset($user_info['password']);
        if (!$field) {
            return $user_info;
        }
        if ($user_info[$field]) {
            return $user_info[$field];
        } else {
            return false;
        }
    }

    /**
     * 自动设置登录状态
     * @param int $duration
     * @return int
     */
    public function autoLogin($duration = 0)
    {
        return false;
        // 记录登录SESSION和COOKIES
        $identity = $this->getIdentity();
        $auth = array(
            'uid'      => $identity->id,
            'username' => $identity->username,
        );
        //if true, default keep one week online;
        $default = $this->rememberMe ? config('identity._rememberMe_duration') : ( config('identity._default_duration') ? config('identity._default_duration') : 0 ) ;
        $duration = $duration ? $duration : $default ;
        session('user_auth', $auth);
        session('user_auth_sign', $this->data_auth_sign($auth));
        $this->setIdentity($identity, $duration);
        return $this->isGuest();
    }

    /**
     * 数据签名认证
     * @param  mixed $data 被认证的数据
     * @return string       签名
     * @author Sir Fu
     */
    public static function data_auth_sign($data)
    {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array) $data;
        }
        ksort($data); //排序
        $code = http_build_query($data); // url编码并生成query字符串
        $sign = sha1($code); // 生成签名
        return $sign;
    }

    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     * @author Sir Fu
     */
    public function isGuest()
    {
        $user = new Identity();
        $_identity = $user->getIdentity();
        if (empty($_identity)) {
            return 0;
        } else {
            if (self::isValidIdentity($_identity)) {
                return $_identity->id;
            } else {
                return 0;
            }
        }
    }

    /**
     * log login IP
     *
     * @return bool
     */
    private function log()
    {
        if (!self::$isLog){
            return true;
        }
        $identity = $this->findIdentity();
        if ($identity === null) {
            return false;
        }
        $ret = false;
        $ip = json_decode($identity->getData('ip'), true);
        $currentIp = request()->ip();
        if (!$ip){
            $ip = ['last'=>['127.0.0.1',date('Y-m-d H:i:s')], 'current'=>['127.0.0.1',date('Y-m-d H:i:s')],'often'=>[],'haply'=>[],'once'=>[]];
        }
        if (in_array($currentIp,$ip['often'])){
            $ret = true;
        }
        $time = 1;
        $date = date('Y-m-d H:i:s');
        if (!$ret){
            $unset =false;
            foreach ($ip['once'] as $oKey => $oValue){
                if (count($ip['once'])>=10 && !$unset){
                    $unset = true;
                }
                if ($unset){
                    unset($ip['once'][$oKey]);
                }
                if ($oKey == $currentIp){
                    $time += intval($oValue[0]);
                    if (strtotime($oValue[0])+24*60*60 > time()){
                        $time = $oValue[0];
                        $date = $oValue[1];
                    }
                    break;
                }
            }
        }
        if (in_array($currentIp,$ip['haply'])){
            $ret = true;
            if ($time >= 15){
                $ip['often'][] = $currentIp;
                if (count($ip['often'])>10){
                    unset($ip['often'][0]);
                }
                $ip['often'] = array_values($ip['often']);
            }
        }else{
            if ($time >= 5){
                $ip['haply'][] = $currentIp;
                if (count($ip['haply'])>10){
                    unset($ip['haply'][0]);
                }
                $ip['haply'] = array_values($ip['haply']);
            }
        }
        $ip['once'][$currentIp][0] = $time;
        $ip['once'][$currentIp][1] = $date;
        $ip['last'] = $ip['current'];
        $ip['current'] = [$currentIp,date('Y-m-d H:i:s')];
        $identity->ip = json_encode($ip);
        $identity->status = $ret ? '1' : '0';
        return true;
    }

    /**
     * set a user
     *
     * @param Identity $_identity
     * @param $duration
     * @return Identity | null
     */
    protected function setIdentity(Identity $_identity, $duration = 0)
    {
        session(config('identity._identity'),$_identity);
        return $_identity;
    }

    /**
     * set a user
     *
     * @param Identity $_identity
     * @param $duration
     * @return string|null
     */
    protected function setRememberMe(Identity $_identity, $duration = 0)
    {
        $duration = (int) $duration;
        if ($duration<1 || !($_identity && $_identity->username)){
            return null;
        }
        if ( @get_class($_identity) == get_class($this) ){
            $token = $this->generateRandomString() . '_' . time();
            $db= $this->isUpdate(true,['username'=>$_identity->getData('username')])->save([
                'token'=> $token,
            ]);  //这里的save()执行的是更新
            if ($db){
                session(config('identity._duration'),time());
                return $token;
            }
        }
        return null;
    }

    /**
     * Finds user by [[username]]
     * @param string $username
     * @return Identity|null
     */
    protected function findIdentity($username = null)
    {
        if (!$username){
            $username = $this->username;
        }
        if ($this->_identity === null) {
            $this->_identity = $this->findByUsername($username);
        }
        if ($this->_identity === null) {
            $this->_identity = $this->findByPhone($username);
        }
        if ($this->_identity === null) {
            $this->_identity = $this->findByEmail($username);
        }
        return $this->_identity;
    }

    /**
     * @description Finds identity by [[username]]
     * @param $username
     * @return Identity | null
     */
    public static function findByUsername($username)
    {
        if (empty($username) || !in_array('username',self::$allowFind)) {
            return null;
        }

        return self::load()
            ->alias('t')
            ->join(BaseUser::tableName().' b','t.base_user_id = b.id')
            ->where(['b.username'=>$username])
            ->where('t.manager_type','in',self::$allowList)
            ->field('*,t.update_time as t_update_time,b.update_time as b_update_time')
            ->find();
    }

    /**
     * Finds user by [[username]]
     *
     * @param $phone
     * @return Identity|null
     */
    public static function findByPhone($phone)
    {
        if (empty($phone) || !in_array('phone',self::getAllowFind())) {
            return null;
        }

        return self::load()
            ->alias('t')
            ->join(BaseUser::tableName().' b','t.base_user_id = b.id')
            ->where(['t.phone'=>$phone])
            ->where('t.manager_type','in',self::$allowList)
            ->field('*,t.update_time as t_update_time,b.update_time as b_update_time')
            ->find();
    }

    /**
     * Finds user by [[username]]
     *
     * @param $email
     * @return Identity|null
     */
    public static function findByEmail($email)
    {
        if (empty($email) || !in_array('email',self::$allowFind)) {
            return null;
        }

        return self::load()
            ->alias('t')
            ->join(BaseUser::tableName().' b','t.base_user_id = b.id')
            ->where(['t.email'=>$email])
            ->where('t.manager_type','in',self::$allowList)
            ->field('*,t.update_time as t_update_time,b.update_time as b_update_time')
            ->find();
    }

    /**
     * Finds user by [[id]]
     *
     * @param $id
     * @return Identity|null
     */
    public static function getIdentityById($id)
    {
        if (empty($id)) {
            return null;
        }

        return self::get(['id' => $id]);
    }

    /**
     * get user token from session or mysql.
     *
     * @return string|null
     */
    protected function geToken()
    {
        if (session(config('identity._auth_key'))){
            return session(config('identity._auth_key'));
        }
        return null;
    }

    /**
     * @description set user token into mysql and session.
     * @param $_authKey
     */
    protected function setToken($_authKey = null)
    {
        if (!$_authKey){
            if (!$this->auth_key){
                $this->setAuthKey();
            }
            $_authKey = $this->auth_key;
        }
        session(config('identity._auth_key'), $_authKey);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return self::get(['password_reset_token' => $token,]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $duration = config('identity._passwordResetTokenExpire');
        return $timestamp + $duration >= time();
    }

    /**
     * @description get the current identity ID ,or the primary key of Identity what is existed in Identity table
     * @return int|null
     */
    public static function getId()
    {
        $identity = self::getIdentity();
        if ($identity){
            return $identity->id;
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        if (!$this->auth_key){
            $user = $this->getIdentity();
            if ($user){
                $this->auth_key = $user->auth_key;
            }else{
                $this->auth_key = $this->getIdentity('auth_key');
            }
        }
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function setAuthKey()
    {
        $this->setAttr('auth_key',md5(md5(time()).$this->username));
        $this->setToken($this->auth_key);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     *
     *
     * Verifies a password against a hash.
     * @param string $password The password to verify.
     * @param string $hash The hash to verify the password against.
     * @return boolean whether the password is correct.
     * @see generateHash()
     */
    public function validatePassword($password)
    {
        if (!is_string($password) || $password === '') {
            return false; //Password must be a string and cannot be empty
        }

        $identity = $this->findIdentity();
        $hash =$identity->getData('password');
        $password = $this->getJoinPassword($password);
        if (self::$encryptType == 1){
            if ($this->generateHash($password) === $hash){
                return true;
            }else{
                return false;
            }
        }elseif(self::$encryptType == 2){
            if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches)
                || $matches[1] < 4
                || $matches[1] > 30
            ) {
                return false; //Hash is invalid
            }

            if (function_exists('password_verify')) {
                return password_verify($password, $hash);
            }

            $test = crypt($password, $hash);
            $n = strlen($test);
            if ($n !== 60) {
                return false;
            }

            return $this->compareString($test, $hash);
        }

        return false;
    }

    /**
     * Performs string comparison using timing attack resistant approach.
     * @see http://codereview.stackexchange.com/questions/13512
     * @param string $expected string to compare.
     * @param string $actual user-supplied string.
     * @return boolean whether strings are equal.
     */
    public function compareString($expected, $actual)
    {
        $expected .= "\0";
        $actual .= "\0";
        $expectedLength = mb_strlen($expected, '8bit');
        $actualLength = mb_strlen($actual, '8bit');
        $diff = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }
        return $diff === 0;
    }

    /**
     * @description Generates password hash from password and sets it to the model
     * @param $password
     * @return string
     *
     */
    public function setPassword($password)
    {
        $password = $this->getJoinPassword($password) ;
        $password = $this->generateHash($password);
        $this->setAttr('password',$password);
        return $password;
    }

    /**
     * @return string
     */
    public function getJoinPassword($password){
        $newPassword = $password;
        if (self::$encryptType == 1){
            $newPassword = self::$passwordPrefix.$password.self::$passwordSuffix;
        }elseif (self::$encryptType == 2){
            if (!$this->thisTime){
                $identity = $this->getIdentity();
                if ($identity){
                    $login_time = self::$login_time;
                    $this->thisTime = $identity->$login_time;
                }
            }
            $newPassword = self::$passwordPrefix.$password.self::$passwordSuffix.$this->thisTime;
        }
        return $newPassword;
    }

    /**
     * Generates hash from password and sets it to the model
     *
     * @param string $string
     * @param int $cost
     * @return string
     * @notice $string Has been rnn the function  getJoinPassword() to processed;
     */
    private function generateHash($string, $cost = null)
    {
        $ret = $string;

        if (self::$encryptType ==1){
            $hash = md5($string);
            return $hash;
        }elseif(self::$encryptType == 2){
            $salt = $this->generateSalt($cost);
            $hash = crypt($string, $salt);

            // strlen() is safe since crypt() returns only ascii
            if (!is_string($hash) || strlen($hash) !== 60) {
                $hash = substr(md5($string).md5(md5($string)),0,60);
            }

            return $hash;
        }

        return $ret;
    }

    /**
     * @param int $cost
     * @return string
     */
    protected function generateSalt($cost = 13)
    {
        $cost = (int) $cost;
        if ($cost < 4 || $cost > 31) {
            $cost = 13;
        }

        // Get a 20-byte random string
        $rand = $this->generateRandomKey(20);
        if (!$rand){
            $rand = md5($cost);
        }
        // Form the prefix that specifies Blowfish (bcrypt) algorithm and cost parameter.
        $salt = sprintf("$2y$%02d$", $cost);
        // Append the random salt data in the required base64 format.
        $salt .= str_replace('+', '.', substr(base64_encode($rand), 0, 22));

        return $salt;
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomKey($length = 32)
    {
        $length = (int) $length;
        if ($length < 1 || !is_int($length)) {
            $length = 20;
        }

        // always use random_bytes() if it is available
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }

        // The recent LibreSSL RNGs are faster and likely better than /dev/urandom.
        // Parse OPENSSL_VERSION_TEXT because OPENSSL_VERSION_NUMBER is no use for LibreSSL.
        // https://bugs.php.net/bug.php?id=71143
        if ($this->_useLibreSSL === null) {
            $this->_useLibreSSL = defined('OPENSSL_VERSION_TEXT')
                && preg_match('{^LibreSSL (\d\d?)\.(\d\d?)\.(\d\d?)$}', OPENSSL_VERSION_TEXT, $matches)
                && (10000 * $matches[1]) + (100 * $matches[2]) + $matches[3] >= 20105;
        }

        // Since 5.4.0, openssl_random_pseudo_bytes() reads from CryptGenRandom on Windows instead
        // of using OpenSSL library. LibreSSL is OK everywhere but don't use OpenSSL on non-Windows.
        if ($this->_useLibreSSL
            || (
                DIRECTORY_SEPARATOR !== '/'
                && substr_compare(PHP_OS, 'win', 0, 3, true) === 0
                && function_exists('openssl_random_pseudo_bytes')
            )
        ) {
            $key = openssl_random_pseudo_bytes($length, $cryptoStrong);
            if ($cryptoStrong === false) {
                return false;
            }
            if ($key !== false && mb_strlen($key, '8bit') === $length) {
                return $key;
            }
        }

        // mcrypt_create_iv() does not use libmcrypt. Since PHP 5.3.7 it directly reads
        // CryptGenRandom on Windows. Elsewhere it directly reads /dev/urandom.
        if (function_exists('mcrypt_create_iv')) {
            $key = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
            if ( mb_strlen($key, '8bit') === $length) {
                return $key;
            }
        }

        // If not on Windows, try to open a random device.
        if ($this->_randomFile === null && DIRECTORY_SEPARATOR === '/') {
            // urandom is a symlink to random on FreeBSD.
            $device = PHP_OS === 'FreeBSD' ? '/dev/random' : '/dev/urandom';
            // Check random device for special character device protection mode. Use lstat()
            // instead of stat() in case an attacker arranges a symlink to a fake device.
            $lstat = @lstat($device);
            if ($lstat !== false && ($lstat['mode'] & 0170000) === 020000) {
                $this->_randomFile = fopen($device, 'rb') ?: null;

                if (is_resource($this->_randomFile)) {
                    // Reduce PHP stream buffer from default 8192 bytes to optimize data
                    // transfer from the random device for smaller values of $length.
                    // This also helps to keep future randoms out of user memory space.
                    $bufferSize = 8;

                    if (function_exists('stream_set_read_buffer')) {
                        stream_set_read_buffer($this->_randomFile, $bufferSize);
                    }
                    // stream_set_read_buffer() isn't implemented on HHVM
                    if (function_exists('stream_set_chunk_size')) {
                        stream_set_chunk_size($this->_randomFile, $bufferSize);
                    }
                }
            }
        }

        if (is_resource($this->_randomFile)) {
            $buffer = '';
            $stillNeed = $length;
            while ($stillNeed > 0) {
                $someBytes = fread($this->_randomFile, $stillNeed);
                if ($someBytes === false) {
                    break;
                }
                $buffer .= $someBytes;
                $stillNeed -= mb_strlen($someBytes, '8bit');
                if ($stillNeed === 0) {
                    // Leaving file pointer open in order to make next generation faster by reusing it.
                    return $buffer;
                }
            }
            fclose($this->_randomFile);
            $this->_randomFile = null;
        }

        return false;
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateRandomString($length = 32)
    {
        if ($length < 1 || !is_int($length)) {
            $length = 32;
        }

        $bytes = $this->generateRandomKey($length);
        // '=' character(s) returned by base64_encode() are always discarded because
        // they are guaranteed to be after position $length in the base64_encode() output.
        return strtr(substr(base64_encode($bytes), 0, $length), '+/', '_-');
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = $this->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = $this->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Removes password reset code
     */
    public function removePasswordResetCode()
    {
        $this->password_reset_code = null;
    }

    /**
     * @param string|null $name
     * @return Identity|null
     */
    public static function getIdentity($name = null)
    {
        $identity =  session(config('identity._identity'));
        if ($identity && @get_class($identity) == self::class ){
            if (!is_string($name) || $name === '') {
                return $identity;
            }
            if (is_string($name) && $identity){
                if (array_key_exists($name, $identity->data)) {
                    return $identity->data[$name];
                }
            }
        }
        return null;
    }

    /**
     * Finds a Valid user
     *
     * @param Identity $_identity
     * @return bool
     */
    public static function isValidIdentity(Identity $_identity = null)
    {
        $res = false;
        if (!$_identity){
            $_identity = new Identity();
            $_identity->getIdentity();
        }
        if (session('user_auth_sign') == self::data_auth_sign($_identity)){

        }
        $duration =  session(config('identity._duration'));
        if ( $duration && $_identity && ($duration + config('identity._default_duration')) > time()){
            session(config('identity._duration'),time());
            $res = true;
        }
        return $res;
    }

    /**
     * @return string
     */
    public static function getClient()
    {
        if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], "wap")) {
            return $_SERVER['HTTP_VIA'];
        } elseif (isset($_SERVER['HTTP_ACCEPT']) && strpos(strtoupper($_SERVER['HTTP_ACCEPT']), "VND.WAP.WML")) {
            return $_SERVER['HTTP_ACCEPT'];
        } elseif (isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE'])) {
            return isset($_SERVER['HTTP_X_WAP_PROFILE']) ? $_SERVER['HTTP_X_WAP_PROFILE'] : $_SERVER['HTTP_PROFILE'];
        } elseif (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        } else {
            return $_SERVER['HTTP_USER_AGENT'];
        }
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        //排除一些非法属性名称
        if (is_null($name) || !is_string($name) || $name === ''){
            return parent::__get($name);
        }

        if (!property_exists($this,$name)){
            if (array_key_exists($name, $this->data)) {
                return $this->data[$name];
            }

            if ($this->getIdentity()) {
                return $this->getIdentity($name);
            }
        }

        return parent::__get($name); // TODO: Change the autogenerated stub
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
