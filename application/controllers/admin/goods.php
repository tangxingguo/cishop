<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//商品控制器

class Goods extends Admin_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('goodstype_model');
		$this->load->model('attribute_model');
		$this->load->model('category_model');
		$this->load->model('brand_model');
		$this->load->model('goods_model');
		$this->load->library('pagination');
		// $this->output->enable_profiler(true);
	}

	public function index($offset = ''){
		#配置分页信息
		$config['base_url'] = site_url('admin/goods/index/');
		$config['total_rows'] = $this->goods_model->count_goods();
		$config['per_page'] = 2;
		$config['uri_segment'] = 4;

		#自定义分页链接
		$config['first_link'] = '首页';
		$config['last_link'] = '尾页';
		$config['prev_link'] = '上一页';
		$config['next_link'] = '下一页';

		#初始化分页类
		$this->pagination->initialize($config);
		
		#生成分页信息
		$data['pageinfo'] = $this->pagination->create_links();

		$limit = $config['per_page'];
		$data['goods'] = $this->goods_model->list_goods($limit,$offset);
		$data['cates'] = $this->category_model->list_cate();
		$data['brands'] = $this->brand_model->list_brand();
		$this->load->view('goods_list.html',$data);
	}


	public function add(){
		#获取所有的商品类型信息
		$data['goodstypes'] = $this->goodstype_model->get_all_types();
		#获取分类信息
		$data['cates'] = $this->category_model->list_cate();
		#获取品牌信息 
		$data['brands'] = $this->brand_model->list_brand();

		$this->load->view('goods_add.html',$data);
	}


	public function edit(){
		#获取所有的商品类型信息
		$data['goodstypes'] = $this->goodstype_model->get_all_types();
		#获取分类信息
		$data['cates'] = $this->category_model->list_cate();
		#获取品牌信息 
		$data['brands'] = $this->brand_model->list_brand();
		#获取商品信息
		$goods_id = $this->uri->segment(4,0);
		$data['goods'] = $this->goods_model->get_goods($goods_id);
		#获取商品属性,并构造对应的html值
		$type_id = $data['goods']['type_id'];
		$attrs = $this->goods_model->get_attrs($goods_id,$type_id);

		$html = '';
		foreach ($attrs as $v) {
			$html .= "<tr>";
			$html .= "<td class='label'>".$v['attr_name']."</td>";
			$html .="<td>";
			$html .= "<input type='hidden' name='attr_id_list[]' value='".$v['attr_id']."'>";
			switch ($v['attr_input_type']) {
				case 0:
					# 文本框
					$html .= "<input name='attr_value_list[]'' type='text' size='40' value='".$v['default_value']."'>";
					break;
				case 1:
					# 下拉列表
					$arr = explode(PHP_EOL, $v['attr_value']);
					$html .= "<select name='attr_value_list[]'>";
					$html .= "<option value=''>请选择...</option>";
					foreach ($arr as $val) {
						$html .= "<option value='$val'";
						if ($val == $v['default_value']) {
							$html .= "selected";
						}
						$html .= ">$val</option>";
					}								  
					$html .= "</select>";
					break;
				case 2:
					# 文本域
					break;
				
				default:
					# code...
					break;
			}

			$html .="</td>";
			$html .="</tr>";
		}
		$data['attr_list'] = $html;

		$this->load->view('goods_edit.html',$data);
	}

	#添加商品
	public function insert(){
		#获取提交的数据
		$data['goods_name'] = $this->input->post('goods_name');
		$data['goods_sn'] = $this->input->post('goods_sn');
		$data['cat_id'] = $this->input->post('cat_id');
		$data['brand_id'] = $this->input->post('brand_id');
		$data['market_price'] = $this->input->post('market_price');
		$data['shop_price'] = $this->input->post('shop_price');
		$data['promote_price'] = $this->input->post('promote_price');
		$data['promote_start_time'] = strtotime($this->input->post('goods_name'));
		$data['promote_end_time'] = strtotime($this->input->post('goods_name'));
		$data['goods_number'] = $this->input->post('goods_number');
		$data['type_id'] = $this->input->post('type_id');
		$data['goods_brief'] = $this->input->post('goods_brief');
		$data['is_best'] = $this->input->post('is_best');
		$data['is_new'] = $this->input->post('is_new');
		$data['is_hot'] = $this->input->post('is_hot');
		$data['is_onsale'] = $this->input->post('is_onsale');
		$data['goods_desc'] = $this->input->post('goods_desc');

		#完成上传图片
		$config['upload_path'] = './public/uploads/';
		$config['allowed_types'] = 'jpg|gif|png';
		$config['max_size'] = 200;
		$this->load->library('upload',$config);

		if ($this->upload->do_upload('goods_img')) {
			# 上传成功，缩略处理
			$res = $this->upload->data(); //获取上传图片信息
			$data['goods_img'] = $res['file_name'];
			$config_img['source_image'] = "./public/uploads/" . $res['file_name'];
			$config_img['create_thumb'] = true;
			$config_img['maintain_ratio'] = true;
			$config_img['width'] = 160;
			$config_img['height'] = 160;

			#载入并初始化图像处理类
			$this->load->library('image_lib',$config_img);

			if ($this->image_lib->resize()) {
				# 缩略ok,得到缩略图的名称
				$data['goods_thumb'] = $res['raw_name'] . $this->image_lib->thumb_marker. $res['file_ext'];

				if ($goods_id = $this->goods_model->add_goods($data)) {
					# 添加商品成功,获取属性并插入到商品属性关联表中
					$attr_ids = $this->input->post('attr_id_list');
					$attr_values = $this->input->post('attr_value_list');
					foreach ($attr_values as $k => $v) {
						if (!empty($v)) {
							$data2['goods_id'] = $goods_id;
							$data2['attr_id'] = $attr_ids[$k];
							$data2['attr_value'] = $v;

							#完成插入
							$this->db->insert('goods_attr',$data2);
						}	
					}

					$data['message'] = '添加商品成功';
					$data['url'] = site_url('admin/goods/index');
					$data['wait'] = 3;
					$this->load->view('message.html',$data);


				} else {
					# 失败
					$data['message'] = '添加商品失败';
					$data['url'] = site_url('admin/goods/add');
					$data['wait'] = 3;
					$this->load->view('message.html',$data);
				}
				

			} else {
				# 缩略失败
				$data['message'] = $this->image_lib->display_errors();
				$data['url'] = site_url('admin/goods/add');
				$data['wait'] = 3;
				$this->load->view('message.html',$data);
			}
			

		} else {
			# 上传失败
			$data['message'] = $this->upload->display_errors();
			$data['url'] = site_url('admin/goods/add');
			$data['wait'] = 3;
			$this->load->view('message.html',$data);
		}
		
	}


	public function create_attrs_html(){
		#获取类型id
		$type_id = $this->input->get('type_id');
		// echo $type_id;
		$attrs = $this->attribute_model->get_attrs($type_id);
		#根据获取到的属性值构造html字符串

		$html = '';
		foreach ($attrs as $v) {
			$html .= "<tr>";
			$html .= "<td class='label'>".$v['attr_name']."</td>";
			$html .="<td>";
			$html .= "<input type='hidden' name='attr_id_list[]' value='".$v['attr_id']."'>";
			switch ($v['attr_input_type']) {
				case 0:
					# 文本框
					$html .= "<input name='attr_value_list[]'' type='text' size='40'>";
					break;
				case 1:
					# 下拉列表
					$arr = explode(PHP_EOL, $v['attr_value']);
					$html .= "<select name='attr_value_list[]'>";
					$html .= "<option value=''>请选择...</option>";
					foreach ($arr as $v) {
						$html .= "<option value='$v'>$v</option>";
					}								  
					$html .= "</select>";
					break;
				case 2:
					# 文本域
					break;
				
				default:
					# code...
					break;
			}

			$html .="</td>";
			$html .="</tr>";
		}

		echo $html;

	}
}