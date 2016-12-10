<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//前台首页控制器
class Home extends Home_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('category_model');
		$this->load->model('goods_model');
	}

	public function index(){

		$data['cates'] = $this->category_model->front_cate();


		$data['best_goods'] = $this->goods_model->best_goods();

		#载入多个视图
		$this->load->view('header.html',$data);
		$this->load->view('menu1.html');
		$this->load->view('index.html');
		$this->load->view('footer.html');

	}
}