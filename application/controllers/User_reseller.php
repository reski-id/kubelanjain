<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_reseller extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "user_reseller";
	var $judul  = "Reseller";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			$redirect=true;
			if ($level==2 && in_array(uri(2), array('view_data', 'simpan'))) {
				$redirect=false;
			}
			if ($level==1 && in_array(uri(2), array('vlist', 'list_referral'))) {
				$redirect=false;
			}
			if ($level==0) {
				$redirect=false;
			}
			if($redirect){ redirect('404'); }
			check_permission('page', 'read', 'user_reseller');
	}

  public function index()
	{
		$this->_data('reseller');
	}

	public function _data($tbl='')
	{
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = $this->judul;
		$p = 'tabel';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->view/$this->folder/$p",
			'url'				=> "$this->folder",
			'url_modal'	=> base_url("$this->folder/view_data/$tbl"),
			'url_import'=> base_url("$this->folder/import/$tbl"),
			'url_hapus' => base_url("$this->folder/hapus"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	public function vlist()
	{
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = "Data ".$this->judul;
		$p = 'referral/list';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->view/$this->folder/$p",
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	public function list_data($tbl='', $id_prov='-', $id_kota='-', $id_mitra=0, $status=0)
	{
		if($tbl==''){ exit; }
		$tbl = 'v_user_biodata_reseller';
		$field_id="id_user";
		cekAjaxRequest();
		$field = '';
		foreach (list_fields($tbl) as $key => $value):
			if (in_array($value, array('nama_lengkap','jenis_kelamin','no_hp','provinsi','kota','id_mitra','status'))) {
				$field .= ", $value";
			}
		endforeach;
    $this->datatables->select("$field_id as id $field");
		if (!in_array($id_prov, array('-',0))) {
			$this->datatables->where('id_provinsi', $id_prov);
		}
		if (!in_array($id_kota, array('-',0))) {
			$this->datatables->where('id_kota', $id_kota);
		}
		if (!in_array($id_mitra, array('-',0))) {
			$this->datatables->where('type_id', $id_mitra);
		}else {
			$this->datatables->where_in('type_id', array(3,4,5));
		}
		if ($status==0) {
			$this->datatables->where("jenis_kelamin", null);
			$this->datatables->where('status', '1');
		}elseif ($status==1) {
			$this->datatables->where('status', '1');
			$this->datatables->where("jenis_kelamin!=", null);
		}elseif ($status==2) {
			$this->datatables->where('status', '0');
		}
		$this->datatables->from($tbl);
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='', $id_kota='')
  {
		// if($tbl==''){ exit; }
		$tbl = 'v_user_biodata_reseller';
		$field_id="id_user";
		$id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['urlnya'] = base_url("$this->folder/simpan");
			$this->db->where_in('type_id', array(3,4,5));
			$data['query'] = get_field($tbl,array($field_id=>"$id"));
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			view("$this->view/$this->folder/$p", $data);
    }
  }

	function simpan($id='', $level='')
	{
		cekAjaxRequest();
		model('M_akun','proses_update_data', $id, $level);
	}

	function hapus($x='')
	{
		if (isset($_POST)) {
			$tbl = 'user';
			$id  = decode(post('id'));
			$where = array("id_$tbl"=>$id);
			if ($x=='oKe') { //Non-Active
				$hapus = update_data($tbl, array("status"=>'0'), $where);
				$pesan = 'Akun berhasil diubah menjadi Tidak Aktif';
			}elseif ($x=='aktifkan') { //Active
				$hapus = update_data($tbl, array("status"=>'1'), $where);
				$pesan = 'Akun berhasil di Aktifkan';
			}else {
				// $hapus = hapus_user_global('delivery', $id);
				// if ($hapus) {
				// 	$pesan = 'Data berhasil dihapus';
				// }
			}
			if ($hapus) {
				$stt=1;
			}else {
				$stt=0; $pesan='Gagal Hapus, silahkan coba lagi!';
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
		}
	}

}
