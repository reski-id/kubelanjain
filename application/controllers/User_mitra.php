<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_mitra extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "user_mitra";
	var $judul  = "Mitra";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			$redirect=true;
			if ($level==1 && in_array(uri(2), array('vlist', 'list_referral', 'view_data', 'simpan', 'cek_user_order'))) {
				$redirect=false;
			}
			if ($level==0) {
				$redirect=false;
			}
			if($redirect){ redirect('404'); }
	}

  public function v($type='')
	{
		if (!in_array($type, array(1,2))) {
			redirect('404');
		}
		check_permission('page', 'read', 'user_mitra/v/'.$type);
    $this->_data('mitra', $type);
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

	public function list_data($tbl='', $id_prov='-', $id_kota='-', $id_mitra=0, $status=0)
	{
		if($tbl==''){ exit; }
		check_permission('page', 'read', 'user_mitra/v/'.$id_mitra);
		$tbl = 'v_user_biodata_mitra';
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
			$this->datatables->where_in('type_id', array(1,2));
		}

		if ($status==0) {
			$this->datatables->where("jenis_kelamin", null);
			$this->datatables->where('status', '1');
		}elseif ($status==1) {
			$this->datatables->where("jenis_kelamin!=", null);
			$this->datatables->where('status', '1');
		}elseif ($status==2) {
			$this->datatables->where('status', '0');
		}
		$this->datatables->from($tbl);
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='', $referral='', $edit='')
  {
		// if($tbl==''){ exit; }
		$tbl = 'v_user_biodata_mitra';
		$field_id="id_user";
		$id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['referral']  = $referral;
			$data['urlnya'] 	 = base_url("$this->folder/simpan");
			$this->db->where_in('type_id', array(1,2));
			$data['query'] = get_field($tbl,array($field_id=>"$id"));
			if (post("input")==1) {
				if ($edit=='edit_fee') {
					$p = 'edit_fee';
				}else {
					$p = 'form';
				}
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

	function hapus($x='', $level='')
	{
		if (isset($_POST)) {
	    if (!check_permission('view', 'delete', 'user_mitra/v/'.$level)) {
	      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
	      exit;
	    }
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


// ========== IMPORT ==========
	public function import($tbl='')
	{
		if (isset($_POST)) {
			$id_user = get_session('id_user');
			$level 	 = get_session('level');
			$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
			$judul = "Import Data $namanya";
			$p = 'import';
			$data = array(
				'url_import' => base_url("$this->folder/aksi_import/$tbl"),
				'tbl'				 => $tbl,
			);
			$this->load->view("$this->view/$this->folder/$p", $data);
		}
	}

	function aksi_import($tbl=''){
		// if (!table_exists($tbl)){ redirect('404'); }
		$tbl = 'user_biodata_'.$tbl;
		$nm_aksi='';
		$this->db->trans_begin();
		if(isset($_FILES["file"]["name"])){
      $path = $_FILES["file"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
			$data=array(); $set_blm_ada=array();
      foreach($object->getWorksheetIterator() as $worksheet){
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        for($row=2; $row<=$highestRow; $row++){
					$nama_lengkap = set_POST($worksheet->getCellByColumnAndRow(0, $row)->getValue());
					$jk 	 				= set_POST($worksheet->getCellByColumnAndRow(1, $row)->getValue());
					if (strtolower($jk)=='pria'){ $jk='Laki - Laki'; }elseif (strtolower($jk)=='wanita'){ $jk='Perempuan'; }
					$no_hp 				= set_POST($worksheet->getCellByColumnAndRow(2, $row)->getValue());
					$email 				= strtolower(set_POST($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
					$provinsi 		= strtolower(set_POST($worksheet->getCellByColumnAndRow(4, $row)->getValue()));
					$kota 				= strtolower(set_POST($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
					$alamat 			= set_POST($worksheet->getCellByColumnAndRow(6, $row)->getValue());
					$pekerjaan 		= set_POST($worksheet->getCellByColumnAndRow(7, $row)->getValue());
					$no_rek 			= set_POST($worksheet->getCellByColumnAndRow(8, $row)->getValue());
					$nama_pemilik = set_POST($worksheet->getCellByColumnAndRow(9, $row)->getValue());
					$bank 				= strtolower(set_POST($worksheet->getCellByColumnAndRow(10, $row)->getValue()));

					if ($nama_lengkap=='' || $jk=='' || $no_hp=='' || $provinsi=='' || $kota=='' || $bank=='' || $no_rek=='' || $nama_pemilik=='') {
						continue;
					}

					$this->db->select('username');
					$cek_no_hp = get_field('user', array('username'=>$no_hp))['username'];
					if (!empty($cek_no_hp)) {
						// $set_blm_ada[] = "Nomor Handphone '$no_hp' sudah ada!";
						continue;
					}

					if ($email!='') {
						$this->db->select('email');
						$cek_email = get_field('user_biodata', array('email'=>$email))['email'];
						if (!empty($cek_email)) {
							$set_blm_ada[] = "Email '$email' sudah ada!";
							continue;
						}
					}

					$this->db->select('id_provinsi');
					$id_provinsi = get_field('provinsi', array('provinsi'=>$provinsi, 'status'=>1))['id_provinsi'];
					if (empty($id_provinsi)) {
						$set_blm_ada[] = "Provinsi '".strtoupper($provinsi)."' belum ada di Database!";
						continue;
					}

					$this->db->select('id_kota');
					$id_kota = get_field( 'kota', array('kota'=>$kota, 'id_provinsi'=>$id_provinsi, 'status'=>1))['id_kota'];
					if (empty($id_kota)) {
						$set_blm_ada[] = "Provinsi '".strtoupper($provinsi)."' & Kota '".strtoupper($kota)."' belum ada di Database!";
						continue;
					}

					// log_r($nama_lengkap);
					$this->db->select('id_bank');
					$id_bank = get_field('bank', array('bank'=>$bank, 'status'=>1))['id_bank'];
					if (empty($id_bank)) {
						$set_blm_ada[] = "Bank '".strtoupper($bank)."' belum ada di Database!";
						continue;
					}

					$password = 'Meeju123';
					$id_mitra = get_nomor('M');
					$post = array('username'=>$no_hp, 'password'=>encode($password), 'level'=>'1', 'status'=>'1', 'mode'=>'0', 'tgl_input'=>tgl_now());
		      $simpan1 = add_data('user', $post);
		      if ($simpan1) {
		        $id_new = $this->db->insert_id();
						$post_bank['id_bank'] = $id_bank;
						$post_bank['nama']    = $nama_pemilik;
						$post_bank['no_rek']  = $no_rek;
						$post_bank['id_user'] = $id_new;
						$post_bank['tgl_input']  = tgl_now();
						$simpan2 = add_data('user_bank', $post_bank);
						if ($simpan2) {
							$data[] = array(
								'id_mitra' 			=> $id_mitra,
								'type_id' 			=> 1,
								'id_user' 			=> $id_new,
								'nama_lengkap' 	=> $nama_lengkap,
								'jenis_kelamin' => $jk,
								'email' 				=> $email,
								'no_hp' 				=> $no_hp,
								'id_provinsi' 	=> $id_provinsi,
								'id_kota' 			=> $id_kota,
								'alamat' 				=> $alamat,
								'pekerjaan' 		=> $pekerjaan,
							);
						}else {
							$this->db->trans_rollback();
							$set_blm_ada[] = "Data User Bank - Baris ke $row Gagal disimpan!";
							break;
						}
					}else {
						$this->db->trans_rollback();
						$set_blm_ada[] = "Data User - Baris ke $row Gagal disimpan!";
						break;
					}

        }
      }
			$set_ls = '';
			if (!empty($set_blm_ada)) {
				$set_ls = "<hr />DATA yang tidak tersimpan: <br />";
				foreach (array_unique($set_blm_ada) as $key => $value) {
					$set_ls .= " <b style='color:red;'>$value</b>, <br />";
				}
			}
			if (!empty($data)) {
				$simpan = add_batch($tbl, $data);
				if ($simpan) {
					$this->db->trans_commit();
					$stt=1; $pesan='Data berhasil di Import '.$set_ls;
				}else {
					$this->db->trans_rollback();
					$stt=0; $pesan='Gagal Import, silahkan coba lagi! '.$set_ls;
				}
			}else {
				$stt='x'; $pesan='Data kosong jadi tidak bisa di Import! '.$set_ls;
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
    }
  }
// ========== IMPORT ==========


// referral
	public function vlist()
	{
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		if (get_session('type_id')!=1) { redirect('404'); }
		$judul = "Data ".$this->judul." II";
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

	public function list_referral($id_mitra='',$type='',$status='mitra')
	{
		$tbl = 'v_user_biodata_'.$status;
		$field_id="id_user";
		cekAjaxRequest();
		$field = '';
		foreach (list_fields($tbl) as $key => $value):
			if (in_array($value, array('id_user','nama_lengkap','jenis_kelamin','no_hp','provinsi','kota','id_mitra','status'))) {
				$field .= ", $value";
			}
		endforeach;
		$this->datatables->select("$field_id as id $field, get_user_fee($type, id_user) as fee_master");
		$this->datatables->where('id_referal', $id_mitra);
		$this->datatables->where('status', '1');
		// if (!in_array($type, array('-',0))) {
		// 	$this->datatables->where('type_id', $type);
		// }else {
		// 	$this->datatables->where_in('type_id', array(1,2));
		// }
		$this->datatables->from($tbl);
		$this->datatables->add_column('id_x','$1','encode(id)');
		echo $this->datatables->generate();
	}

	function cek_user_order()
	{
		if (isset($_POST)) {
			$id_user = khususAngka(post('id'));
			$bg='danger'; $nama='BELUM';
			$this->db->select('id_user');
			$this->db->limit(1);
			$get = get('order', array('id_user'=>$id_user));
			if ($get->num_rows()!=0) {
				$bg='success'; $nama='SUDAH';
			}
			echo json_encode(array('bg'=>$bg, 'nama'=>$nama));
			exit;
		}
	}

	public function detail_fee($tbl='', $referral='')
	{
		// if($tbl==''){ exit; }
		$tbl = 'user_mitra_fee';
		$field_id="id_child";
		$id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['query'] = get_field($tbl,array($field_id=>"$id"));
			$p = 'referral_fee';
			view("$this->view/$this->folder/detail_tab/$p", $data);
		}
	}

}
