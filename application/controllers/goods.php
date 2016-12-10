<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//商品控制器
class Goods extends Home_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('goods_model');
	}

	public function index($goods_id){
		$data['goods'] = $this->goods_model->get_goods($goods_id);
		$this->load->view('goods.html',$data);
	}
}