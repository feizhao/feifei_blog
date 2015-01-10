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
	private $isgzip=false; #是否开启gzip
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
		$this->user = new User;
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








}
?>