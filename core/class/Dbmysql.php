<?php
/**
 * MySQL数据库操作类
 *
 * @package core
 * @subpackage DataBase 类库
 */
class Dbmysql implements IdbBase {

	/**
	* @var string|null SQL语句分隔符
	*/
	public $dbpre = null;
	/**
	* @var string|null 数据库服务器
	*/
	private $db = null;
	/**
	* @var string|null 数据库名
	*/
	public $dbname = null;
	/**
	* @var DbSql|null 
	*/
	public $sql=null;
	/**
	* 构造函数，实例化$sql参数
	*/
	function __construct()
	{
		$this->sql=new DbSql($this);
	}

	/**
	* @param $s
	* @return string
	*/
	public function escapeString($s){
		return addslashes($s);
	}

	/**
	* @param $array
	* @return bool
	*/
	function open($array){
		/*$array=array(
			'dbmysql_server',
			'dbmysql_username',
			'dbmysql_password',
			'dbmysql_name',
			'dbmysql_pre',
			'dbmysql_port',
			'persistent'
		*/
		if($array[6]==false){
			$db_link = @mysql_connect($array[0] . ':' . $array[5], $array[1], $array[2]);
		}else{
			$db_link = @mysql_pconnect($array[0] . ':' . $array[5], $array[1], $array[2]);
		}

		if(!$db_link){
			return false;
		} else {
			$this->db = $db_link;
			mysql_query("SET NAMES 'utf8'",$db_link);
			if(mysql_select_db($array[3], $this->db)){
				$this->dbpre=$array[4];
				$this->dbname=$array[3];
				return true;
			} else {
				$this->close();
				return false;
			}
		}

	}

	/**
	* @param string $dbmysql_server
	* @param string $dbmysql_port
	* @param string $dbmysql_username
	* @param string $dbmysql_password
	* @param string $dbmysql_name
	*/
	function createDB($dbmysql_server,$dbmysql_port,$dbmysql_username,$dbmysql_password,$dbmysql_name){
		$db_link = @mysql_connect($dbmysql_server . ':' . $dbmysql_port, $dbmysql_username, $dbmysql_password);
		$this->db = $db_link;
		$this->dbname=$dbmysql_name;
		$s="SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$dbmysql_name'";
		$a=$this->query($s);
		$c=0;
		if(is_array($a)){
			$b=current($a);
			if(is_array($b)){
				$c=(int)current($b);
			}
		}
		if($c==0){
			mysql_query($this->sql->Filter('CREATE DATABASE ' . $dbmysql_name));
			return true;
		}
	}
	
	/**
	* 关闭数据库连接
	*/
	function close(){
		if(is_resource($this->db))
			mysql_close($this->db);
	}

	/**
	* 拼接SQL语句
	* @param $s 
	*/
	function queryMulit($s){
		//$a=explode(';',str_replace('%pre%', $this->dbpre,$s));
		$a=explode(';',$s);
		foreach ($a as $s) {
			$s=trim($s);
			if($s<>''){
				mysql_query($this->sql->Filter($s));				
			}
		}
	}

	/**
	* @param $query
	* @return array
	*/
	function query($query){
		//$query=str_replace('%pre%', $this->dbpre, $query);
		$results = mysql_query($this->sql->Filter($query));
		if(mysql_errno())trigger_error(mysql_error(),E_USER_NOTICE);
		$data = array();
		if(is_resource($results)){
			while($row = mysql_fetch_assoc($results)){
				$data[] = $row;
			}
		}else{
			$data[] = $results;
		}
		return $data;
	}

	/**
	* @param $query
	* @return resource
	*/
	function update($query){
		//$query=str_replace('%pre%', $this->dbpre, $query);
		return mysql_query($this->sql->Filter($query));
	}

	/**
	* 删除数据
	* @param $query
	* @return resource
	*/
	function delete($query){
		//$query=str_replace('%pre%', $this->dbpre, $query);
		return mysql_query($this->sql->Filter($query));
	}

	/**
	* 插入数据
	* @param $query
	* @return int 返回ID序列号
	*/
	function insert($query){
		//$query=str_replace('%pre%', $this->dbpre, $query);
		mysql_query($this->sql->Filter($query));
		return mysql_insert_id();
	}

	/**
	* 新建表
	* @param string $tablename 表名
	* @param string $datainfo 表结构
	*/
	function createTable($table,$datainfo){
		$this->queryMulit($this->sql->CreateTable($table,$datainfo));
	}

	/**
	* 删除表
	* @param string $table 表名
	*/
	function delTable($table){
		$this->queryMulit($this->sql->DelTable($table));
	}

	/**
	* 判断数据表是否存在
	* @param string $table 表名
	* @return bool
	*/
	function existTable($table){

		$a=$this->query($this->sql->existTable($table,$this->dbname));
		if(!is_array($a))return false;
		$b=current($a);
		if(!is_array($b))return false;
		$c=(int)current($b);
		if($c>0){
			return true;
		}else{
			return false;
		}
	}
}
