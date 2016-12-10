<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 商品类别控制器
 */

class Category extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('category_model');
		$this->load->library('form_validation');
		#激活分析器以调试程序
		// $this->output->enable_profiler(TRUE);

	}

	/* 插入几条测试数据
	insert into ci_category(cat_name,parent_id)values('广东',0);
	insert into ci_category(cat_name,parent_id)values('湖北',0);
	insert into ci_category(cat_name,parent_id)values('中山',1);
	insert into ci_category(cat_name,parent_id)values('武汉',2);
	insert into ci_category(cat_name,parent_id)values('顺德',3);
	insert into ci_category(cat_name,parent_id)values('武昌',4);

	*/
	// 显示分类信息
	public function index(){
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('cat_list.html',$data);
	}
	// 显示添加表单
	public function add(){
		$data['cates'] = $this->category_model->list_cate();
		$this->load->view('cat_add.html',$data);
	}
	
	//显示编辑表单
	public function edit($cat_id){
		#获取所有的分类信息
		$data['cates'] = $this->category_model->list_cate();
		#获取当前这条记录的信息
		//获取cat_id
		$data['current_cat'] = $this->category_model->get_cate($cat_id);
		$this->load->view('cat_edit.html',$data);
	}
	

	//完成添加分类动作
	public function insert(){
		#设置验证规则
		$this->form_validation->set_rules('cat_name','分类名称','trim|required');

		if ($this->form_validation->run() == false) {
			# 未通过验证
			$data['message'] = validation_errors(); 
			$data['wait'] = 3;
			$data['url'] = site_url('admin/category/add');
			$this->load->view('message.html',$data);
		} else {
			# 通过验证
			$data['cat_name'] = $this->input->post('cat_name',true);
			$data['parent_id'] = $this->input->post('parent_id');
			$data['unit'] = $this->input->post('unit',true);
			$data['sort_order'] = $this->input->post('sort_order',true);
			$data['cat_desc'] = $this->input->post('cat_desc',true);
			$data['is_show'] = $this->input->post('is_show');
			// var_dump($data);


			#调用model方法完成插入
			if ($this->category_model->add_category($data)){
				#插入ok
				$data['message'] = '添加商品类别成功'; 
				$data['wait'] = 2;
				$data['url'] = site_url('admin/category/index');
				$this->load->view('message.html',$data);
			} else{
				#插入失败
				$data['message'] = '添加商品类别失败'; 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/add');
				$this->load->view('message.html',$data);
			}
		}
		

	}

	#更新操作
	public function update(){
		$cat_id = $this->input->post('cat_id');
		#获取该cat_id 分类下的所有后代分类
		$sub_cates = $this->category_model->list_cate($cat_id);
		#var_dump($sub_cates);
		#获取这些后代分类的cat_id
		$sub_ids = array();
		foreach ($sub_cates as $v) {
			$sub_ids[] = $v['cat_id'];
		}
		$parent_id = $this->input->post('parent_id');
		// var_dump($sub_ids);
		#判断所选的父分类是否为当前分类或其后代分类
		if ($parent_id == $cat_id || in_array($parent_id, $sub_ids)) {
				$data['message'] = '不能将分类放置到当前分类或其子分类'; 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/edit') . '/'.$cat_id;
				$this->load->view('message.html',$data);
		} else {
			# 进行更新
			$this->form_validation->set_rules('cat_name','分类名称','trim|required');
			if ($this->form_validation->run() == false) {
				# 未通过验证
				$data['message'] = validation_errors(); 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/add');
				$this->load->view('message.html',$data);
			} else {
				# 通过验证
				$data['cat_name'] = $this->input->post('cat_name',true);
				$data['parent_id'] = $this->input->post('parent_id');
				$data['unit'] = $this->input->post('unit',true);
				$data['sort_order'] = $this->input->post('sort_order',true);
				$data['cat_desc'] = $this->input->post('cat_desc',true);
				$data['is_show'] = $this->input->post('is_show');

				if ($this->category_model->update_cate($data,$cat_id)) {
					$data['message'] = '更新成功'; 
					$data['wait'] = 3;
					$data['url'] = site_url('admin/category/index');
					$this->load->view('message.html',$data);
				} else {
					$data['message'] = '更新失败'; 
					$data['wait'] = 3;
					$data['url'] = site_url('admin/category/edit') . '/'.$cat_id;
					$this->load->view('message.html',$data);
				}
				
			}
		}
		
	}

	#删除操作
	public function delete(){
		$cat_id = $this->uri->segment(4);
		#如果不是底层分类，则不允许删除
		$sub_cates = $this->category_model->list_cate($cat_id);
		if (empty($sub_cates)) {
			# code...
			if ($this->category_model->delete_cate($cat_id)) {
				# code...
				$data['message'] = '删除成功'; 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/index');
				$this->load->view('message.html',$data);
			} else {
				# code...
				$data['message'] = '删除失败'; 
				$data['wait'] = 3;
				$data['url'] = site_url('admin/category/index');
				$this->load->view('message.html',$data);
			}
		} else {
			# code...
			$data['message'] = '该分类下面还包含其他分类，先删除其子分类'; 
			$data['wait'] = 3;
			$data['url'] = site_url('admin/category/index');
			$this->load->view('message.html',$data);
		}
		
		
		
	}
}