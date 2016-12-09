<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品的类别模型
class Category_model extends CI_Model
{
	const TBL_CATE = "category";
	/**
	 * array 返回该节点的所有后代节点
	 * @param unknown $pid  节点的id
	 */
	public function list_cate($pid = 0)
	{
		 //获取所有记录
		 $query = $this->db->get(self::TBL_CATE);
		 $cates = $query->result_array();
		 //对类别进行重组，并返回
		 return $this->_tree($cates, $pid);
		 
	}
	
	/**
	 * 
	 * @param unknown $arr 要遍历的数组
	 * @param number $pid 节点pid, 默认为0, 表示从顶级节点开始
	 * @param int    $level表示层级 默认为0 
	 * @param array 排好序后的所有后代节点
	 */
	private function _tree($arr, $pid = 0, $level = 0)
	{
		static $tree = array();   //用于保存重组的结果， 注意使用静态变量
		foreach ($arr as $v) 
		{
			if($v['parent_id'] == $pid)
			{
				//说明找到了以$pid为父节点的子节点，将其保存
				$v['level'] = $level;
				$tree[] = $v;
				//然后以当前节点为父节点，继续找其后代节点
				$this->_tree($arr, $v['cat_id'], $level + 1);  //这里使用的是递归
			}
		}
		
		return $tree;
		
	}
	
	
	//添加分类信息
	public function add_category($data)
	{
		 return $this->db->insert(self::TBL_CATE, $data);  
	}
	
	//获取单条信息
	public function get_cate($cate_id)
	{
		$condition['cat_id'] = $cate_id;
		//根据id查询该条信息
		$query = $this->db->where($condition)->get(self::TBL_CATE);
		//返回单条信息
		return $query->row_array();
	}
	
	//更新
	public  function update_cate($data, $cat_id)
	{
		//获取更新字段的id
		$condition['cat_id'] = $cat_id;
		return $this->db->where($condition)->update(self::TBL_CATE, $data);
	}
	
	//删除
	public function delete_cate($cat_id)
	{
		$condition['cat_id'] = $cat_id;
		$query = $this->db->where($condition)->delete(self::TBL_CATE);
		if($query && $this->db->affected_rows() > 0)
		{
			return true;
		} else 
		{
			return false;
		}
	}
	
}
