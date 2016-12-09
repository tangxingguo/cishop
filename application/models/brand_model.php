<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//商品品牌模型
class Brand_model extends CI_Model
{
	//定义表名常量
	const TBL_BRAND = "brand";  //不需要加前缀   已经在config/database.php中配置了前缀
	
	//添加商品品牌
	public function add_brand($data)
	{
		return $this->db->insert(self::TBL_BRAND, $data);
	}
	
	//查询商品品牌信息
	public function list_brand()
	{
		$query = $this->db->get(self::TBL_BRAND);
		return $query->result_array();
	}
 	  
	
}