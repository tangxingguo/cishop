<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品属性控制器

class Attribute extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
		$this->load->library('pagination');
	}

	public function index($type='',$offset = ''){
		$config['base_url'] = site_url('admin/attribute/index').'/' .$type.'/';
		$config['total_rows'] = $this->attribute_model->count_attrs($type);
		$config['per_page'] = 2;
		$config['uri_segment'] = 5;
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';

		$this->pagination->initialize($config);

		$limit = $config['per_page'];

		$data['types'] = $this->goodstype_model->list_goodstype();
		$data['attrs'] = $this->attribute_model->list_attribute($limit,$offset,$type);
		$data['pageinfo'] = $this->pagination->create_links();
		$data['type_id'] = $type;

		$this->load->view('attribute_list.html',$data);
	}

	public function add(){
		#获取商品类型信息
		$data['goodstypes'] = $this->goodstype_model->get_all_types();
		$this->load->view('attribute_add.html',$data);
	}

	public function edit(){
		$this->load->view('attribute_edit.html');
	}

	#添加属性
	public function insert(){
		$data['attr_name'] = $this->input->post('attr_name');
		$data['type_id'] = $this->input->post('type_id');
		$data['attr_type'] = $this->input->post('attr_type');
		$data['attr_input_type'] = $this->input->post('attr_input_type');
		$data['attr_value'] = $this->input->post('attr_value');
		$data['sort_order'] = $this->input->post('sort_order');
		
		if ($this->attribute_model->add_attrs($data)) {
			# ok
			$data['message'] = '添加属性成功';
			$data['url'] = site_url('admin/attribute/index');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		} else {
			# error
			$data['message'] = '添加属性失败';
			$data['url'] = site_url('admin/attribute/add');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		}
		
	}
}