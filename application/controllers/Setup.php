<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setup extends CI_Controller {

	var $index 	= "users";
	var $view 	= "setup";
	var $re_log = "auth/login";
	var $folder = "view";
	var $judul  = "Setup";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			// menu_akses(0,'view');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			// if($level==0 && $id_user==1){}else{ redirect('404'); }
			check_permission('page', 'read', "#setup");
	}

  public function index()
	{
		redirect('404');
	}

	public function menu($akun_menu=0,$aksi='',$lvl_menu='',$id=0)
	{
		check_permission('page', 'read', "setup/menu/$akun_menu");
		$id_user = get_session('id_user');
		$level 	 = get_session('level');

		$query = array();
		$menunya = array('Lainnya','Mitra','Reseller');
    $judul = $this->judul. " MENU ".$menunya[$akun_menu];

		$Lebar_BOX = 'tabel'; $btnAdd=''; $url = $this->view."/menu";
		if ($aksi=='t') {
			check_permission('page', 'create', "setup/menu/$akun_menu");
			$p = 'form';  $Lebar_BOX = $p;
		}elseif ($aksi=='e') {
			check_permission('page', 'update', "setup/menu/$akun_menu");
			$p = 'form';  $Lebar_BOX = $p;
			$this->db->order_by('urutan', 'ASC');
			$query = get_field('menu', array('id_menu'=>$id));
		}else {
			$p = 'index'; $url = $this->view;
			if (check_permission('view', 'create', "setup/menu/$akun_menu")):
				$btnAdd .= '<div class="float-right"><select id="add_menu" class="form-control" onchange="Q_tambah();">';
				$btnAdd .= '<option value="" disabled selected> -===- Add MENU -===- </option>';
				for ($i=0; $i <=3; $i++) {
					$btnAdd .= '<option value="'.$i.'">Level '.$i.'</option>';
				}
				$btnAdd .= '</select></div>';
			endif;
		}

		$data = array(
			'judul_web' => $judul,
			'content'		=> "$this->view/$this->folder/index",
      'view'      => "$this->view/$this->folder/menu/$p",
			'query'		  => $query,
			'url'				=> $url,
			'tblnya'		=> 'menu',
			'btnAdd'		=> $btnAdd,
			'Lebar_BOX'	=> $Lebar_BOX,
			'akun_menux'	=> $akun_menu
		);
		$this->load->view("$this->index/index", $data);

		if (isset($_POST['btnsimpan'])) {
			$url="$this->view/menu/$akun_menu"; $tbl='menu';
			$this->db->trans_begin();
			$post = post_all(array('btnsimpan','url'));
			$post['akun_menu'] = $akun_menu;
			if ($lvl_menu==0) {
				$post['master_menu'] = 0;
			}
			$post['level_menu'] = $lvl_menu;
			$post['icon'] = post('icon',1);
			$URL = post('url');
			if ($URL=='') { $URL = '#'; }
			$post['url'] = $URL;
			if ($id=='') {
				check_permission('page', 'create', "setup/menu/$akun_menu");
				$this->db->order_by('urutan', 'DESC');
				$this->db->limit(1);
				$get = get($tbl)->row();
				if (empty($get)) { $urutan=0; }else{ $urutan=$get->urutan; }
				$post['urutan'] = $urutan + 1;
				// log_r($post);
				$simpan = add_data($tbl, $post);
			}else {
				check_permission('page', 'update', "setup/menu/$akun_menu");
				$simpan = update_data($tbl, $post, array('id_menu'=>$id));
			}
			if ($simpan) {
				$this->db->trans_commit();
        pesan('success','msg','Sukses!',"Data berhasil disimpan","$url");
      }else {
				$this->db->trans_rollback();
        pesan('danger','msg','Gagal!',"Silahkan coba lagi","$url");
      }
		}

	}

	public function simpan_urutan($akun_menu='')
	{
		if (!check_permission('view', 'update', "setup/menu/$akun_menu")) {
			echo json_encode(array('stt'=>'x'));
			exit;
		}
		if (isset($_POST)) {
			$stt=0; $tbl='menu'; $this->db->trans_begin();
			$data = json_decode($_POST['urutan_menu']);
			$data = objectToArray($data);
			$data_id = '';
			foreach ($data as $key => $element) { //0
			    $data_id .= "0 > ".$element['id'].", ";
					if (!empty($element['children'])) {
						$data_id .= get_chil($element, $element['id']);
					}
			}
			$data_arr = explode(', ', substr($data_id,0,-2));
			foreach ($data_arr as $key => $value) {
				$stt=1;
				$field = explode(' > ', $value);
				$master_menu = $field[0];
				$id_menu 		 = $field[1];
				$get_lvl = get($tbl, array("id_$tbl"=>$master_menu))->row();
				if (empty($get_lvl)) {
					$lvl = 0;
				}else {
					$lvl = $get_lvl->level_menu + 1;
				}
				$where = array("id_$tbl"=>$id_menu);
				update_data($tbl, array('level_menu'=>$lvl), $where);
				$simpan = update_data($tbl, array('master_menu'=>$master_menu, 'urutan'=>$key), $where);
				if (!$simpan) {
					$this->db->trans_rollback();
					break;
				}
				$x[] = "$id_menu - $key";
			}
			$this->db->trans_commit();
			echo json_encode(array('stt'=>$stt));
		}
	}

	public function web()
	{
		check_permission('page', 'read', "setup/web");
		$this->_data(uri(2));
	}

	public function email()
	{
		check_permission('page', 'read', "setup/email");
		$this->_data(uri(2));
	}

	public function _data($method='')
	{
		if(!method_exists($this,$method)){ redirect('404'); }
    $judul = $this->judul. " ".ucwords(preg_replace('/[_]/',' ',$method)). " ".ucwords(preg_replace('/[_]/',' ',uri(3)));
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->index/index_form",
			'view'			=> "$this->view/view/$method/form",
			'col'				=> '6',
		);
		$this->load->view("$this->index/index", $data);
	}


