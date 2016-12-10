<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//商品属性控制器
class Attribute extends Admin_Controller
{
	//构造器
	public function __construct()
	{
		parent::__construct();
		//加载商品类型模型
		$this->load->model('goodstype_model');
		//加载商品属性模型
		$this->load->model('attribute_model');
	}
	
	
	//显示商品属性
	public function index()
	{
		$data['attrs'] = $this->attribute_model->list_attrs();
		//加载视图
		$this->load->view('attribute_list.html', $data);
	}
	
	//添加商品属性
	public function add()
	{
		//获取商品类型信息
		$data['goodstypes'] = $this->goodstype_model->get_all_types();
		//加载视图
		$this->load->view('attribute_add.html', $data);
	}
	
	//编辑商品属性
	public function edit()
	{
		//加载视图
		$this->load->view('attribute_edit.html');
	}
	
	
	//添加属性
	public function insert()
	{
		//获取表单提交的数据
		$data['attr_name'] = $this->input->post('attr_name');
		$data['type_id'] = $this->input->post('type_id');
		$data['attr_type'] = $this->input->post('attr_type');
		$data['attr_input_type'] = $this->input->post('type_id');
		$data['attr_value'] = $this->input->post('type_id');
		$data['sort_order'] = $this->input->post('sort_order');
		
		//插入数据库
		if($this->attribute_model->add_attrs($data))
		{
			$data['message'] = '添加商品属性成功';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/attribute/index');
			$this->load->view('message.html', $data);
		} else 
		{
			$data['message'] = '添加商品属性失败';
			$data['wait'] = 3;
			$data['url'] = site_url('admin/attribute/add');
			$this->load->view('message.html', $data);
		}
	}
	
	
	
	
	
	
	
	
	
	
}