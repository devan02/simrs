<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bpjs_c extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$sess_user = $this->session->userdata('masuk_rs');
    	$id_user = $sess_user['id'];
	    if($id_user == "" || $id_user == null){
	        redirect('portal');
	    }
	}

	function index()
	{
		$data = array(
			'page' => 'bpjs/bpjs_beranda_v',
			'title' => 'BPJS',
			'subtitle' => 'BPJS',
			'master_menu' => 'home',
			'view' => '',
		);

		$this->load->view('bpjs/bpjs_home_v',$data);
	}

} 