<?php
/**
 * blog全局操作类
 */
error_reporting(E_ALL);

class ABlog {
	private static $_blog=null;
	public $version=null;
	public $db = null;
	public $config = array();
	public $lang = array();
	public $host = null;
	public $cookiespath=null;
	public $currenturl=null;
	public $validcodeurl = null;
	public $configs=array();
	public $title=null;
	public $name=null;
	public $subname=null;
	public $blogpath;
	public $userdir;
	public $user=null;
	private $isinitialize=false; #是否初始化成功
	private $isconnect=false; #是否连接成功
	private $isload=false; #是否载入
	private $issession=false; #是否使用session
	public $ismanage=false; #是否管理员
	private $isgzip=false; #是否开启gzip
	private $isgziped=false; #是否已经过gzip压缩
 
	

	/**
	 * 获取唯一实例
	 * @return null|ABlog
	 */
	static public function getInstance(){
		if(!isset(self::$_blog)){
			self::$_blog=new ABlog;
		}
		return self::$_blog;
	}

	/**
	 * 构造函数，加载基本配置到$blog
	 */
	function __construct() {
		global $config,$lang, $blogpath,$userdir,$bloghost,$cookiespath;
		global $blogtitle,$blogname,$blogsubname,$currenturl;
		//基本配置加载到$blog内
		$this->config = &$config;
		$this->blogpath = &$blogpath;
		$this->userdir = &$userdir;
		$this->lang = &$lang;
		$this->host = &$bloghost;
		$this->cookiespath = &$cookiespath;
		$this->currenturl = &$currenturl;
		$this->title = &$blogtitle;
		$this->name = &$blogname;
		$this->subname = &$blogsubname;
	}


	/**
	 *析构函数，释放资源
	 */
	function __destruct(){
		$this->terminate();
	}

	/**
	 * @param $method
	 * @param $args
	 * @return mixed
	 */
	function __call($method, $args) {
	}

	/**
	 * 设置参数值
	 * @param $name
	 * @param $value
	 * @return mixed
	 */
	function __set($name, $value){
	}

	/**
	 * 获取参数值
	 * @param $name
	 * @return mixed
	 */
	function __get($name){
	  //
	}


	/**
	 * 初始化$blog
	 * @return bool
	 */
	public function initialize(){
		$oldzone=$this->config['A_TIME_ZONE_NAME'];
		date_default_timezone_set($oldzone);
		$oldlang=$this->config['A_BLOG_LANGUAGEPACK'];
		$this->lang = require($this->blogpath . 'myblog/language/' . $oldlang . '.php');
		if(!$this->openConnect())
		{
			exit('数据库连接失败');
		}

		$this->LoadConfigs();
		$this->LoadCache();
		$this->LoadOption();
		$this->validcodeurl=$this->host . 'system/script/c_validcode.php';
		$this->user= get_current_user();
		$this->isinitialize=true;

	}


	/**
	 * 重建索引并载入
	 * @return bool
	 */
	public function Load(){
		if(!$this->isinitialize)return false;
		if($this->isload)return false;
		$this->StartGzip();
  
		$this->isload=true;

		return true;
	}

	 

	/**
	 *终止连接，释放资源
	 */
	public function terminate(){
		if($this->isinitialize){
			$this->CloseConnect();
			unset($this->db);
			$this->isinitialize=false;
		}
	}


	/**
	 * 初始化数据库连接
	 * @param string $type 数据连接类型
	 * @return bool
	 */
	public function initializeDB($type){
		if(!trim($type))return false;
		$newtype='Db'.trim($type);
		$this->db=new $newtype();
	}

