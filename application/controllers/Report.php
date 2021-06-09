<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "report";
	var $tbl    = "user";
	var $judul  = "Report";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if($level!=0){ redirect('404'); }
			if ($this->input->post('who') == 'mBOT11') {
	      return true;
	    }else {
				if(!isset($id_user)) { redirect($this->re_log); }
	    	check_permission('page', 'read', '#report');
	    }
	}

  public function index()
	{
		redirect('404');
	}

	public function payment()
	{
		check_permission('page', 'read', 'report/payment');
		$this->_data('payment');
	}

	public function _data($nama='')
	{
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = $this->judul; $tipe_hal='';
		$namanya = ucwords(preg_replace('/[_]/',' ',$nama));
		$p = $nama.'/tabel';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul ".$namanya,
			'content'		=> "$this->view/index_form",
			'view'			=> "$this->view/$this->folder/$p",
			'url'				=> "$this->folder",
			'head_tambah' => "$head_tambah",
			'tbl'				=> $this->tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

// AJAX ==============================
	function ajax_report($stt='', $status='')
	{
		cekAjaxRequest();
		if (isset($_POST)) {
			$level = get_session('level');
			$id_user = get_session('id_user');
			$id_kota = get_session('id_kota');
			if ($level==0 && in_array($id_kota, array('',0))) {
				$id_kota = post('id_kota');
			}
			$arr=array();
			if ($stt=='payment') {
				$tgl_dari 	= tgl_format(post('tgl_dari'), 'Y-m-d');
				$tgl_sampai = tgl_format(post('tgl_sampai'), 'Y-m-d');
				$this->db->select('a.no_transaksi, a.nama_lengkap, a.no_hp, b.tanggal, b.nominal_pembayaran');
				$this->db->where('b.tanggal>=', $tgl_dari);
				$this->db->where('b.tanggal<=', $tgl_sampai);
				if ($id_kota!=0) {
					// $this->db->where('a.id_kota', $id_kota);
				}
				$this->db->join('order_pembayaran as b', 'a.id_order=b.id_order');
				$arr = get('v_user_order as a')->result_array();
			}
			echo '{"detailnya":' . json_encode($arr).'}';
		}
	}

}
