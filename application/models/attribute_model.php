<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//商品属性模型
class Attribute_model extends CI_Model
{
	const TBL_ATTR = 'attribute';
	//添加商品属性
	public function add_attrs($data)
	{
		return $this->db->insert(self::TBL_ATTR, $data);
	}
	
	//显示商品属性
	public function list_attrs()
	{
		$query = $this->db->get(self::TBL_ATTR);
		return $query->result_array();
	}
	
	//获取指定类型下面所有的属性
	public function get_arrs($type_id)
	{
		$condition['type_id'] = $type_id;
		$query = $this->db->where($condition)->get(self::TBL_ATTR);
		return $query->result_array();
	}
}