	/**
	 * 连接数据库
	 * @return bool
	 * @throws Exception
	 */
	public function openConnect(){
		if($this->isconnect)return false;
		if(!$this->config['A_DATABASE_TYPE'])return false;
		switch ($this->config['A_DATABASE_TYPE']) {
			case 'mysql':
			case 'mysqli':
			case 'pdo_mysql':
			default:
				try {
					$this->initializeDB($this->config['A_DATABASE_TYPE']);
					if($this->db->Open(array(
							$this->config['A_MYSQL_SERVER'],
							$this->config['A_MYSQL_USERNAME'],
							$this->config['A_MYSQL_PASSWORD'],
							$this->config['A_MYSQL_NAME'],
							$this->config['A_MYSQL_PRE'],
							$this->config['A_MYSQL_PORT'],
							$this->config['A_MYSQL_PERSISTENT']
						))==false){
						$this->ShowError(67,__FILE__,__LINE__);
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
	 * 关闭数据库连接
	 */
	public function CloseConnect(){
		if($this->isconnect){
			$this->db->Close();
			$this->isconnect=false;
		}
	}


	/**
	 * 启用session
	 * @return bool
	 */
	public function StartSession(){
		if($this->issession==true)return false;
		session_start();
		$this->issession=true;
		return true;
	}


	/**
	 * 终止session
	 * @return bool
	 */
	public function EndSession(){
		if($this->issession==false)return false;
		session_unset();
		session_destroy();
		$this->issession=false;
		return true;
	}

#插件用Configs表相关设置函数

	/**
	 * 载入插件Configs表
	 */
	public function LoadConfigs(){

		$this->configs=array();
		$sql = $this->db->sql->Select('config',array('*'),'','','','');
		$array=$this->db->Query($sql);
		foreach ($array as $c) {
			$m=new Metas;
			$m->Unserialize($c['conf_Value']);
			$this->configs[$c['conf_Name']]=$m;
		}
	}

	/**
	 * 删除Configs表
	 * @param string $name Configs表名
	 * @return bool
	 */
	public function DelConfig($name){
		$sql = $this->db->sql->Delete($this->table['Config'],array(array('=','conf_Name',$name)));
		$this->db->Delete($sql);
		return true;
	}

	/**
	 * 保存Configs表
	 * @param string $name Configs表名
	 * @return bool
	 */
	public function SaveConfig($name){

		if(!isset($this->configs[$name]))return false;

		$kv=array('conf_Name'=>$name,'conf_Value'=>$this->configs[$name]->Serialize());
		$sql = $this->db->sql->Select($this->table['Config'],array('*'),array(array('=','conf_Name',$name)),'','','');
		$array=$this->db->Query($sql);

		if(count($array)==0){
			$sql = $this->db->sql->Insert($this->table['Config'],$kv);
			$this->db->Insert($sql);
		}else{
			array_shift($kv);
			$sql = $this->db->sql->Update($this->table['Config'],$kv,array(array('=','conf_Name',$name)));
			$this->db->Update($sql);
		}

		return true;
	}

	/**
	 * 获取Configs表值
	 * @param string $name Configs表名
	 * @return mixed
	 */
	public function Config($name){
		if(!isset($this->configs[$name])){
			$m=new Metas;
			$this->configs[$name]=$m;
		}
		return $this->configs[$name];
	}

#Cache相关

	/**
	 * 保存缓存
	 * @return bool
	 */
	public function SaveCache(){
		#$s=$this->usersdir . 'cache/' . $this->guid . '.cache';
		#$c=serialize($this->cache);
		#@file_put_contents($s, $c);
		//$this->configs['cache']=$this->cache;
		$this->SaveConfig('cache');
		return true;
	}

	/**
	 * 加载缓存
	 * @return bool
	 */
	public function LoadCache(){
		#$s=$this->usersdir . 'cache/' . $this->guid . '.cache';
		#if (file_exists($s))
		#{
		#	$this->cache=unserialize(@file_get_contents($s));
		#}
		$this->cache=$this->Config('cache');
		return true;
	}

################################################################################################################
#保存blog设置函数

	/**
	 * 保存配置
	 * @return bool
	 */
	public function SaveOption(){

		$this->config['A_BLOG_CLSID']=$this->guid;

		if( strpos('|SAE|BAE2|ACE|TXY|', '|'.$this->config['A_YUN_SITE'].'|')===false ){
			$s="<?php\r\n";
			$s.="return ";
			$s.=var_export($this->config,true);
			$s.="\r\n?>";
			@file_put_contents($this->usersdir . 'c_option.php',$s);
		}

		foreach ($this->config as $key => $value) {
			$this->Config('system')->$key = $value;
		}
		$this->SaveConfig('system');
		return true;
	}


	/**
	 * 载入配置
	 * @return bool
	 */
	public function LoadOption(){

		$array=$this->Config('system')->Data;

		if(empty($array))return false;
		if(!is_array($array))return false;
		foreach ($array as $key => $value) {
			//if($key=='A_PERMANENT_DOMAIN_ENABLE')continue;
			//if($key=='A_BLOG_HOST')continue;
			//if($key=='A_BLOG_CLSID')continue;
			//if($key=='A_BLOG_LANGUAGEPACK')continue;
			if($key=='A_YUN_SITE')continue;
			if($key=='A_DATABASE_TYPE')continue;
			if($key=='A_SQLITE_NAME')continue;
			if($key=='A_SQLITE_PRE')continue;
			if($key=='A_MYSQL_SERVER')continue;
			if($key=='A_MYSQL_USERNAME')continue;
			if($key=='A_MYSQL_PASSWORD')continue;
			if($key=='A_MYSQL_NAME')continue;
			if($key=='A_MYSQL_CHARSET')continue;
			if($key=='A_MYSQL_PRE')continue;
			if($key=='A_MYSQL_ENGINE')continue;
			if($key=='A_MYSQL_PORT')continue;
			if($key=='A_MYSQL_PERSISTENT')continue;
			if($key=='A_SITE_TURNOFF')continue;			
			$this->config[$key]=$value;
		}
		return true;
	}

 
#权限及验证类

	/**
	 * 验证操作权限
	 * @param string $action 操作
	 * @return bool
	 */
	function CheckRights($action){
		if(!isset($this->actions[$action])){
			if(is_numeric($action)){
				if ($this->user->Level > $action) {
					return false;
				} else {
					return true;
				}
			}
		}else{
			if ($this->user->Level > $this->actions[$action]) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * 根据用户等级验证操作权限
	 * @param int $level 用户等级
	 * @param string $action 操作
	 * @return bool
	 */
	function CheckRightsByLevel($level,$action){
		if(is_int($action)){
			if ($level > $action) {
				return false;
			} else {
				return true;
			}
		}

		if ($level > $this->actions[$action]) {
			return false;
		} else {
			return true;
		}

	}

	/**
	 * 验证用户登录(COOKIE中的用户名密码)
	 * @return bool
	 */
	public function Verify(){
		return $this->Verify_MD5Path(GetVars('username','COOKIE'),GetVars('password','COOKIE'));
	}

	/**
	 * 验证用户登录（二次MD5密码）
	 * @param string $name 用户名
	 * @param string $ps_and_path 二次md5加密后的密码
	 * @return bool
	 */
	public function Verify_MD5Path($name,$ps_and_path){
		if (isset($this->membersbyname[$name])){
			$m=$this->membersbyname[$name];
			if(md5($m->Password . $this->guid) == $ps_and_path){
				$this->user=$m;
				return true;
			}else{
				return false;
			}
		}
	}

	/**
	 * 验证用户登录（一次MD5密码）
	 * @param string $name 用户名
	 * @param string $md5pw md5加密后的密码
	 * @return bool
	 */
	public function Verify_MD5($name,$md5pw){
		if (isset($this->membersbyname[$name])){
			$m=$this->membersbyname[$name];
			return $this->Verify_Final($name,md5($md5pw . $m->Guid));
		}else{
			return false;
		}
	}

	/**
	 * 验证用户登录（加盐的密码）
	 * @param string $name 用户名
	 * @param string $originalpw 密码明文与Guid连接后的字符串
	 * @return bool
	 */
	public function Verify_Original($name,$originalpw){
		return $this->Verify_MD5($name,md5($originalpw));
	}

	/**
	 * 验证用户登录
	 * @param string $name 用户名
	 * @param string $password 二次加密后的密码
	 * @return bool
	 */
	public function Verify_Final($name,$password){
		if (isset($this->membersbyname[$name])){
			$m=$this->membersbyname[$name];
			if(strcasecmp ( $m->Password ,  $password ) ==  0){
				$this->user=$m;
				return true;
			}else{
				return false;
			}
		}
	}









################################################################################################################
#
	/**
	 * 生成模块
	 */
	function BuildModule(){


		foreach ($this->readymodules as $modfilename) {
			if(isset($this->modulesbyfilename[$modfilename])){
				if(isset($this->readymodules_function[$modfilename])){
					$m=$this->modulesbyfilename[$modfilename];
					if($m->NoRefresh==true)continue;
					if(function_exists($this->readymodules_function[$modfilename])){
						if(!isset($this->readymodules_parameters[$modfilename])){
							$m->Content=call_user_func($this->readymodules_function[$modfilename]);
						}else{
							$m->Content=call_user_func($this->readymodules_function[$modfilename],$this->readymodules_parameters[$modfilename]);
						}
					}
					$m->Save();
				}
			}
		}

	}

	/**
	 * 重建模块
	 * @param string $modfilename 模块名
	 * @param string $userfunc 用户函数
	 */
	function RegBuildModule($modfilename,$userfunc){
		$this->readymodules_function[$modfilename]=$userfunc;
	}

	/**
	 * 添加模块
	 * @param string $modfilename 模块名
	 * @param null $parameters 模块参数
	 */
	function AddBuildModule($modfilename,$parameters=null){
		$this->readymodules[$modfilename]=$modfilename;
		$this->readymodules_parameters[$modfilename]=$parameters;
	}

	/**
	 * 删除模块
	 * @param string $modfilename 模块名
	 */
	function DelBuildModule($modfilename){
		unset($this->readymodules[$modfilename]);
		unset($this->readymodules_function[$modfilename]);
		unset($this->readymodules_parameters[$modfilename]);
	}

	/**
	 * 所有模块重置
	 */
	function AddBuildModuleAll(){
		$m=array('catalog','calendar','comments','previous','archives','navbar','tags','authors');
		foreach ($m as $key => $value) {
			$this->readymodules[$value]=$value;
		}
	}

 

	/**
	 *载入用户列表
	 */
	public function LoadMembers(){

		$array=$this->GetMemberList();
		foreach ($array as $m) {
			$this->members[$m->ID]=$m;
			$this->membersbyname[$m->Name]=&$this->members[$m->ID];
		}
	}

	/**
	 * 载入分类列表
	 * @return bool
	 */
	public function LoadCategorys(){

		$lv0=array();
		$lv1=array();
		$lv2=array();
		$lv3=array();
		$array=$this->GetCategoryList(null,null,array('cate_Order'=>'ASC'),null,null);
		if(count($array)==0)return false;
		foreach ($array as $c) {
			$this->categorys[$c->ID]=$c;
		}
		foreach ($this->categorys as $id=>$c) {
			$l='lv' . $c->Level;
			${$l}[$c->ParentID][]=$id;
		}

		if(count($lv0)>0)$this->categorylayer=1;
		if(count($lv1)>0)$this->categorylayer=2;
		if(count($lv2)>0)$this->categorylayer=3;
		if(count($lv3)>0)$this->categorylayer=4;

		foreach ($lv0[0] as $id0) {
			$this->categorysbyorder[$id0]=&$this->categorys[$id0];
			if(!isset($lv1[$id0])){continue;}
			foreach ($lv1[$id0] as $id1) {
				if($this->categorys[$id1]->ParentID==$id0){
					$this->categorys[$id0]->SubCategorys[]=$this->categorys[$id1];
					$this->categorysbyorder[$id1]=&$this->categorys[$id1];
					if(!isset($lv2[$id1])){continue;}
					foreach ($lv2[$id1] as $id2) {
						if($this->categorys[$id2]->ParentID==$id1){
							$this->categorys[$id0]->SubCategorys[]=$this->categorys[$id2];
							$this->categorys[$id1]->SubCategorys[]=$this->categorys[$id2];
							$this->categorysbyorder[$id2]=&$this->categorys[$id2];
							if(!isset($lv3[$id2])){continue;}
							foreach ($lv3[$id2] as $id3) {
								if($this->categorys[$id3]->ParentID==$id2){
									$this->categorys[$id0]->SubCategorys[]=$this->categorys[$id3];
									$this->categorys[$id1]->SubCategorys[]=$this->categorys[$id3];
									$this->categorys[$id2]->SubCategorys[]=$this->categorys[$id3];
									$this->categorysbyorder[$id3]=&$this->categorys[$id3];
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 *载入标签列表
	 */
	public function LoadTags(){

		$array=$this->GetTagList();
		foreach ($array as $t) {
			$this->tags[$t->ID]=$t;
			$this->tagsbyname[$t->Name]=&$this->tags[$t->ID];
		}

	}

	/**
	 * 载入模块列表
	 * @return null
	 */
	public function LoadModules(){

		$array=$this->GetModuleList();
		foreach ($array as $m) {
			$this->modules[]=$m;

			$this->modulesbyfilename[$m->FileName]=$m;
		}

		$dir=$this->usersdir . 'theme/' . $this->theme . '/include/';
		if(!file_exists($dir))return null;
		$files=GetFilesInDir($dir,'php');
		foreach ($files as $sortname => $fullname) {
			$m=new Module();
			$m->FileName=$sortname;
			$m->Content=file_get_contents($fullname);
			$m->Type='div';
			$m->Source='theme';
			$this->modules[]=$m;
			$this->modulesbyfilename[$m->FileName]=$m;
		}

	}

	/**
	 *载入当前主题
	 */
	public function LoadThemes(){
		$dirs=GetDirsInDir($this->usersdir . 'theme/');

		foreach ($dirs as $id) {
			$app = new App;
			if($app->LoadInfoByXml('theme',$id)==true){
				$this->themes[]=$app;
			}
		}

	}
  

################################################################################################################
#加载数据对像List函数

	/**
	 * 自定义查询语句获取数据库数据列表
	 * @param string $table 数据表
	 * @param string $datainfo 数据字段
	 * @param string $sql SQL操作语句
	 * @return array
	 */
	function GetListCustom($table,$datainfo,$sql){

		$array=null;
		$list=array();
		$array=$this->db->Query($sql);
		if(!isset($array)){return array();}
		foreach ($array as $a) {
			$l=new Base($table,$datainfo);
			$l->LoadInfoByAssoc($a);
			$list[]=$l;
		}
		return $list;
	}


	/**
	 * @param $type
	 * @param $sql
	 * @return array
	 */
	function GetList($type,$sql){

		$array=null;
		$list=array();
		$array=$this->db->Query($sql);
		if(!isset($array)){return array();}
		foreach ($array as $a) {
			$l=new $type();
			$l->LoadInfoByAssoc($a);
			$list[]=$l;
		}
		return $list;
	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetPostList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		if(empty($where)){$where = array();}
		$sql = $this->db->sql->Select($this->table['Post'],$select,$where,$order,$limit,$option);
		return $this->GetList('Post',$sql);

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @param bool $readtags
	 * @return array
	 */
	function GetArticleList($select=null,$where=null,$order=null,$limit=null,$option=null,$readtags=true){

		if(empty($select)){$select = array('*');}
		if(empty($where)){$where = array();}
		if(is_array($where))array_unshift($where,array('=','log_Type','0'));
		$sql = $this->db->sql->Select($this->table['Post'],$select,$where,$order,$limit,$option);
		$array = $this->GetList('Post',$sql);

		if($readtags){
			$tagstring = '';
			foreach ($array as $a) {
				$tagstring .= $a->Tag;
				$this->posts[$a->ID]=$a;
			}
			$this->LoadTagsByIDString($tagstring);
		}

		return $array;

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetPageList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		if(empty($where)){$where = array();}
		if(is_array($where))array_unshift($where,array('=','log_Type','1'));
		$sql = $this->db->sql->Select($this->table['Post'],$select,$where,$order,$limit,$option);
		$array = $this->GetList('Post',$sql);
		foreach ($array as $a) {
			$this->posts[$a->ID]=$a;
		}
		return $array;

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetCommentList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Comment'],$select,$where,$order,$limit,$option);
		$array=$this->GetList('Comment',$sql);
		foreach ($array as $comment) {
			$this->comments[$comment->ID]=$comment;
		}
		return $array;

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetMemberList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Member'],$select,$where,$order,$limit,$option);
		return $this->GetList('Member',$sql);

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetTagList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Tag'],$select,$where,$order,$limit,$option);
		return $this->GetList('Tag',$sql);

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetCategoryList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Category'],$select,$where,$order,$limit,$option);
		return $this->GetList('Category',$sql);

	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetModuleList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Module'],$select,$where,$order,$limit,$option);
		return $this->GetList('Module',$sql);
	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetUploadList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Upload'],$select,$where,$order,$limit,$option);
		return $this->GetList('Upload',$sql);
	}

	/**
	 * @param null $select
	 * @param null $where
	 * @param null $order
	 * @param null $limit
	 * @param null $option
	 * @return array
	 */
	function GetCounterList($select=null,$where=null,$order=null,$limit=null,$option=null){

		if(empty($select)){$select = array('*');}
		$sql = $this->db->sql->Select($this->table['Counter'],$select,$where,$order,$limit,$option);
		return $this->GetList('Counter',$sql);
	}


################################################################################################################
#wp类似

	/**
	 * @param $sql
	 * @return mixed
	 */
	function get_results($sql){
		return $this->db->Query($sql);
	}


################################################################################################################
#读取对象函数


	/**
	 * 通过ID获取文章实例
	 * @param int $id
	 * @return Post
	 */
	function GetPostByID($id){
		if($id==0)return new Post;
		if(isset($this->posts[$id])){
			return $this->posts[$id];
		}else{
			$p = new Post;
			$p->LoadInfoByID($id);
			$this->posts[$id]=$p;
			return $p;
		}
	}

	/**
	 * 通过ID获取分类实例
	 * @param int $id
	 * @return Category
	 */
	function GetCategoryByID($id){
		if(isset($this->categorys[$id])){
			return $this->categorys[$id];
		}else{
			return new Category;
		}
	}

	/**
	 * 通过分类名获取分类实例
	 * @param string $name
	 * @return Category
	 */
	function GetCategoryByName($name){
		$name=trim($name);
		foreach ($this->categorys as $key => &$value) {
			if($value->Name==$name){
				return $value;
			}
		}
		return new Category;
	}

	/**
	 * 通过分类别名获取分类实例
	 * @param string $name
	 * @return Category
	 */
	function GetCategoryByAliasOrName($name){
		$name=trim($name);
		foreach ($this->categorys as $key => &$value) {
			if(($value->Name==$name)||($value->Alias==$name)){
				return $value;
			}
		}
		return new Category;
	}

	/**
	 * 通过ID获取模块实例
	 * @param int $id
	 * @return Module
	 */
	function GetModuleByID($id){
		if($id==0){
			$m = new Module;
			return $m;
		}else{
			foreach ($this->modules as $key => $value) {
				if($value->ID==$id)return $value;
			}
			$m = new Module;
			return $m;
		}
	}

	/**
	 * 通过ID获取用户实例
	 * @param int $id
	 * @return Member
	 */
	function GetMemberByID($id){
		if(isset($this->members[$id])){
			return $this->members[$id];
		}
		$m = new Member;
		$m->Guid=GetGuid();
		return $m;
	}

	/**
	 * 通过用户获取用户实例
	 * @param string $name
	 * @return Member
	 */
	function GetMemberByAliasOrName($name){
		$name=trim($name);
		foreach ($this->members as $key => &$value) {
			if(($value->Name==$name)||($value->Alias==$name)){
				return $value;
			}
		}
		return new Member;
	}

	/**
	 * 通过ID获取评论实例
	 * @param int $id
	 * @return Comment
	 */
	function GetCommentByID($id){
		if(isset($this->comments[$id])){
			return $this->comments[$id];
		}else{
			$c = new Comment;
			if($id==0){
				return $c;
			}else{
				$c->LoadInfoByID($id);
				$this->comments[$id]=$c;
				return $c;
			}
		}
	}

	/**
	 * 通过ID获取附件实例
	 * @param int $id
	 * @return Upload
	 */
	function GetUploadByID($id){
		$m = new Upload;
		if($id>0){
			$m->LoadInfoByID($id);
		}
		return $m;
	}

	/**
	 * 通过ID获取审计类实例
	 * @param int $id
	 * @return Counter
	 */
	function GetCounterByID($id){
		$m = new Counter;
		if($id>0){
			$m->LoadInfoByID($id);
		}
		return $m;
	}

	/**
	 * 通过tag名获取tag实例
	 * @param string $name
	 * @return Tag
	 */
	function GetTagByAliasOrName($name){
		$a=array();
		$a[]=array('tag_Alias',$name);
		$a[]=array('tag_Name',$name);
		$array=$this->GetTagList('*',array(array('array',$a)),'',1,'');
		if(count($array)==0){
			return new Tag;
		}else{
			$this->tags[$array[0]->ID]=$array[0];
			$this->tagsbyname[$array[0]->ID]=&$this->tags[$array[0]->ID];
			return $this->tags[$array[0]->ID];
		}
	}

	/**
	 * 通过ID获取tag实例
	 * @param int $id
	 * @return Tag
	 */
	function GetTagByID($id){
		if(isset($this->tags[$id])){
			return $this->tags[$id];
		}else{
			$array=$this->GetTagList('',array(array('=','tag_ID',$id)),'',array(1),'');
			if(count($array)==0){
				return new Tag;
			}else{
				$this->tags[$array[0]->ID]=$array[0];
				$this->tagsbyname[$array[0]->ID]=&$this->tags[$array[0]->ID];
				return $this->tags[$array[0]->ID];
			}

		}
	}

	/**
	 * 通过类似'{1}{2}{3}{4}{4}'载入tags
	 * @param $s
	 * @return array
	 */
	function LoadTagsByIDString($s){
		if($s=='')return array();
		$s=str_replace('}{', '|', $s);
		$s=str_replace('{', '', $s);
		$s=str_replace('}', '', $s);
		$a=explode('|', $s);
		$b=array();
		foreach ($a as &$value) {
			$value = trim($value);
			if($value)$b[]=$value;
		}
		$t=array_unique($b);

		if(count($t)==0)return array();

		$a=array();
		$b=array();
		$c=array();
		foreach ($t as $v) {
			if(isset($this->tags[$v])==false){
				$a[]=array('tag_ID',$v);
				$c[]=$v;
			}else{
				$b[$v]=&$this->tags[$v];
			}
		}

		if(count($a)==0){
			return $b;
		}else{
			$t=array();
			//$array=$this->GetTagList('',array(array('array',$a)),'','','');
			$array=$this->GetTagList('',array(array('IN','tag_ID',$c)),'','','');
			foreach ($array as $v) {
				$this->tags[$v->ID]=$v;
				$this->tagsbyname[$v->Name]=&$this->tags[$v->ID];
				$t[$v->ID]=&$this->tags[$v->ID];
			}
			return $b+$t;
		}
	}

	/**
	 * 通过类似'aaa,bbb,ccc,ddd'载入tags
	 * @param string $s 标签名字符串，如'aaa,bbb,ccc,ddd
	 * @return array
	 */
	function LoadTagsByNameString($s){
		$s=str_replace(';', ',', $s);
		$s=str_replace('，', ',', $s);
		$s=str_replace('、', ',', $s);
		$s=trim($s);
		$s=strip_tags($s);
		if($s=='')return array();
		if($s==',')return array();
		$a=explode(',', $s);
		$t=array_unique($a);

		if(count($t)==0)return array();

		$a=array();
		$b=array();
		foreach ($t as $v) {
			if(isset($this->tagsbyname[$v])==false){
				$a[]=array('tag_Name',$v);
			}else{
				$b[$v]=&$this->tagsbyname[$v];
			}
		}

		if(count($a)==0){
			return $b;
		}else{
			$t=array();
			$array=$this->GetTagList('',array(array('array',$a)),'','','');
			foreach ($array as $v) {
				$this->tags[$v->ID]=$v;
				$this->tagsbyname[$v->Name]=&$this->tags[$v->ID];
				$t[$v->Name]=&$this->tags[$v->ID];
			}
			return $b+$t;
		}
	}

################################################################################################################
#杂项
	/**
	 * 验证评论key
	 * @param $id
	 * @param $key
	 * @return bool
	 */
	function VerifyCmtKey($id,$key){
		$nowkey=md5($this->guid . $id . date('Y-m-d'));
		$nowkey2=md5($this->guid . $id . date('Y-m-d',time()-(3600*24)));
		if($key==$nowkey||$key==$nowkey2){
			return true;
		}
	}

	/**
	 * 检查应用是否安装并启用
	 * @param string $name 应用（插件或主题）的ID
	 * @return bool
	 */
	function CheckPlugin($name){
		//$s=$this->config['A_BLOG_THEME'] . '|' . $this->config['A_USING_PLUGIN_LIST'];
		//return HasNameInString($s,$name);
		return in_array($name,$this->activeapps);
	}
	
	/**
	 * 检查应用是否安装并启用
	 * @param string $name 应用ID（插件或主题）
	 * @return bool
	 */
	function CheckApp($name){
		return $this->CheckPlugin($name);
	}

	#$type=category,tag,page,item
	/**
	 * 向导航菜单添加相应条目
	 * @param string $type $type=category,tag,page,item
	 * @param string $id
	 * @param string $name
	 * @param string $url
	 */
	function AddItemToNavbar($type='item',$id,$name,$url){

		if(!$type)$type='item';
		$m=$this->modulesbyfilename['navbar'];
		$s=$m->Content;

		$a='<li id="navbar-'.$type.'-'.$id.'"><a href="'.$url.'">'.$name.'</a></li>';

		if($this->CheckItemToNavbar($type,$id)){
			$s=preg_replace('/<li id="navbar-'.$type.'-'.$id.'">.*?<\/li>/', $a, $s);
		}else{
			$s.='<li id="navbar-'.$type.'-'.$id.'"><a href="'.$url.'">'.$name.'</a></li>';
		}


		$m->Content=$s;
		$m->Save();

	}

	/**
	 * 删除导航菜单中相应条目
	 * @param string $type
	 * @param $id
	 */
	function DelItemToNavbar($type='item',$id){

		if(!$type)$type='item';
		$m=$this->modulesbyfilename['navbar'];
		$s=$m->Content;

		$s=preg_replace('/<li id="navbar-'.$type.'-'.$id.'">.*?<\/li>/', '', $s);

		$m->Content=$s;
		$m->Save();

	}

	/**
	 * 检查条目是否在导航菜单中
	 * @param string $type
	 * @param $id
	 * @return bool
	 */
	function CheckItemToNavbar($type='item',$id){

		if(!$type)$type='item';
		$m=$this->modulesbyfilename['navbar'];
		$s=$m->Content;
		return (bool)strpos($s,'id="navbar-'.$type.'-'.$id.'"');

	}

	#$signal = good,bad,tips
	private $hint1=null,$hint2=null,$hint3=null,$hint4=null,$hint5=null;
	/**
	 * 设置提示消息并存入Cookie
	 * @param string $signal 提示类型（good|bad|tips）
	 * @param string $content 提示内容
	 */
	function SetHint($signal,$content=''){
		if($content==''){
			if($signal=='good')$content=$this->lang['msg']['operation_succeed'];
			if($signal=='bad')$content=$this->lang['msg']['operation_failed'];
		}
		if($this->hint1==null){
			$this->hint1=$signal . '|' . $content;
			setcookie("hint_signal1", $signal . '|' . $content,time()+3600,$this->cookiespath);
		}elseif($this->hint2==null){
			$this->hint2=$signal . '|' . $content;
			setcookie("hint_signal2", $signal . '|' . $content,time()+3600,$this->cookiespath);
		}elseif($this->hint3==null){
			$this->hint3=$signal . '|' . $content;
			setcookie("hint_signal3", $signal . '|' . $content,time()+3600,$this->cookiespath);
		}elseif($this->hint4==null){
			$this->hint4=$signal . '|' . $content;
			setcookie("hint_signal4", $signal . '|' . $content,time()+3600,$this->cookiespath);
		}elseif($this->hint5==null){
			$this->hint5=$signal . '|' . $content;
			setcookie("hint_signal5", $signal . '|' . $content,time()+3600,$this->cookiespath);
		}
	}

	/**
	 * 提取Cookie中的提示消息
	 */
	function GetHint(){
		for ($i = 1; $i <= 5; $i++) {
			$signal=GetVars('hint_signal' . $i,'COOKIE');
			if($signal){
				$a=explode('|', $signal);
				$this->ShowHint($a[0],$a[1]);
				setcookie("hint_signal" . $i , '',time()-3600,$this->cookiespath);
			}
		}
	}

	/**
	 * 显示提示消息
	 * @param string $signal 提示类型（good|bad|tips）
	 * @param string $content 提示内容
	 */
	function ShowHint($signal,$content=''){
		if($content==''){
			if($signal=='good')$content=$this->lang['msg']['operation_succeed'];
			if($signal=='bad')$content=$this->lang['msg']['operation_failed'];
		}
		echo "<div class='hint'><p class='hint hint_$signal'>$content</p></div>";
	}

	/**
	 * 显示错误信息
	 * @api Filter_Plugin_blog_ShowError
	 * @param $idortext
	 * @param null $file
	 * @param null $line
	 * @return mixed
	 * @throws Exception
	 */
	function ShowError($idortext,$file=null,$line=null){

		if((int)$idortext==2){
			Http404();
		}

	 
		throw new Exception($idortext);
	}

	/**
	 * 获取会话Token
	 * @return string
	 */
	function GetToken(){
		return md5($this->guid . date('Ymd') . $this->user->Name . $this->user->Password);
	}

	/**
	 * 验证会话Token
	 * @param $t
	 * @return bool
	 */
	function ValidToken($t){
		if($t==md5($this->guid . date('Ymd') . $this->user->Name . $this->user->Password)){
			return true;
		}
		if($t==md5($this->guid . date('Ymd',strtotime("-1 day")) . $this->user->Name . $this->user->Password)){
			return true;
		}
		return false;
	}

	/**
	 * 显示验证码
	 *
	 * @api Filter_Plugin_blog_ShowValidCode 如该接口未被挂载则显示默认验证图片
	 * @param string $id 页面ID
	 * @return mixed
	 */
	function ShowValidCode($id=''){

		 

		$_vc = new ValidateCode();
		$_vc->GetImg();
		setcookie('blogvalidcode' . md5($this->guid . $id), md5( $this->guid . date("Ymd") . $_vc->GetCode() ), null,$this->cookiespath);
	}


	/**
	 * 比对验证码
	 *
	 * @api Filter_Plugin_blog_CheckValidCode 如该接口未被挂载则比对默认验证码
	 * @param string $vaidcode 验证码数值
	 * @param string $id 页面ID
	 * @return bool
	 */
	function CheckValidCode($vaidcode,$id=''){
		$vaidcode = strtolower($vaidcode);
	 

		$original=GetVars('blogvalidcode' . md5($this->guid . $id),'COOKIE');
		if(md5( $this->guid . date("Ymd") . $vaidcode)==$original) return true;
	}


	/**
	 * 检查并开启Gzip压缩
	 */
	function CheckGzip(){
		if(	extension_loaded("zlib")&&
			isset($_SERVER["HTTP_ACCEPT_ENCODING"])&&
			strstr($_SERVER["HTTP_ACCEPT_ENCODING"],"gzip")
			)
			$this->isgzip=true;
	}

	/**
	 * 启用Gzip
	 */
	function StartGzip(){
		if($this->isgziped)return false;

		if(!headers_sent()&&$this->isgzip&&isset($this->config['A_GZIP_ENABLE'])&&$this->config['A_GZIP_ENABLE']){
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
	 * 跳转到安装页面
	 * @param bool $yun 是否云主机（SAE等）
	 */
	// function  RedirectInstall($yun=false){
	// 	if(!$yun){
	// 		if(!$this->config['A_DATABASE_TYPE']){Redirect('./zb_install/index.php');}
	// 	}else{
	// 		if($this->config['A_YUN_SITE']){
	// 			if($this->Config('system')->CountItem()==0){Redirect('./zb_install/index.php');}
	// 		}
	// 	}
	// }
	
}