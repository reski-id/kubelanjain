<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_management extends CI_Controller {

	var $index 	= "users";
	var $view 	= "setup";
	var $re_log = "auth/login";
	var $folder = "view";
	var $menu 	= "user_management";
	var $judul  = "Setup";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			// if($level==0 && $id_user==1){}else{ redirect('404'); }
	}

  public function index()
	{
		redirect('404');
	}

	public function data_user()
	{
		check_permission('page', 'read', "user_management/data_user");
		$this->_data('user');
	}

	public function approval()
	{
		check_permission('page', 'approve', "user_management/data_user");
		$this->_data('approval_tipe', uri(3));
	}

	public function _data($tbl='', $uri3='')
	{
		if (!table_exists($tbl)){ redirect('404'); }
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
		$judul = "Management $namanya";
		if ($tbl=='approval_tipe') {
			$uri3 = decode($uri3);
			$this->db->select('id_user, nama_lengkap');
			$cek_user = get('user_biodata_management', array('id_user'=>$uri3))->row();
			if (empty($cek_user)) { redirect('404'); }
			$judul = 'Setup User Approval '.$cek_user->nama_lengkap;
		}
		$p = 'tabel';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->index/index_form",
			'view'			=> "$this->view/$this->folder/$this->menu/$tbl/$p",
			'url'				=> "$this->menu",
			'url_modal'	=> base_url("$this->menu/view_data/$tbl"),
			'url_import'=> base_url("$this->menu/import/$tbl"),
			'url_hapus' => base_url("$this->menu/hapus/$tbl"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->index/index", $data);
	}

	public function list_data($tbl='', $id='', $id2='')
	{
		check_permission('page', 'read', "user_management/data_user");
		if($tbl==''){ exit; }
		$field_id="A.id_$tbl";
		cekAjaxRequest();
		if ($tbl=='user') {
			$this->datatables->select("$field_id as id, A.username, B.nama_lengkap, B.no_hp, A.status, get_name_akses(B.jenis_akses, B.id_gudang_kota, B.id_gudang, B.id_management_akses) AS gudang");
			$this->datatables->join('user_biodata_management AS B', 'A.id_user=B.id_user');
			$this->datatables->where("A.level", '0');
		}elseif ($tbl=='approval_tipe') {
			$this->datatables->select("$field_id as id, nama_approval_tipe, ket");
			$this->datatables->where("B.id_user", decode($id));
			$this->datatables->join("approval AS B", "A.id_approval_tipe=B.id_approval_tipe");
			$this->datatables->group_by("A.id_approval_tipe");
		}
		$this->datatables->from("$tbl AS A");
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='', $id_kota='')
  {
		check_permission('page', 'read', "user_management/data_user");
		if($tbl==''){ exit; } $tblnya=$tbl;
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if ($tbl=='approval_tipe') {
				if ($id_kota!='') {
					$data['id_user'] = decode($id_kota);
				}
			}
			if($id==''){
				$stt='';
				$data['query'] = array();
			}else{
				$stt=1;
				if ($tbl=='user') {
			    $this->db->select("A.id_user, B.nama_lengkap, B.no_hp, A.status, B.id_gudang_kota, B.id_gudang, A.username, A.password, A.tgl_input, B.jenis_akses, B.id_management_akses");
					$this->db->join('user_biodata_management AS B', 'A.id_user=B.id_user');
				}elseif ($tbl=='approval_tipe') {
					$this->datatables->select("$field_id as id, A.nama_approval_tipe AS tipe, A.*");
				}
				$data['query'] = get("$tbl AS A",array("A.$field_id"=>"$id"))->row_array();
			}
			$data['tbl'] 		= $tblnya;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['urlnya'] = base_url("$this->menu/simpan/$tblnya");
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			view("$this->view/$this->folder/$this->menu/$tblnya/$p", $data);
    }
  }



