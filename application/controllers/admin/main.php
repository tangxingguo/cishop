<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller{
	#展示后台首页面
	public function index(){
		$this->load->view('index.html');
	}
	#展示头部
	public function top(){
		$this->load->view('top.html');
	}
	public function menu(){
		$this->load->view('menu.html');
	}
	public function drag(){
		$this->load->view('drag.html');
	}
	public function content(){
		$this->load->view('main.html');
	}
}