<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品品牌控制器

class Brand extends Admin_Controller
{
	//构造方法
	public function __construct()
	{
		//调用父类的构造方法
		parent::__construct();
		//加载表单验证类
		$this->load->library('form_validation');
		//加载模型
		$this->load->model('brand_model');
		//加载文件上传类
		$this->load->library('upload');
	}
	
	//显示品牌信息
	public function index()
	{
		$data['brands'] = $this->brand_model->list_brand();
		$this->load->view('brand_list.html', $data);
	}
	
	//显示添加品牌页面
	public function add()
	{
		$this->load->view('brand_add.html');
	}
	
	//显示编辑品牌页面
	public function edit()
	{
		$this->load->view('brand_edit.html');
	}
	
	//添加商品品牌
	public function insert()
	{
		//设置验证规则
		$this->form_validation->set_rules('brand_name', '品牌名称', 'required');
		if($this->form_validation->run() == false)
		{
			//未通过验证
			$data['message'] = validation_errors();
			$data['wait'] = 3;
			$data['url'] = site_url('admin/brand/add');
			$this->load->view('message.html',$data);
		} else 
		{
			//通过验证，处理图片上传
			//配置图片上传相关参数
			if($this->upload->do_upload('logo'))
			{
				//上传成功，获取文件名
				$fileinfo = $this->upload->data();
				$data['logo'] = $fileinfo['file_name'];
				//获取表单提交的数据
				$data['brand_name'] = $this->input->post('brand_name', true);
				$data['url'] = $this->input->post('url');
				$data['brand_desc'] = $this->input->post('brand_desc');
				$data['sort_order'] = $this->input->post('sort_order');
				$data['is_show'] = $this->input->post('is_show');
				
				//调用模型完成添加
				if($this->brand_model->add_brand($data))
				{
					$data['message'] = '添加品牌成功';
					$data['wait'] = 3;
					$data['url'] = site_url('admin/brand/index');
					$this->load->view('message.html',$data);
				} else 
				{
					$data['message'] = '添加品牌失败';
					$data['wait'] = 3;
					$data['url'] = site_url('admin/brand/add');
					$this->load->view('message.html',$data);
				}
				
			} else 
			{
				//文件上传失败
				$data['message'] = $this->upload->display_errors();
				$data['wait'] = 3;
				$data['url'] = site_url('admin/brand/add');
				$this->load->view('message.html',$data);
			}
		}
	}
	
}