// PROSES ====================================================================
	public function proses($p='',$stt='',$id='')
	{
		if (isset($_POST)) {
			$MODELnya = 'M_setup';
			if ($this->load->model($MODELnya)) {
				$data=$p;
				if($stt=='simpan'){ $data=$stt; }
				model($MODELnya,"proses_$data",$p,$id);
			}else {
				echo json_encode(array('stt'=>0, 'pesan'=>'Function tidak ditemukan!'));
				exit;
			}
		}
	}
// PROSES ====================================================================


	public function api()
	{
		check_permission('page', 'read', "setup/api");
		$this->__data('api');
	}

	public function __data($tbl='', $get='',$id='')
	{
		if(!method_exists($this,$tbl)){ redirect('404'); }
    $id_user = get_session('id_user');
		$level 	 = get_session('level');
		$judul = $this->judul. " ".ucwords(preg_replace('/[_]/',' ',$tbl)). " ".ucwords(preg_replace('/[_]/',' ',uri(3)));
		$p = 'tabel';
		if($get=='get'){ $p='form_menu'; }
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->index/index_form",
			'view'			=> "$this->view/$this->folder/$tbl/$p",
			'url'				=> "$this->view",
			'url_modal'	=> base_url("$this->view/view_data"),
			'url_hapus' => base_url("$this->view/hapus"),
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->index/index", $data);
	}

	public function list_data($tbl='')
	{
		check_permission('page', 'read', "setup/api");
		if($tbl==''){ exit; }
		$field_id="id_$tbl";
		cekAjaxRequest();
		$field = '';
		foreach (list_fields($tbl) as $key => $value):
			$field .= ", $value";
		endforeach;
    $this->datatables->select("$field_id as id $field");
    $this->datatables->from($tbl);
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='')
  {
		check_permission('page', 'read', "setup/api");
		if($tbl==''){ exit; }
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['query'] = get_field($tbl,array($field_id=>"$id"));
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['urlnya'] = base_url("$this->view/simpan/$tbl");
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			if(substr($tbl,0,4)=='set_'){ $file=substr($tbl,4); }else{ $file=$tbl; }
			view("$this->view/$this->folder/$file/$p", $data);
    }
  }

	function simpan($tbl='',$id='')
  {
		if($tbl==''){ exit; }
    if (isset($_POST)) {
			$this->db->trans_begin();
			$id  = decode($id);
			$post = post_all(array("id_$tbl","id",'simpan'));
			if ($id=='') {
				$post['tgl_input'] = tgl_now();
					if (!check_permission('view', 'create', "setup/api")) {
						echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
						exit;
					}
				$simpan = add_data($tbl,$post);
			}else{
				@$post['tgl_update'] = tgl_now();
					if (!check_permission('view', 'update', "setup/api")) {
						echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
						exit;
					}
				$simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
			}
			if ($simpan) {
				$this->db->trans_commit();
        $stt=1; $pesan='Data berhasil disimpan';
      }else {
				$this->db->trans_rollback();
				$stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
      }
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
    }
  }


	function status_menu($tblnya='')
	{
		$id_user = get_session('id_user');
		$level = get_session('level');
		if($level==0 && $id_user==1){}else{
			echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
			exit;
		}
		if($tblnya==''){ exit; }
		if (isset($_POST)) {
			$id  = post('id');
			$where = array("id_$tblnya"=>$id);
			$get = get_field($tblnya, $where)['status'];
			if ($get==0) { $stt=1; }else { $stt=0; }
			$simpan = update_data($tblnya, array('status'=>$stt), $where);
			if (!$simpan) { $stt='x'; }
			echo json_encode(array('stt'=>$stt));
			exit;
		}
	}

	function hapus($tblnya='')
	{
		if($tblnya==''){ exit; }
		if (isset($_POST)) {
			$id  = decode(post('id'));
			if ($tblnya=='menu') {
				$this->db->select('akun_menu');
				$akun = get_field('menu', array('master_menu'=>$id))['akun_menu'];
					if (!check_permission('view', 'delete', "setup/menu/$akun")) {
						echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
						exit;
					}
				delete_data($tblnya,array("master_menu"=>$id));
			}
			$url = '';
			if ($tblnya=='set_api') {
				$url = 'setup/api';
			}
			if ($url!='') {
				if (!check_permission('view', 'delete', "$url")) {
					echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
					exit;
				}
			}else {
				$id_user = get_session('id_user');
				$level = get_session('level');
				if($level==0 && $id_user==1){}else{
					echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
					exit;
				}
			}
			delete_data($tblnya,array("id_$tblnya"=>$id));
			echo json_encode(array('stt'=>1));
			exit;
		}
	}

}
