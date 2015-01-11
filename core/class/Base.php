<?php
/**
 * 数据操作基类
 *
 * @package core
 * @subpackage class 类库
 */
class Base {

	/**
	* @var string 数据表
	*/
	protected $table='';
	/**
	* @var array 数据
	*/
	protected $data = array();
	/**
	* @param string $table 数据表
	*/
	function __construct(&$table){
		global $core;
		$this->table=&$table;
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
	function getData(){
		return $this->data;
	}

	/**
	* 获取数据表
	* @return string
	*/
	function getTable(){
		return $this->table;
	}
	/**
	* 保存数据
	*
	* 保存实例数据到$core及数据库中
	* @return bool
	*/
	function save(){
		global $core;

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
					$keyvalue[$value[0]]=str_replace($core->host,'{#ZC_BLOG_HOST#}',$this->data[$key]);
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
			$sql = $core->db->sql->Insert($this->table,$keyvalue);
			$this->$id_name = $core->db->Insert($sql);
		} else {

			$sql = $core->db->sql->Update($this->table,$keyvalue,array(array('=',$id_field,$this->$id_name)));
			return $core->db->Update($sql);
		}

		return true;
	}
 
	/**
	* 删除数据
	*
	* 从$core及数据库中删除该实例数据
	* @return bool
	*/
	function del(){
		global $core;
		$id_field=reset($this->datainfo);
		$id_name=key($this->datainfo);
		$id_field=$id_field[0];
		$sql = $core->db->sql->Delete($this->table,array(array('=',$id_field,$this->$id_name)));
		$core->db->Delete($sql);
		return true;
	}

}
