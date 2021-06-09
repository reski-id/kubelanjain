<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Belanja extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "belanja";
	var $judul  = "Belanja";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
	}

  public function index()
	{
		check_permission('page', 'read', 'belanja');
		$this->_data('belanja');
	}

	public function _data($tbl='', $folder='')
	{
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = $this->judul;

		$contentnya = "$this->view/$this->folder/tabel";
		$viewnya    = '';

		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> $contentnya,
			'view'			=> $viewnya,
			'url'				=> "$this->folder",
			'url_modal'	=> base_url("$this->folder/view_data/$tbl"),
			'url_import'=> base_url("$this->folder/import/$tbl"),
			'url_hapus' => base_url("$this->folder/hapus/$tbl"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	function get_data($tgl='', $cari='', $limit='')
	{
		$this->db->select('nama_item_master AS nama_item, kode_satuan, isian, harga, SUM(to_ons) AS total_ons');
		$this->db->like('tgl_input', $tgl, 'after');
		if (!empty($cari)) {
			$this->db->where("(nama_item_master LIKE '%". $cari."%')",null,false);
		}
		$this->db->order_by('nama_item', 'ASC');
		if (!empty($limit)) { $this->db->limit($limit); }
		return get('order_item')->result_array();
	}

	public function list_data()
	{
		$cari  = post('cari');
		$tgl 	 = tgl_format(post('tgl'), 'Y-m-d');
		$limit = post('limit');
		$arr = array();

		// Khusus ONS, GRAM & KG
		$this->db->where_in('kode_satuan', array('B','D','G'));
		$this->db->where_not_in('nama_item_master', array('-','0'));
		$this->db->group_by('plu_master');
		$get = $this->get_data($tgl, $cari, $limit);
		// log_r($this->db->last_query());
		foreach ($get as $key => $value) {
			$value['harga_satuan'] = konversi_satuan_harga(1, $value['kode_satuan'], $value['isian'], $value['harga']);
			$satuan = konversi_satuan(1, 'G', 'D', $value['total_ons'], 'kode');
			$value['satuan'] = $satuan. ' KG';
			$value['total_harga'] = $value['harga_satuan'] * $satuan;
			unset($value['total_ons']);
			$arr[] = $value;
		}

		// Khusus ITEM Manual
		$this->db->select('SUM(qty) AS qty, id_item_satuan');
		$this->db->where_in('id_item_satuan', array(null,0));
		$this->db->group_by('nama_item');
		$get2 = $this->get_data($tgl, $cari, $limit);
		foreach ($get2 as $key => $value) {
			$value['harga_satuan'] = $value['harga'];
			$qty = $value['qty'];
			$exp_qty = explode('.', $qty);
			if ($exp_qty[1] == '00') { $qty = $exp_qty[0]; }
			$satuan = $qty;
			$value['satuan'] = $satuan;
			$value['total_harga'] = $value['harga_satuan'] * $satuan;
			unset($value['total_ons']);
			$arr[] = $value;
		}

		// Khusus Selain ONS, GRAM, KG & ITEM Manual
		$this->db->select('SUM(qty) AS qty, id_item_satuan');
		$this->db->where_not_in('kode_satuan', array('B','D','G'));
		$this->db->where_not_in('id_item_satuan', array(null,0));
		$this->db->group_by('plu_master');
		$get3 = $this->get_data($tgl, $cari, $limit);
		foreach ($get3 as $key => $value) {
			$value['harga_satuan'] = $value['harga'];
			$qty = $value['qty'];
			$exp_qty = explode('.', $qty);
			if ($exp_qty[1] == '00') { $qty = $exp_qty[0]; }
			$satuan = $qty. ' ' .get_name_item_satuan($value['id_item_satuan']);
			$value['satuan'] = $satuan;
			$value['total_harga'] = $value['harga_satuan'] * $satuan;
			unset($value['total_ons']);
			$arr[] = $value;
		}
		// log_r(str_to_number('B'));
		usort($arr, function($a, $b) {
	      return str_to_number(substr($a['nama_item'],0,1)) - str_to_number(substr($b['nama_item'],0,1));
	  });
		// log_r($arr);
		echo '{"detailnya":' . json_encode($arr).'}';
	}

}
