<?php
namespace app\manage\model;

use app\common\model\Model;

/**
 * @description This is the model class for table "{{%back_user}}".  用户模型
 * @author Sir Fu
 *
 * @property integer $id
 * @property integer $uid
 * @property string $mb
 * @property integer $mb_verified
 * @property string $email
 * @property integer $email_verified
 * @property string $username
 * @property string $password_hash
 * @property string $registered_at
 * @property string $logined_at
 * @property string $head_url
 * @property string $real_name
 * @property string $sex
 * @property string $signature
 * @property string $birthday
 * @property integer $height
 * @property integer $weight
 * @property string $token
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $password_reset_code
 * @property string $ip
 * @property int $status
 * @property string $update_date
 *
 * @property string $password
 * @property string $password_rep
 * @property string $rememberMe
 */
class User extends Model
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return parent::getTablePrefix().'user';
    }

    /**
     * @var string
     */
    protected $pk = 'id';

    // 数据表字段信息 留空则自动获取
    protected $field = [
        'id',
        'uid',
        'mb',
        'mb_verified',
        'email',
        'email_verified',
        'username',
        'password_hash',
        'registered_at',
        'logined_at',
        'head_url',
        'real_name',
        'sex',
        'signature',
        'birthday',
        'height',
        'weight',
        'token',
        'auth_key',
        'password_reset_token',
        'password_reset_code',
        'ip',
        'status',
        'update_date',
    ];
    //

    /**
     * @var array
     */
    public $loginUrl = ['manage/login/login'];

    // 追加属性
    protected $append = [
        'password',
        'password_rep',
    ];

    /**
     * @var \app\manage\model\User
     */
    protected $_user;

    private $_useLibreSSL;
    private $_randomFile;

    public $__token__;
    public $username;
    public $password;

    protected $password_rep;
    protected $rememberMe;

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     * @return User|bool
     */
    public function signUp()
    {
        $res = false;
        $data = [
            'username'=>$this->getData('username'),
            'password'=>$this->getData('password'),
        ];
        $validate = \think\Loader::validate('userValidate');
        $validate->scene('signUp');
        if( $validate->check($data)){
            $time = time();
            $db= $this->save([
                'uid'=>rand(100000,999999),
                'username'=>$this->getData('username'),
//                'password_hash'=>$this->generateHash($this->data('password').$time),
                'password_hash'=>$this->generateHash($this->data('password')),
                'registered_at'=>$time,
                'logined_at'=>$time,
                'updated_at'=>$time,
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
     * @return User|bool
     */
    public function login( $duration = 0)
    {
        $this->username = trim($this->username);
        $this -> data([
            'username'=>$this->username,
            'password'=>$this->password,
            '__token__'=>input('__token__'),
        ]);
        $validate = \think\Loader::validate('userValidate');
        $validate->scene('login');
        if( $validate->check($this) && ($user = $this->getUser())){
            if ($this->validatePassword($this->password)){
                if ($this->log()){
                    $this->logined_at = time();
                    $this->setAuthKey();
                    $this->setPassword($this->password);
                    $db= $this->isUpdate(true,['username'=>$this->username])->save([
                        'password_hash'=>$this->password_hash,
                        'logined_at'=>$this->logined_at,
                        'auth_key'=>$this->auth_key,
                        'ip'=>$user->ip,
                        'status'=>$user->status,
                    ]);  //这里的save()执行的是更新
                    if($db){
                        //if true, default keep one week online;
                        $default = $this->rememberMe ? config('user._rememberMe_duration') : ( config('user._default_duration') ? config('user._default_duration') : 0 ) ;
                        $duration = $duration ? $duration : $default ;
                        $identity = $this->getUser();
                        $res = $this->setIdentity($identity, $duration);
                    }else{
                        $res = '服务出错';
                    }
                }else{
                    $res = '未发现会员';
                }
            }else{
                $res = '密码错误';
            }
        }else{
            $res = $validate->getError();
        }
        return $res;

    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public static function logout()
    {
        session(config('user._identity'),null);
        session(config('user._auth_key'), null);
        session(config('user._duration'),null);
        return true;
    }


    /**
     * 自动验证规则
     * @author Sir Fu
     */
    protected $_validate = array(
        //验证用户名
        array('nickname', 'require', '昵称不能为空', 'regex'),

        //验证用户名
        array('username', 'require', '用户名不能为空', 'regex',),
        array('username', '3,32', '用户名长度为1-32个字符', 'length', ),
        array('username', '', '用户名被占用',  'unique',),
        array('username', '/^(?!_)(?!\d)(?!.*?_$)[\w]+$/', '用户名只可含有数字、字母、下划线且不以下划线开头结尾，不以数字开头！',  'regex', ),

        //验证密码
        array('password', 'require', '密码不能为空',  'regex', ),
        array('password', '6,30', '密码长度为6-30位',  'length'),
        array('password', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成',  'regex',),

        // 验证注册来源
        array('reg_type', 'require', '注册来源不能为空', 'regex',),
    );

    /**
     * 自动完成规则
     * @author Sir Fu
     */
    protected $_auto = array(
        array('score', '0', ),
        array('money', '0', ),
        array('reg_ip', 'get_client_ip',  'function', 1),
        array('password', 'user_md5',  'function'),
        array('create_time', 'time',  'function'),
        array('update_time', 'time',  'function'),
        array('status', '1',),
    );

    /**
     * 根据用户ID获取用户信息
     * @param  integer $id 用户ID
     * @param  string $field
     * @return array  用户信息
     * @author Sir Fu
     */
    public function getUserInfo($id = null, $field = null)
    {
        if (!$id) {
            return false;
        }
        if (is_dir(APP_DIR . 'user') && (D('Admin/Module')->where('name="User" and status="1"')->count())) {
            $user_info = D('User/User')->detail($id);
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
        $identity = $this->getUser();
        $auth = array(
            'uid'      => $identity->id,
            'username' => $identity->username,
        );
        //if true, default keep one week online;
        $default = $this->rememberMe ? config('user._rememberMe_duration') : ( config('user._default_duration') ? config('user._default_duration') : 0 ) ;
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
        $user = new User();
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
        $user = $this->getUser();
        if ($user === null) {
            return false;
        }
        $res = false;
        $ip = json_decode($user->ip, true);
        $currentIp = request()->ip();
        if (!$ip){
            $ip = ['last'=>['127.0.0.1',date('Y-m-d H:i:s')], 'current'=>['127.0.0.1',date('Y-m-d H:i:s')],'often'=>[],'haply'=>[],'once'=>[]];
        }
        if (in_array($currentIp,$ip['often'])){
            $res = true;
        }
        $time = 1;
        $date = date('Y-m-d H:i:s');
        if (!$res){
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
            $res = true;
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
        $user->ip = json_encode($ip);
        $user->status = $res ? '1' : '0';
        return true;
    }

    /**
     * set a user
     *
     * @param User $_identity
     * @param $duration
     * @return User | null
     */
    protected function setIdentity(User $_identity, $duration = 0)
    {
        session(config('user._identity'),$_identity);
        $this->setRememberMe($_identity, $duration);
        $this->setToken();
        return $_identity;
    }

    /**
     * set a user
     *
     * @param User $_identity
     * @param $duration
     * @return string|null
     */
    protected function setRememberMe(User $_identity, $duration = 0)
    {
        $duration = (int) $duration;
        if ($duration<1 || !($_identity && $_identity->getData('username'))){
            return null;
        }
        if ( @get_class($_identity) == get_class($this) ){
            $token = $this->generateRandomString() . '_' . time();
            $db= $this->isUpdate(true,['username'=>$_identity->getData('username')])->save([
                'token'=> $token,
            ]);  //这里的save()执行的是更新
            if ($db){
                session(config('user._duration'),time());
                return $token;
            }
        }
        return null;
    }

    /**
     * Finds user by [[username]]
     * @param string $username
     * @return User|null
     */
    protected function getUser($username = null)
    {
        if (!$username){
            $username = $this->username;
        }
        if ($this->_user === null) {
            $this->_user = $this->findByUsername($username);
        }
        if ($this->_user === null) {
            $this->_user = $this->findByPhone($username);
        }
        if ($this->_user === null) {
            $this->_user = $this->findByEmail($username);
        }
        return $this->_user;
    }

    /**
     * Finds user by [[username]]
     *
     * @param $username
     * @return User|null
     */
    public static function findByUsername($username)
    {
        if (empty($username)) {
            return null;
        }
        return self::get(['username' => $username]);
    }

    /**
     * Finds user by [[username]]
     *
     * @param $phone
     * @return User|null
     */
    public static function findByPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        return self::get(['mb' => $phone]);
    }

    /**
     * Finds user by [[username]]
     *
     * @param $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        if (empty($email)) {
            return null;
        }

        return self::get(['email' => $email]);
    }

    /**
     * Finds user by [[id]]
     *
     * @param $id
     * @return User|null
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
        if (session(config('user._auth_key'))){
            return session(config('user._auth_key'));
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
        session(config('user._auth_key'), $_authKey);
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
        $duration = config('user._passwordResetTokenExpire');
        return $timestamp + $duration >= time();
    }

    /**
     * @description get the current identity ID ,or the primary key of User what is existed in User table
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
            $user = $this->getUser();
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
//        $this->setAttr('auth_key',md5(md5($this->logined_at.time()).$this->username));
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

        $user = $this->getUser();
        $hash = $user->password_hash;
        if ($this->generateHash($password) === $hash){
            return true;
        }else{
            return false;
        }
//        $password = $user->logined_at ? $user->logined_at.$password  : $password ;
//        if (!preg_match('/^\$2[axy]\$(\d\d)\$[\.\/0-9A-Za-z]{22}/', $hash, $matches)
//            || $matches[1] < 4
//            || $matches[1] > 30
//        ) {
//            return false; //Hash is invalid
//        }
//
//        if (function_exists('password_verify')) {
//            return password_verify($password, $hash);
//        }
//
//        $test = crypt($password, $hash);
//        $n = strlen($test);
//        if ($n !== 60) {
//            return false;
//        }
//
//        return $this->compareString($test, $hash);
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
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
//        $baseHash = $this->logined_at ? $this->logined_at.$password : $password ;
        $baseHash = $password;
        $this->setAttr('password_hash',$this->generateHash($baseHash));
    }

    /**
     * Generates hash from password and sets it to the model
     *
     * @param string $string
     * @param int $cost
     * @return string
     */
    private function generateHash($string, $cost = null)
    {
        $hash = md5($string);
        return $hash;
//        $salt = $this->generateSalt($cost);
//        $hash = crypt($string, $salt);
//
//        // strlen() is safe since crypt() returns only ascii
//        if (!is_string($hash) || strlen($hash) !== 60) {
//            $hash = substr(md5($string).md5(md5($string)),0,60);
//        }
//
//        return $hash;
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
     * @return User|null
     */
    public static function getIdentity($name = null)
    {
        $identity =  session(config('user._identity'));
        if ($identity && @get_class($identity) == self::class ){
            if (!is_string($name) || $name === '') {
                return $identity;
            }
            if (isset($identity->$name)){
                return $identity->$name;
            }
        }
        return null;
    }

    /**
     * Finds a Valid user
     *
     * @param User $_identity
     * @return bool
     */
    public static function isValidIdentity(User $_identity = null)
    {
        $res = false;
        if (!$_identity){
            $_identity = new User();
            $_identity->getIdentity();
        }
        if (session('user_auth_sign') == self::data_auth_sign($_identity)){

        }
        $duration =  session(config('user._duration'));
        if ( $duration && $_identity && ($duration + config('user._default_duration')) > time()){
            session(config('user._duration'),time());
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

    public function __get($name)
    {
        $res = null;
        if (!is_null($name) && !array_key_exists($name, $this->data)) {
            $this->$name = $this->getIdentity($name);
        }
        return parent::__get($name); // TODO: Change the autogenerated stub
    }

}
