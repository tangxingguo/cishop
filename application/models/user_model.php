<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//用户模型
class User_model extends CI_Model{

	const TBL_USER = 'user';

	public function add_user($data){
		return $this->db->insert(self::TBL_USER,$data);
	}

	public function get_user($username,$password){
		$condition['user_name'] = $username;
		$condition['password'] = md5($password);
		$query = $this->db->where($condition)->get(self::TBL_USER);
		return $query->row_array();
	}
}