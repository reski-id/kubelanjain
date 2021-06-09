<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "order";
	var $judul  = "Order";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			if($level==0 && $id_user==1){
			}else{
				redirect('404');
			}
	}

  public function index()
	{
		$this->_data('order');
	}

	public function _data($tbl='order')
	{
		check_permission('page', 'read', "$tbl");
		if (!table_exists($tbl)){ redirect('404'); }
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
		$judul = "$namanya";
		$p='tabel';
		$contentnya = "$this->view/$this->folder/$p";
		$viewnya    = '';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> $contentnya,
			'view'			=> $viewnya,
			'url'				=> "$this->folder",
			'url_modal'	=> base_url("$this->folder/view_data/$tbl"),
			'url_hapus' => base_url("$this->folder/hapus/$tbl"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	public function list_data($tbl='', $id='', $id2='', $id3='')
	{
		if($tbl==''){ exit; }
		$field_id="id_$tbl";
		cekAjaxRequest();
		$field = '';
		foreach (list_fields($tbl) as $key => $value):
			$field .= ", $value";
		endforeach;
		$this->datatables->select("$field_id as id $field");
		$this->datatables->from($tbl);
		if (in_array($tbl, array('kota'))) {
			if ($id!=0) {
				$this->datatables->where('id_provinsi', $id);
			}
			$this->datatables->where('status', $id2);
		}
		if (in_array($tbl, array('kecamatan'))) {
			if ($id!=0) { $this->datatables->where('id_provinsi', $id); }
			if ($id2!=0) { $this->datatables->where('id_kota', $id2); }
			$this->datatables->where('status', $id3);
		}
		if (in_array($tbl, array('order'))) {
			$this->datatables->where('status', $id);
		}
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='', $aksi='')
  {
		if($tbl==''){ exit; }
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['id_kota'] = $aksi;
			$data['urlnya'] = base_url("$this->folder/simpan/$tbl");
			$tblnya=$tbl;
			$data['query'] = get_field($tblnya,array($field_id=>"$id"));
			// log_r($this->db->last_query());
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			if (substr($aksi, 0, 5)=='opsi_') {
				$p = 'modal_opsi/'.$aksi;
			}
			view("$this->view/$this->folder/$p", $data);
    }
  }

// SIMPAN =============================================
	function simpan($tbl='',$id='', $id_kota='')
	{ cekAjaxRequest();
		if($tbl==''){ exit; }
		if (isset($_POST)) {
				$this->db->trans_begin();
				$id  = decode($id);
				if($tbl=='order'){
					model('M_order','order_proses_simpan', $id);
				}
				elseif($tbl=='order_cancel'){
					if (isset($_POST['id'])) { model('M_order',$tbl, $id); }
				}
				elseif($tbl=='order_payment'){
					if (isset($_POST['id'])) { model('M_order',$tbl, $id); }
				}
				elseif($tbl=='order_done'){
					if (isset($_POST['benefit'])) { model('M_order',$tbl, $id); }
				}
				exit;
		}
	}
// SIMPAN =============================================

	function ajax_get_item_plu($status='')
	{
		cekAjaxRequest();
		model('M_order','get_item_plu', $status);
	}

	function ajax_get_item_update_harga($status='')
	{
		cekAjaxRequest();
		model('M_order','get_item_update_harga', $status);
	}

	function ajax_harga()
	{
		cekAjaxRequest();
		model('M_order','get_harga');
	}

	function cetak_struk($id='')
	{
		// cekAjaxRequest();
		// model('M_order', 'cetak_struk');
		$id = decode($id);
    $tbl = 'order';
    $data['get'] = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($data['get'])) { redirect('404'); }
		view('users/order/cetak/pdf', $data);
	  // $html = $this->output->get_output();
	  // // Load pdf library
	  // $this->load->library('pdf');
	  // $this->pdf->loadHtml($html, 'UTF-8');
	  // // $this->pdf->setPaper('A4', 'portrait');
		// // $customPaper = array(0,0,180,$height);
		// // $this->pdf->set_paper($customPaper);
		// $this->pdf->set_option('dpi', 30);
	  // $this->pdf->render();
		// $filename = "BKYB.pdf";
		// $this->pdf->stream($filename, array("Attachment"=> 0));
	  // $output = $this->pdf->output();
	}

}
