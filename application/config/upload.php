<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 由于上传可能会多次用到
 * 改进方案：在config文件下，配置统一的参数
 * 使用了配置文件以后，它就会自动加载
 */
//配置上传的相关参数
$config['upload_path'] = './public/uploads/';
$config['allowed_types'] = 'gif|jpg|png';
$config['max_size'] = 500;