// SIMPAN =============================================
	function simpan($tbl='',$id='', $id_kota='')
	{
		if($tbl==''){ exit; }$d_username=''; $d_no_hp=''; $d_kota='';
		if (isset($_POST)) {
			$this->db->trans_begin();
			$id  = decode($id);
			if($tbl=='approval'){
				model('M_master','add_approval', $id, decode($id_kota));
				exit;
			}
			if ($tbl=='user') {
				$username = post('username');
				$no_hp 		= post('no_hp');
				if($id!=''){
					$this->db->select('username');
					$data_lama = get_field($tbl, array('id_user'=>$id));
					$d_username = $data_lama['username'];
					$this->db->select('no_hp');
					$data_lama2 = get_field($tbl.'_biodata_management', array('id_user'=>$id));
					$d_no_hp    = $data_lama2['no_hp'];
					// $d_kota   	= $data_lama['id_kota'];
				}
				// log_r($id);
				// if($id!=''){
				// 	$this->db->where('id_kota!=', $d_kota);
				// }
			  // if (get('v_user', array('level'=>'0', 'id_kota'=>post('id_kota')))->num_rows() <> 0) {
				// 	$kota = get_name_kota(post('id_kota'));
	      //   $pesan = "USER di KOTA <b>'$kota'</b> sudah tersedia";
				// 	echo json_encode(array('stt'=>0, 'pesan'=>$pesan));
				// 	exit;
	      // }
				$cek_data = get_no_hp($no_hp, $d_no_hp, 'user_biodata_management');
				if ($cek_data->num_rows() <> 0) {
	        $pesan = "No. HP <b>'$no_hp'</b> sudah tersedia";
					echo json_encode(array('stt'=>0, 'pesan'=>$pesan));
					exit;
	      }
				$cek_data = get_un($username, $d_username, '0');
				if ($cek_data->num_rows() <> 0) {
	        $pesan = "Username <b>'$username'</b> sudah tersedia";
					echo json_encode(array('stt'=>0, 'pesan'=>$pesan));
					exit;
	      }
				$post['username'] = $username;
				$post['password'] = encode(post('password'));
				$post['level'] 		= '0';
				$post['mode'] 		= '0';
				$post['status'] 	= post('status');
				$post['tgl_input'] = tgl_now();
			} else {
				$post = post_all(array("id_$tbl","id",'simpan'));
			}
			if ($id=='') {
				if (!check_permission('view', 'create', "user_management/data_user")) {
					echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
					exit;
				}
				$simpan = add_data($tbl, $post);
				$id_new = $this->db->insert_id();
				$post2['id_user'] 			= $id_new;
			}else{
				if (!check_permission('view', 'update', "user_management/data_user")) {
					echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
					exit;
				}
				$simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
			}

			if ($tbl=='user') {
				// $post2['id_provinsi'] 	= post('id_provinsi');
				// $post2['id_kota'] 			= post('id_kota');
				$post2['nama_lengkap'] 	 = post('nama_lengkap');
				$post2['no_hp']      	 = post('no_hp');
				$post2['id_gudang_kota'] = post('id_gudang_kota');
				$post2['jenis_akses']    = 0;
				$post2['id_gudang'] 	 = 0;
				$post2['id_management_akses'] = 0;
				if (post('id_gudang_kota')!=0) {
					$post2['jenis_akses']  = post('jenis_akses');
					if ($post2['jenis_akses'] == 1) {
						$post2['id_gudang']  = post('id_gudang');
					}elseif ($post2['jenis_akses'] == 2) {
						$post2['id_management_akses'] = post('id_management_akses');
					}
				}
				if ($id=='') {
					$simpan = add_data($tbl."_biodata_management",$post2);
					// if($id==''){ add_user_total($id_new); }
				}else{
					$simpan = update_data($tbl."_biodata_management",$post2, array("id_$tbl"=>$id));
				}
			}

			if ($simpan) {
				$this->db->trans_commit();
				$stt=1; $pesan='Data berhasil disimpan';
			} else {
				$this->db->trans_rollback();
				$stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
		}
	}
// SIMPAN =============================================

	// function hapus($tblnya='')
	// {
	// 	if($tblnya==''){ exit; }
	// 	if (isset($_POST)) {
	// 		$id = decode(post('id'));
	// 		$where = array("id_$tblnya"=>$id);
	// 		$hapus = delete_data($tblnya, $where);
	// 		if ($hapus) {
	// 			$stt=1; $pesan='Data berhasil dihapus';
	// 		}else {
	// 			$stt=0; $pesan='Gagal Hapus, silahkan coba lagi!';
	// 		}
	// 		echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
	// 		exit;
	// 	}
	// }

	function ajax_gudang()
	{
		cekAjaxRequest();
		model('M_ajax', 'get_gudang');
	}



	// MENU
	public function menu($id_user='')
	{
		check_permission('page', 'read', "user_management/data_user");
		$id_user = decode($id_user);

		$Lebar_BOX = 'tabel'; $btnAdd=''; $url = $this->view."/menu";
		$p = 'index'; $url = $this->view;
		$this->db->select('nama_lengkap, id_gudang, id_gudang_kota');
		$get_user = get('user_biodata_management', array('id_user'=>$id_user))->row();
		if (empty($get_user)) { redirect('404'); }
		$namanya = $get_user->nama_lengkap;
		if ($get_user->id_gudang!=0) {
			$namanya .= ' [ '.get_name_gudang($get_user->id_gudang). ' - '.get_name_gudang_kota($get_user->id_gudang_kota). ' ]';
		}
		$data = array(
			'judul_web' => 'Akses Menu '.$namanya,
			'content'		=> "$this->index/index_form",
			'view'			=> "$this->view/$this->folder/$this->menu/menu/$p",
			'url'				=> $this->menu,
			'col'				=> '12'
		);
		$this->load->view("$this->index/index", $data);
	}

	function set_permission_menu($id_user='')
	{
		if (!check_permission('view', 'update', "user_management/data_user")) {
			echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
			exit;
		}
		if (isset($_POST['permission'])) {
			$id_user = decode($id_user);
			$this->db->select('id_user');
			if (empty(get('user', array('id_user'=>$id_user))->row())) {
				redirect('404');
			}
			$id_menu = post('id');
			$value   = post('permission');
			$checked = post('checked');

			$tbl   = 'menu_permission';
			$where = array('id_user'=>$id_user, 'id_menu'=>$id_menu);
			$get   = get($tbl, $where)->row();

			$post['id_menu'] = $id_menu;
			$post['id_user'] = $id_user;
			$post['tgl_update'] = tgl_now();
			$post['update_by']  = get_session('id_user');
			if(empty($get)){
				$arr = array();
	    }else{
				if ($get->permission==null || $get->permission=='') {
					$arr = array();
				}else {
					$arr = unserialize($get->permission);
				}
	    }

	    #apakah di check atau un check (ditambahkan / dihapus)
			//hapus
			 foreach ($arr as $key => $row) {
					if($arr[$key]==$value){ unset($arr[$key]); }
			 }
			 //tambahkan
	    if($checked=="true"){ array_push($arr, $value); }

			$post['permission'] = serialize($arr);
			// log_r($post);
			if (empty($get)) {
				$simpan = add_data($tbl, $post);
			}else {
				$simpan = update_data($tbl, $post, $where);
			}
			if ($simpan) {
				$stt=1; $pesan='Berhasil diupdate!';
			}else {
				$stt=0; $pesan="Silahkan coba lagi!";
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
		}
	}


	function get_approval_ket()
	{
		if (isset($_POST['p'])) {
			$q = get_field('approval_tipe', array('id_approval_tipe'=>post('p')))['ket'];
			echo json_encode(array('ket'=>$q));
			exit;
		}
	}

	function ajax_get_user_approval_tipe($stt='')
	{
		cekAjaxRequest();
		model('M_ajax','get_user_approval_tipe', $stt);
  }

	function ajax_get_list_user_approval()
	{
		cekAjaxRequest();
		model('M_ajax','get_list_user_approval');
	}

	function approval_hapus($id='')
	{
		cekAjaxRequest();
		model('M_master','approval_hapus', $id);
	}

}
