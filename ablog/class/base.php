<?php
/**
 * @descrition 数据操作基类
 * @package a-blog
 * @subpackage base 作为操作父类
 */
class Base {

	/**
	* @var string 数据表
	*/
	protected $table='';
	/**
	* @var array 表结构信息
	*/
	protected $datainfo = array();
	/**
	* @var array 数据
	*/
	protected $data = array();

	/**
	* @var Metas|null 扩展元数据
	*/
	public $Metas = null;

	/**
	* @param string $table 数据表
	* @param array $datainfo 数据表结构信息
	*/
	function __construct($table){
		global $ablog;
		$this->table=$table;
	}

	/**
	* @param $name
	* @param $value
	*/
	public function __set($name, $value){
		$this->data[$name]  =  $value;
	}

	/**
	* @param $name
	* @return mixed
	*/
	public function __get($name){
		return $this->data[$name];
	}

	/**
	* @param $name
	* @return bool
	*/
	public function __isset($name){
		return isset($this->data[$name]);
	}

	/**
	* @param $name
	*/
	public function  __unset($name){
		unset($this->data[$name]);
	}

	/**
	* 获取数据库数据
	* @return array
	*/
	function GetData(){
		return $this->data;
	}

	/**
	* 获取数据表 
	* @return string
	*/
	function GetTable(){
		return $this->table;
	}

	/**
	* 获取表结构
	* @return array
	*/
	function GetDataInfo(){
		return $this->datainfo;
	}

	/**
	* 从数据库加载实例数据
	* @param int $id 实例ID
	* @return bool
	*/
	function LoadInfoByID($id){
		global $ablog;

		$id=(int)$id;
		$id_field=reset($this->datainfo);
		$id_field=$id_field[0];
		$s = $ablog->db->sql->Select($this->table,array('*'),array(array('=',$id_field,$id)),null,null,null);
		$array = $ablog->db->Query($s);
		if (count($array)>0) {
			$this->LoadInfoByAssoc($array[0]);
			return true;
		}else{
			return false;
		}
	}

	/**
	* 从关联数组中加载实例数据
	* @param array $array 关联数组
	* @return bool
	*/
	function LoadInfoByAssoc($array){
		global $ablog;

		foreach ($this->datainfo as $key => $value) {
			if(!isset($array[$value[0]]))continue;
			if($value[1] == 'boolean'){
				$this->data[$key]=(boolean)$array[$value[0]];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$this->data[$key]=$array[$value[0]];
				}else{
					$this->data[$key]=str_replace('{#ZC_BLOG_HOST#}',$ablog->host,$array[$value[0]]);
				}
			}else{
				$this->data[$key]=$array[$value[0]];
			}
		}
		if(isset($this->data['Meta']))$this->Metas->Unserialize($this->data['Meta']);
		return true;
	}

	/**
	* 从数组中加载实例数据
	* @param $array
	* @return bool
	*/
	function LoadInfoByArray($array){
		global $ablog;

		$i = 0;
		foreach ($this->datainfo as $key => $value) {
			if(count($array)==$i)continue;
			if($value[1] == 'boolean'){
				$this->data[$key]=(boolean)$array[$i];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$this->data[$key]=$array[$i];
				}else{
					$this->data[$key]=str_replace('{#ZC_BLOG_HOST#}',$ablog->host,$array[$i]);
				}
			}else{
				$this->data[$key]=$array[$i];
			}
			$i += 1;
		}
		if(isset($this->data['Meta']))$this->Metas->Unserialize($this->data['Meta']);
		return true;
	}

	/**
	* 保存数据
	*
	* 保存实例数据到$ablog及数据库中
	* @return bool
	*/
	function Save(){
		global $ablog;

		if(isset($this->data['Meta']))$this->data['Meta'] = $this->Metas->Serialize();

		$keys=array();
		foreach ($this->datainfo as $key => $value) {
			if(!is_array($value) || count($value)!=4)continue;
			$keys[]=$value[0];
		}
		$keyvalue=array_fill_keys($keys, '');

		foreach ($this->datainfo as $key => $value) {
			if(!is_array($value)|| count($value)!=4)continue;
			if($value[1]=='boolean'){
				$keyvalue[$value[0]]=(integer)$this->data[$key];
			}elseif($value[1] == 'integer'){
				$keyvalue[$value[0]]=(integer)$this->data[$key];
			}elseif($value[1] == 'float'){
				$keyvalue[$value[0]]=(float)$this->data[$key];
			}elseif($value[1] == 'double'){
				$keyvalue[$value[0]]=(double)$this->data[$key];
			}elseif($value[1] == 'string'){
				if($key=='Meta'){
					$keyvalue[$value[0]]=$this->data[$key];
				}else{
					$keyvalue[$value[0]]=str_replace($ablog->host,'{#ZC_BLOG_HOST#}',$this->data[$key]);
				}
			}else{
				$keyvalue[$value[0]]=$this->data[$key];
			}
		}
		array_shift($keyvalue);

		$id_field=reset($this->datainfo);
		$id_name=key($this->datainfo);
		$id_field=$id_field[0];
		
		if ($this->$id_name  ==  0) {
			$sql = $ablog->db->sql->Insert($this->table,$keyvalue);
			$this->$id_name = $ablog->db->Insert($sql);
		} else {
			$sql = $ablog->db->sql->Update($this->table,$keyvalue,array(array('=',$id_field,$this->$id_name)));
			return $ablog->db->Update($sql);
		}

		return true;
	}

	/**
	* 删除数据
	*
	* 从$ablog及数据库中删除该实例数据
	* @return bool
	*/
	function Del(){
		global $ablog;
		$id_field=reset($this->datainfo);
		$id_name=key($this->datainfo);
		$id_field=$id_field[0];
		$sql = $ablog->db->sql->Delete($this->table,array(array('=',$id_field,$this->$id_name)));
		$ablog->db->Delete($sql);
		return true;
	}

}