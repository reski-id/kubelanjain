<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";

	function __construct() {
			parent::__construct();
			$id_user = get_session('id_user');
			if(!isset($id_user)) { redirect($this->re_log); }
	}

	public function mode()
	{
		if (isset($_POST)) {
			$id_user = get_session('id_user');
			mode()=='' ? $stt='1' : $stt='0';
			update_data('user', array('mode'=>$stt), array('id_user'=>$id_user));
			echo json_encode(array('stt'=>1));
		}
	}

	public function index()
	{
		$data = array(
			'judul_web' => web('title_web'),
			'content'		=> "$this->view/dashboard/dashboard_".lv()
		);
		$this->load->view("$this->view/index", $data);
	}

	public function bc_info()
	{
		check_permission('page', 'read', 'users/bc_info');
		$data = array(
			'judul_web' => 'BC Information',
			'content'		=> "$this->view/index_form",
      'view'      => "$this->view/bc_info/index",
			'col'			  => '6'
		);
		$this->load->view("$this->view/index", $data);
	}

	function get_user_bc()
	{
		check_permission('page', 'read', 'users/bc_info');
		if (isset($_POST)) {
			$jenis = post('jenis');
			$this->db->select('id_user, no_hp, nama_lengkap');
			if (in_array($jenis, array(1,2))) {
				$tbl = 'v_user_biodata_mitra';
				$this->db->where('type_id', $jenis);
			}else {
				$tbl = 'v_user_biodata_reseller';
			}
			$this->db->where('status', '1');
			$this->db->order_by('nama_lengkap', 'ASC');
			$get = get($tbl)->result_array();
			echo json_encode($get);
		}
	}

	public function profile($aksi='')
	{
		check_permission('page', 'read', 'users/profile');
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		if($level==0 && ($aksi!='reset_password' && $aksi!='edit')){ redirect('users/profile/edit'); }
		$data = array(
			'judul_web' => "Profile",
			'content'		=> "$this->view/akun/profile",
		);
		$this->load->view("$this->view/index", $data);
	}

	public function reset_password()
	{
		check_permission('page', 'read', 'users/reset_password');
		$url="$this->view/reset_password";
		$id_user = get_session('id_user');
		$un 		 = get_session('username');
		$data = array(
			'judul_web' => "Ubah Password",
			'content'		=> "$this->view/index_form",
      'view'      => "$this->view/akun/reset_password",
			'col'			  => '6'
		);
		$this->load->view("$this->view/index", $data);
	}


// PROSES ====================================================================
	public function profile_upload($v='')
	{
		cekAjaxRequest();
		if (isset($_POST)) {
			$p = 'form';
			view("$this->view/akun/upload_foto");
		}
	}

	public function proses($p='', $id='')
	{
		cekAjaxRequest();
		if (isset($_POST)) {
			$MODELnya = 'M_akun';
			if ($this->load->model($MODELnya)) {
				model($MODELnya,"proses_$p", $id);
			}else {
				echo json_encode(array('stt'=>0, 'pesan'=>'Function tidak ditemukan!'));
				exit;
			}
		}
	}

	public function benefit()
	{
		$folder = 'benefit';
		$p = 'tabel';
		$data = array(
			'judul_web' => "Benefit",
			'content'		=> "$this->view/index_form",
			'view'			=> "$this->view/$folder/$p",
			'url'				=> "$folder",
			'col'				=> 12
		);
		$this->load->view("$this->view/index", $data);
	}

	public function ajax_detail_benefit($stt='detail')
	{
		cekAjaxRequest();
		model('M_transaksi','benefit',$stt);
	}


	public function pembayaran_benefit()
	{
		check_permission('page', 'read', 'users/pembayaran_benefit');
		if (get_session('level')!=0) { redirect('404'); }
		$folder = 'benefit';
		$p = 'pembayaran/index';
		$data = array(
			'judul_web' => "Pembayaran Benefit",
			'content'		=> "$this->view/index_form",
			'view'			=> "$this->view/$folder/$p",
			'url'				=> "$folder",
			'col'				=> 12
		);
		$this->load->view("$this->view/index", $data);
	}

	public function ajax_detail_pembayaran_benefit($stt='detail')
	{
		if (get_session('level')!=0) { redirect('404'); }
		cekAjaxRequest();
		model('M_transaksi','detail_pembayaran_benefit',$stt);
	}

	public function view_detail_pembayaran_benefit()
  {
		if (get_session('level')!=0) { redirect('404'); }
		$tbl 			= 'user_biodata_mitra';
		$field_id = "id_user";
		$folder   = "benefit/pembayaran";
		$id='';
		if (isset($_POST)) {
			$id  = post("id");
			$data['tbl'] 		= $tbl;
			$data['id'] 		= $id;
			$data['urlnya'] = base_url("users/add_pembayaran_benefit");
			$data['query'] = get_field($tbl,array($field_id=>"$id"));
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			view("$this->view/$folder/$p", $data);
    }
  }

	public function add_pembayaran_benefit($id='')
	{
		if (get_session('level')!=0) { redirect('404'); }
		cekAjaxRequest();
		model('M_transaksi','add_pembayaran_benefit',$id);
	}

}
