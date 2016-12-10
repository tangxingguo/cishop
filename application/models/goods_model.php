<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Goods_model extends CI_Model
{
	const TBL_GOODS = 'goods';
	
	//添加商品，注意返回值，是新插入记录的id
	public function add_goods($data)
	{
		$query = $this->db->insert(self::TBL_GOODS, $data);
		return $query ? $this->db->insert_id : false;
	}
}