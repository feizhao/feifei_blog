<?php
/**
 * @author zhaofei
 * @description 项目核心文件
 */
class Core{
	private static $_core = null;
	public $db = null;
	public $config = array();
	public $lang = null;
	public $path = null;
	public $host = null;
	public $assets = null;
	public $userDir = null;
	public $user = null;
	public $name = null;
	public $subname = null;
	public $actions = array();
	public $limiter = '/';
	public $guid = 'fangke';
	private $isinit=false; #是否初始化成功
	private $isconnect=false; #是否连接成功
	private $isload=false; #是否载入
	private $issession=false; #是否使用session
	private $isgzip=true; #是否开启gzip
	private $isgziped=false; #是否已经过gzip压缩

	function __construct(){
		global $appPath,$host,$limiter;
		$this->path = &$appPath;
		$this->host = &$host;
		$this->limiter = &$limiter;
		$this->userDir = $this->path.'notes'.$this->limiter;
		$this->assets = $this->host.'/notes/assets/';
		$this->corePath = $this->path.'core'.$this->limiter;

	}
	/**
	 *获取唯一实例
	 *@
	 */
	static public function getInstance(){
		if(!isset(self::$_core)){
			self::$_core = new Core;
		}
		return self::$_core;
	}

	public function init(){
		$this->dbConnect();
		$this->loadConfig();
		date_default_timezone_set($this->config['TIME_ZONE_NAME']);
		$this->lang = require($this->corePath . 'lang'.$this->limiter. $this->config['LANGUAGEPACK'] . '.php');
		$this->user =  new User;
		$this->name = $this->config['BLOG_NAME'];
		$this->subname = $this->config['BLOG_SUBNAME'];
		$this->actions = require($this->corePath . 'conf'.$this->limiter. 'act.php');
		$this->isinit = true;
	}

	private function dbConnect(){
		if($this->isconnect){
			return false;
		}
		$db_c = require_once $this->corePath.'conf'.$this->limiter.'db.conf.php';
		if(!$db_c['DATABASE_TYPE']){
			return false;
		}
		switch ($db_c['DATABASE_TYPE']) {
			case 'mysql':
			case 'mysqli':
			case 'pdo_mysql':
			default:
				try {
					$this->initDB($db_c['DATABASE_TYPE']);
					if($this->db->open(array(
							$db_c['MYSQL_SERVER'],
							$db_c['MYSQL_USERNAME'],
							$db_c['MYSQL_PASSWORD'],
							$db_c['MYSQL_NAME'],
							$db_c['MYSQL_PRE'],
							$db_c['MYSQL_PORT'],
							$db_c['MYSQL_PERSISTENT']
						))==false){
						$this->error('数据库错误');
					}
				} catch (Exception $e) {
					throw new Exception("MySQL DateBase Connection Error.");
				}
				break;
		}
		$this->isconnect=true;
		return true;
	}
	/**
	 * 初始化数据库连接
	 * @param string $type 数据连接类型
	 * @return bool
	 */
	public function initDB($type){
		if(!trim($type))return false;
		$newtype='Db'.strtolower(trim($type));
		$this->db=new $newtype();
	}


	private function loadConfig(){
		$this->config=array();
		$global_c = require_once $this->corePath.'conf'.$this->limiter.'global.php';
		// $sql = $this->db->sql->select('config',array('*'),'','','','');
		// $array=$this->db->query($sql);
		$user_c = require_once $this->corePath.'conf'.$this->limiter.'config.php';
		$this->config = array_merge($global_c,$user_c);
	}

	/**
	 * 重建索引并载入
	 * @return bool
	 */
	public function load(){
		if(!$this->isinit)return false;
		if($this->isload)return false;
		$this->startGzip();
		header('Content-type: text/html; charset=utf-8');
		$this->verify();
		$this->isload=true;
		return true;
	}


