<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//品牌模型
class Brand_model extends CI_Model{

	const TBL_BRAND = 'brand'; //此处不需要加前缀

	#添加商品品牌
	public function add_brand($data){
		return $this->db->insert(self::TBL_BRAND,$data);
	}

	#查询商品品牌
	public function list_brand(){
		$query = $this->db->get(self::TBL_BRAND);
		return $query->result_array();
	}

	#分页查询
	public function page_brand($limit,$offset){
		$query = $this->db->limit($limit,$offset)->get(self::TBL_BRAND);
		return $query->result_array();
	}
}