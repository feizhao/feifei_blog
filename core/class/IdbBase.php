<?php
/**
 * 数据库操作接口
 * @author zhaofei
 */
interface IdbBase {

	/**
	* @param $array
	* @return mixed
	*/
	public function open($array);
	/**
	* @return mixed
	*/
	public function close();
	/**
	* @param $query
	* @return mixed
	*/
	public function query($query);
	/**
	* @param $query
	* @return mixed
	*/
	public function insert($query);
	/**
	* @param $query
	* @return mixed
	*/
	public function update($query);

	/**
	* @param $query
	* @return mixed
	*/
	public function delete($query);
	/**
	* @param $s
	* @return mixed
	*/
	public function queryMulit($s);
	/**
	* @param $s
	* @return mixed
	*/
	public function escapeString($s);

	/**
	* @param $table
	* @param $datainfo
	* @return mixed
	*/
	public function createTable($table,$datainfo);
	/**
	* @param $table
	* @return mixed
	*/
	public function delTable($table);
	/**
	* @param $table
	* @return mixed
	*/
	public function existTable($table);
}

?>