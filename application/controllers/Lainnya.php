<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lainnya extends CI_Controller {

	private $parents = 'Lainnya';
	private $icon	 = 'fa fa-money';
	var $table 		 = 'lainnya';

	function __construct(){
		parent::__construct();

		is_login();
		if ((int) $this->session->userdata('role') === 4) {
			redirect('School_finance/portal');
			return;
		}
		get_breadcrumb();
		$this->load->model('M_'.$this->parents,'mod');
		$this->load->library('form_validation');
		$this->load->library('Datatables'); 
	}

	public function index(){
		$this->breadcrumb->append_crumb('Kas Masuk', 'School_finance/tagihan');
		$this->breadcrumb->append_crumb('Kas Masuk Lainnya', $this->parents);

		$data['title']	= 'Kas Masuk Lainnya | SMK DUMMY KIPE';
		$data['judul']	= 'Kas Masuk Lainnya';
		$data['icon']	= $this->icon;

		$this->template->views('Backend/'.$this->parents.'/v_'.$this->parents, $data);
	}

	function getData(){
		header('Content-Type:application/json');
		echo $this->mod->getAllData();
	}

	function getDetail(){
		header('Content-Type:application/json');
		$id = $this->input->post('tgl');
		echo $this->mod->getDetailData($id);
	}

	function Detail(){
		$this->breadcrumb->append_crumb('Kas Masuk', 'School_finance/tagihan');
		$this->breadcrumb->append_crumb($this->parents, base_url('Lainnya'));
		$this->breadcrumb->append_crumb('Detail Pemasukan Lainnya', $this->parents);

		$data['title']	= 'Detail Pemasukan Lainnya | SMK DUMMY KIPE';
		$data['judul']	= 'Detail Pemasukan Lainnya';
		$data['icon']	= $this->icon;
		$this->template->views('Backend/'.$this->parents.'/v_Detail', $data);
	}

	public function edit($id){
		$data = $this->db->get_where($this->table, ['id' => $id])->row();
		echo json_encode($data);
	}

	function Simpan(){
		$id = $this->input->post('id');
		$total = (float) filter_string($this->input->post('nominal', TRUE));
		$jenis = filter_string($this->input->post('jenis', TRUE));
		$keterangan = filter_string($this->input->post('keterangan', TRUE));

		if (!empty($id)) {
			// Update
			$old = $this->db->get_where($this->table, ['id' => $id])->row_array();
			$diff = $total - (float) ($old['nominal'] ?? 0);

			$this->db->where('id', $id)->update($this->table, [
				'nominal'    => $total,
				'jenis'      => $jenis,
				'keterangan' => $keterangan,
			]);

			if ($diff != 0) {
				$this->M_General->update_kas('kas_masuk', $diff);
			}
		} else {
			// Insert baru
			$insert = [
				'nominal'	 => $total,
				'sekarang'	 => sekarang(),
				'time'	     => waktu(),
				'jenis'      => $jenis,
				'keterangan' => $keterangan,
			];
			$this->db->insert($this->table, $insert);
			$this->M_General->update_kas('kas_masuk', $total);
		}

		$data['status'] = TRUE;
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}

	public function Hapus($id){
		$item = $this->db->get_where($this->table, ['id' => $id])->row_array();
		if ($item) {
			$nominal = (float) $item['nominal'];
			$this->db->where('id', $id)->delete($this->table);
			$this->M_General->update_kas('kas_masuk', -$nominal);
			$data['status'] = TRUE;
		} else {
			$data['status'] = FALSE;
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($data));
	}
}
