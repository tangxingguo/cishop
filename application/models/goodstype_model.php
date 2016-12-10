<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品类型模型
class Goodstype_model extends CI_Model{

	const TBL_GT = 'goods_type';

	public function add_goodstype($data){
		return $this->db->insert(self::TBL_GT,$data);
	}

	#获取所有商品类型
	public function get_all_types(){
		$query = $this->db->get(self::TBL_GT);
		return $query->result_array();
	}


	#获取分页数据
	public function page_goodstype($limit,$offset){
		$query = $this->db->limit($limit,$offset)->get(self::TBL_GT);
		return $query->result_array();
	}

	#统计商品类型的总数
	public function count_goodstype(){
		return $this->db->count_all(self::TBL_GT);
	}

	#查询品牌
	public function list_goodstype(){
		$query = $this->db->get(self::TBL_GT);
		return $query->result_array();
	}
}