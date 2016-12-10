<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品模型
class Goods_model extends CI_Model{
	const TBL_GOODS = 'goods';

	#分页获取商品
	public function list_goods($limit,$offset){
		$query = $this->db->limit($limit,$offset)->get(self::TBL_GOODS);
		return $query->result_array();
	}

	#添加商品，注意返回值,是新插入记录的id
	public function add_goods($data){
		$query = $this->db->insert(self::TBL_GOODS,$data);
		return $query ? $this->db->insert_id() : false;
	}

	#获取商品总数
	public function count_goods(){
		return $this->db->count_all(self::TBL_GOODS);
	}

	#获取商品信息
	public function get_goods($goods_id){
		$condition['goods_id'] = $goods_id;
		$query = $this->db->where($condition)->get(self::TBL_GOODS);
		return $query->row_array();
	}

	#获取商品属性信息
	public function get_attrs($goods_id,$type_id){
		$sql = "select a.*,b.attr_value as default_value from ci_attribute as a left join ci_goods_attr as b on a.attr_id = b.attr_id
		and b.goods_id = $goods_id where a.type_id = $type_id";

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	#获取推荐商品信息
	public function best_goods(){
		$condition['is_best'] = 1;
		$query = $this->db->where($condition)->get(self::TBL_GOODS);
		return $query->result_array();
	}

}