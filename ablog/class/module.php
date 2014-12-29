<?php
/**
 * 模块类
 *
 * @package Z-BlogPHP
 * @subpackage ClassLib/Article 类库
 */
class Module extends Base{

	/**
	 * 构造函数
	 */
	function __construct()
	{
		global $ablog;
		parent::__construct($ablog->table['Module'],$ablog->datainfo['Module']);
	}

	/**
	 * 设置参数值
	 * @param string $name
	 * @param mixed $value
	 * @return null
	 */
	public function __set($name, $value)
	{
		global $ablog;
		if ($name=='SourceType') {
			return null;
		}
		if ($name=='NoRefresh') {
			$n='module_norefresh_' . $this->FileName;
			if($value==true){
				$ablog->cache->$n=true;
				$ablog->SaveCache();
			}else{
				if($ablog->cache->HasKey($n)==true){
					$ablog->cache->Del($n);
					$ablog->SaveCache();
				}
			}
			return null;
		}
		parent::__set($name, $value);
	}

	/**
	 * 获取参数值
	 * @param $name
	 * @return bool|mixed|string
	 */
	public function __get($name)
	{
		global $ablog;
		if ($name=='SourceType') {
			if($this->Source=='system'){
				return 'system';
			}elseif($this->Source=='user'){
				return 'user';
			}elseif($this->Source=='theme'){
				return 'theme';
			}elseif($this->Source=='plugin_' . $ablog->theme){
				return 'theme';
			}else{
				return 'plugin';
			}
		}
		if ($name=='NoRefresh') {
			$n='module_norefresh_' . $this->FileName;
			if($ablog->cache->HasKey($n)==true){
				return true;
			}else{
				return false;
			}
		}
		return parent::__get($name);
	}

}
