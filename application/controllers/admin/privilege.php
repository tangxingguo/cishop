<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//权限控制器
class Privilege extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		//手动加载CAPTCHA辅助函数
		$this->load->helper('captcha');
		//手动加载表单验证类
		$this->load->library('form_validation');
	}
	
	public function login()
	{
// 		$vals = array(
// 			'word' => rand(1000, 9999),  //验证码数据的来源
// 			'img_path' =>'./data/captcha/', //设置验证码图片保存路径
// 			'img_url'  =>base_url(). 'data/captcha/', //图片的url
			
// 		);
		
// 		$data = create_captcha($vals);
// 		var_dump($data);
		$this->load->view('login.html');
	}
	
	//生成验证码
	public function code()
	{
		$vals = array(
			'word_length' => 4,
		);
		//调用函数生成验证码
		$code = create_captcha($vals);
		//将验证码字符串保存到session中
		$this->session->set_userdata('code', $code);
	}
	
	//处理登录
	public function signin()
	{
		//设置验证规则
		$this->form_validation->set_rules('username', '用户名', 'required');
		$this->form_validation->set_rules('password', '密码', 'required');
		//获取表单的数据
		$captcha = strtolower($this->input->post('captcha'));
		
		//获取session中保存的验证码
		$code = strtolower($this->session->userdata('code'));
		//验证验证码是否正确
		if($captcha === $code)
		{
			//验证码正确，则需要验证用户名和密码
			if($this->form_validation->run() == false)
			{
				$data['message'] = validation_errors();
				$data['url'] = site_url('admin/Privilege/login');
				$data['wait'] = 3;
				//加载提示视图
				$this->load->view('message.html', $data);
			} else 
			{
				$username = $this->input->post('username', true);
				$password = $this->input->post('password', true);
					
				if($username == 'admin' && $password == '123')
				{
					//正确，将登录信息保存到session中，然后跳转到首页
					$this->session->set_userdata('admin', $username);
					//跳转到首页
					redirect('admin/main/index');
				}else
				{
					//验证码不正确，给出提示页面，然后返回
					$data['url'] = site_url('admin/Privilege/login');
					$data['message'] = '用户名和密码错误，请重新填写';
					$data['wait'] = 3;
					//加载提示页面
					$this->load->view('message.html', $data);
				}

			}
		}else 
		{
			//验证码不正确，给出提示页面，然后返回
			$data['url'] = site_url('admin/Privilege/login');
			$data['message'] = '验证码错误，请重新填写';
			$data['wait'] = 3;
			//加载提示页面
			$this->load->view('message.html', $data);			
		}
		
	}
	
	public function logout()
	{
		//注销登录
		$this->session->unset_userdata();
		//销毁Session
		$this->session->sess_destroy();
		//注销完成后跳转到登录页面
		redirect('admin/privilege/login');
	}
	
	
	
}