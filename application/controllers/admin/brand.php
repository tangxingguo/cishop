<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品品牌控制器

class Brand extends Admin_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('brand_model');
		$this->load->library('upload');
		$this->load->library('pagination');
	}

	#显示商品品牌信息
	public function index($offset = ''){
		#分页显示
		$config['base_url'] = site_url('admin/brand/index');
		$config['total_rows'] = $this->db->count_all_results('brand');
		$config['per_page'] = 2;
		$config['uri_segment'] = 4;
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';
		// $config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$limit = $config['per_page'];
		$data['pageinfo'] = $this->pagination->create_links();
		$data['brands'] = $this->brand_model->page_brand($limit,$offset);
		
		$this->load->view('brand_list.html',$data);
	}


	#显示添加品牌页面
	public function add(){
		$this->load->view('brand_add.html');
	}

	#显示编辑品牌页面
	public function edit(){
		$this->load->view('brand_edit.html');
	}

	#添加品牌
	public function insert(){
		#设置验证规则
		$this->form_validation->set_rules('brand_name','品牌名称','required');
		if ($this->form_validation->run() == false) {
			# 未通过验证
			$data['message'] = validation_errors();
			$data['wait'] = 3;
			$data['url'] = site_url('admin/brand/add');
			$this->load->view('message.html',$data);
		} else {
			# 通过验证，处理图片上传
			#配置上传相关参数
			if ($this->upload->do_upload('logo')) {
				# 上传成功，获取文件名
				$fileinfo = $this->upload->data();
				$data['logo'] = $fileinfo['file_name'];
				#获取表单提交数据
				$data['brand_name'] = $this->input->post('brand_name');
				$data['url'] = $this->input->post('url');
				$data['brand_desc'] = $this->input->post('brand_desc');
				$data['sort_order'] = $this->input->post('sort_order');
				$data['is_show'] = $this->input->post('is_show');

				#调用品牌模型完成插入动作
				if ($this->brand_model->add_brand($data)) {
					$data['message'] = '添加品牌成功';
					$data['wait'] = 3;
					$data['url'] = site_url('admin/brand/index');
					$this->load->view('message.html',$data);
				} else {
					$data['message'] = '添加品牌失败';
					$data['wait'] = 3;
					$data['url'] = site_url('admin/brand/add');
					$this->load->view('message.html',$data);
				}
			} else {
				# 上传失败
				$data['message'] = $this->upload->display_errors();
				$data['wait'] = 3;
				$data['url'] = site_url('admin/brand/add');
				$this->load->view('message.html',$data);
			}
			

			
			

		}
		
	}
}