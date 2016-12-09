<?php
class Hello extends CI_Controller
{
	public function index()
	{
		$data['title'] = 'CI框架';
		$this->load->view('hello.html', $data);
		
	}
}