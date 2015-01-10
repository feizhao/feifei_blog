<?php
/**
 * 用户类
 *
 * @package core
 * @subpackage User 
 */
class User extends Base {

	/**
	 * @var string 头像图片地址
	 */
	private $_avatar='';

	/**
	 * 构造函数，默认用户设为anonymous
	 */
	function __construct()
	{
		$table = 'user';
		parent::__construct($table);
		global $core;
		$this->name = $core->lang['msg']['anonymous'];
	}

	/**
	 * 自定义函数
	 * @api Filter_Plugin_Member_Call
	 * @param $method
	 * @param $args
	 * @return mixed
	 */
	function __call($method, $args) {
		 
	}

	/**
	 * 自定义参数及值
	 * @param $name
	 * @param $value
	 * @return null|string
	 */
	public function __set($name, $value)
	{
		global $core;
		if ($name=='Url') {
			$u = new UrlRule($core->option['ZC_AUTHOR_REGEX']);
			$u->Rules['{%id%}']=$this->ID;
			$u->Rules['{%alias%}']=$this->Alias==''?urlencode($this->Name):$this->Alias;
			return $u->Make();
		}
		if ($name=='Avatar') {
			return null;
		}
		if ($name=='LevelName') {
			return null;
		}
		if ($name=='EmailMD5') {
			return null;
		}
		if ($name=='StaticName') {
			return null;
		}
		if ($name=='Template') {
			if($value==$core->option['ZC_INDEX_DEFAULT_TEMPLATE'])$value='';
			return $this->data[$name]  =  $value;
		}
		parent::__set($name, $value);
	}

	/**
	 * @param $name
	 * @return mixed|string
	 */
	public function __get($name)
	{
		global $core;
		if ($name=='Url') {
			$u = new UrlRule($core->option['ZC_AUTHOR_REGEX']);
			$u->Rules['{%id%}']=$this->ID;
			$u->Rules['{%alias%}']=$this->Alias==''?urlencode($this->Name):$this->Alias;
			return $u->Make();
		}
		if ($name=='Avatar') {
			foreach ($GLOBALS['Filter_Plugin_Mebmer_Avatar'] as $fpname => &$fpsignal) {
				$fpreturn=$fpname($this);
				if($fpreturn){$fpsignal=PLUGIN_EXITSIGNAL_NONE;return $fpreturn;}
			}
			if($this->_avatar)return $this->_avatar;
			$s=$core->usersdir . 'avatar/' . $this->ID . '.png';
			if(is_readable($s)){
				$this->_avatar = $core->host . 'zb_users/avatar/' . $this->ID . '.png';
				return $this->_avatar;
			}
			$this->_avatar = $core->host . 'zb_users/avatar/0.png';
			return $this->_avatar;
		}
		if ($name=='LevelName') {
			return $core->lang['user_level_name'][$this->Level];
		}
		if ($name=='EmailMD5') {
			return md5($this->Email);
		}
		if ($name=='StaticName') {
			if($this->Alias)return $this->Alias;
			return $this->Name;
		}
		if ($name=='Template') {
			$value=$this->data[$name];
			if($value=='')$value=$core->option['ZC_INDEX_DEFAULT_TEMPLATE'];
			return $value;
		}
		return parent::__get($name);
	}

	/**
	 * 获取加盐及二次加密的密码
	 * @param string $ps 明文密码
	 * @param string $guid 用户唯一码
	 * @return string
	*/
	static function GetPassWordByGuid($ps,$guid){

		return md5(md5($ps). $guid);

	}

	/**
	 * 保存用户数据
	 * @return bool
	 */
	function save(){
		global $core;
		return parent::save();
	}

}
