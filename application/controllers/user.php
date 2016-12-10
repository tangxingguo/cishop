<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
	
	// 用户控制器
class User extends Home_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library ( 'form_validation' );
		$this->load->model ( 'user_model' );
	}
	// 示注册页面
	public function register() {
		$this->load->view ( 'register.html' );
	}
	
	// 示登录页面
	public function login() {
		$this->load->view ( 'login.html' );
	}
	public function do_register() {
		// 置验证规则
		$this->form_validation->set_rules ( 'username', '用户名', 'required' );
		$this->form_validation->set_rules ( 'password', '密码', 'required|min_length[6]|max_length[16]|md5' );
		$this->form_validation->set_rules ( 'repassword', '重复密码', 'required|matches[password]' );
		$this->form_validation->set_rules ( 'email', '电子邮箱', 'required|valid_email' );
		
		if ($this->form_validation->run () == false) {
			// 未通过
			echo validation_errors ();
		} else {
			// 通过,注册
			$data ['user_name'] = $this->input->post ( 'username', true );
			$data ['password'] = $this->input->post ( 'password', true );
			$data ['email'] = $this->input->post ( 'email', true );
			$data ['reg_time'] = time ();
			if ($this->user_model->add_user ( $data )) {
				// 注册成功
				echo 'ok';
			} else {
				// 注册失败
				echo 'error';
			}
		}
	}
	
	// 录动作
	public function signin() {
		// 证 省略
		$username = $this->input->post ( 'username' );
		$password = $this->input->post ( 'password' );
		
		if ($user = $this->user_model->get_user ( $username, $password )) {
			// 功，将用户信息保存至session
			$this->session->set_userdata ( 'user', $user );
			redirect ( 'home/index' );
		} else {
			// error
			echo 'error';
		}
	}
	
	// 销动作
	public function logout() {
		$this->session->unset_userdata ( 'user' );
		redirect ( 'home/index' );
	}
}