	public function startGzip(){
		if($this->isgziped)return false;
		if(!headers_sent()&&$this->isgzip){
			if(ini_get('output_handler'))return false;
			$a=ob_list_handlers();
			if(in_array('ob_gzhandler',$a) || in_array('zlib output compression',$a))return false;
			if(function_exists('ini_set')){
				ini_set('zlib.output_compression', 'On');
				ini_set('zlib.output_compression_level', '5');
			}elseif(function_exists('ob_gzhandler')){
				ob_start('ob_gzhandler');
			}
			ob_start();
			$this->isgziped=true;
			return true;
		}
	} 
	/**
	 * 验证用户登录(COOKIE中的用户名密码)
	 * @return bool
	 */
	public function verify(){
		return $this->verify_MD5Path(getVars('username','COOKIE'),getVars('password','COOKIE'));
	}

	/**
	 * 验证用户登录（二次MD5密码）
	 * @param string $name 用户名
	 * @param string $ps_and_path 二次md5加密后的密码
	 * @return bool
	 */
	public function verify_MD5Path($name,$ps_and_path){
		$rsName = $this->getList('user',null,array(array('=','name',$name)));
		if(empty($rsName[0])){
			return false;
		}
		$this->user = (object)$rsName[0];
		if(md5($this->user->password . $this->user->guid) == $ps_and_path){
			return true;
		}else{
			return false;
		}
	 
	}

	/**
	 *验证是否能访问这个url
	 *@param string $act
	 *@return exit
	 */
	public function checkAction($act){
		if(array_key_exists($act, $this->actions)){
			if($this->user->level>$this->actions[$act]){
				$this->error('权限不足');
			}
		}else{
			$this->error('没有这个url');
		}
	}
	/**
	 *错误信息显示
	 *@param string $msg
	 *@return null
	 */
	public function error($msg){
		exit($msg);
	}
	/**
	 * 获取用户列表
	 *@param array $filed 
	 *@param array $where or string $where
	 *@param string $order 
	 *@param string $limit
	 *@param string $table
	 *@return array
	 */
	public function getList($table,$field=null,$where=null,$order=null,$limit=null){
		$sql = $this->db->sql->select($table,$field,$where,$order,$limit);
		$array=$this->db->query($sql);
		return $array ;
	}
	/**
	 * 验证用户登录（一次MD5密码）
	 * @param string $name 用户名 （数据库中用户名必须唯一）
	 * @param string $md5pw md5加密后的密码
	 * @return bool  
	 */
	public function verify_MD5($name,$md5pw){
		$rsName = $this->getList('user',null,array(array('=','name',$name)));
		if(empty($rsName[0])){
			return false;
		}
		$this->user = (object)$rsName[0];
		return $this->verify_Final($name,md5($md5pw . $this->user->guid));
	}

	/**
	 * 验证用户登录（加盐的密码）
	 * @param string $name 用户名
	 * @param string $originalpw 密码明文与Guid连接后的字符串
	 * @return bool
	 */
	public function verify_Original($name,$originalpw){
		return $this->verify_MD5($name,md5($originalpw));
	}

	/**
	 * 验证用户登录
	 * @param string $name 用户名
	 * @param string $password 二次加密后的密码
	 * @return bool
	 */
	public function verify_Final($name,$password){
		if(strcasecmp ( $this->user->password ,  $password ) ==  0){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 获取文章类别
	 * @return array ;
	 */
	 public function getArtCate(){
		$cates = $this->getList('category',null,null,"'order' ASC",null);
		return $cates;
	 }
	/**
	 * 获取文章标签
	 * @return array ;
	 */
	 public function getTags(){
		$cates = $this->getList('tag',null,null,"'order' ASC",null);
		return $cates;
	 }
	 /**
	  *保存信息
	  *@param $table string
	  *@param $data array 
	  *@return boolean
	  */
	 public function add($table,$data)
	 {
	 	if(is_array($data)){
	 		$sqlAdd = $this->db->sql->insert($table,$data);
			$addResult = $this->db->query($sqlAdd);
	 		if($addResult){
	 			$where = array();
	 			foreach ($data as $key => $value) {
	 				$where[] = array('=',$key,$value);
	 			}
	 			$ids = $this->getList($table,'id',$where);
	 			return $ids[0]['id'];
	 		}else{
	 			return false;
	 		}
	 	}else{
	 		return false;
	 	}
	 }

	 public function save($table,$data,$id)
	 {

	 }













}
?>