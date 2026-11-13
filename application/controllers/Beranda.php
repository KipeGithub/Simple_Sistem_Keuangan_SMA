<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends CI_Controller {

	function __construct(){
		parent::__construct();
		is_login();
		if ($this->session->userdata('role') == 4) {
			redirect('School_finance/portal');
			return;
		}
	}

	public function index(){
		redirect('School_finance/dasbor');
	}
}

/* End of file Beranda.php */
/* Location: ./application/controllers/Beranda.php */