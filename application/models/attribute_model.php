<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品属性模型

class Attribute_model extends CI_Model{

	const TBL_ATTR = 'attribute';
	const TBL_TYPE = 'goods_type';


	public function add_attrs($data){
		return $this->db->insert(self::TBL_ATTR,$data);
	}


	#获取属性
	public function list_attribute($limit,$offset,$type=''){
		$this->db->select('*');
		$this->db->from(self::TBL_ATTR);
		$this->db->join(self::TBL_TYPE,'attribute.type_id = goods_type.type_id','left');
		if ($type > 0){
			$this->db->where('attribute.type_id',$type);
		}
		$query = $this->db->limit($limit,$offset)->get();
		return $query->result_array();
	}

	#获取总的记录数
	public function count_attrs($type){
		if ($type > 0){
			$this->db->where('attribute.type_id',$type);
		}
		return $this->db->count_all_results(self::TBL_ATTR);
	}

	#获取指定类型下面所有的属性
	public function get_attrs($type_id){
		$condition['type_id'] = $type_id;
		$query = $this->db->where($condition)->get(self::TBL_ATTR);
		return $query->result_array();
	}
}