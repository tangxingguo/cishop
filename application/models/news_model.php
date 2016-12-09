<?php
class News_model extends CI_Model
{
	//构造函数
	public function __construct()
	{
		//调用父类的构造器
		parent::__construct();
		//手动加载数据库
		$this->load->database();
	}
	
	
}