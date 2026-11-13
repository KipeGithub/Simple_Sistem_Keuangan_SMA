<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Lainnya extends CI_Model {

	function getAllData(){
		$this->datatables->select("id, DATE_FORMAT(tanggal,'%d-%m-%Y') AS Tgl, jenis, keterangan, nominal");
		$this->datatables->from('lainnya');
		return $this->datatables->generate();
	}

	function getDetailData($detail =''){
		$this->datatables->select("id, DATE_FORMAT(tanggal,'%d-%m-%Y - %H:%i:%s WIB') AS Tgl, nominal, jenis, keterangan");
		$this->datatables->from('lainnya');
		$this->datatables->where('sekarang', $detail);
		return $this->datatables->generate();
	}
}
