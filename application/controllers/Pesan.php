<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pesan extends CI_Controller {

	var $view 	= "users";
	var $folder = "pesan";
	var $judul  = "pesan";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			
	}

  public function index()
	{
		redirect('404');
	}

	

	public function _data($tbl='', $folder='')
	{
		
		if (!table_exists($tbl)){ redirect('404'); }
		$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
		$judul = $this->judul." $namanya";
		if($tbl=='set_fee'){
			$p='index';
			$contentnya = "$this->view/index_form";
			$viewnya 		= "$this->view/$this->folder/$tbl/$p";
		}else{
			$p = ($folder=='') ? "$tbl/tabel" : "$folder/$tbl/tabel";
			$contentnya = "$this->view/$this->folder/$p";
			$viewnya    = '';
		}
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

	public function list_data($tbl='', $id='', $id2='', $id3='')
	{
		if($tbl==''){ exit; }
		$field_id="id_$tbl";
		cekAjaxRequest();
		$field = '';
		if(in_array($tbl, array('bc_info'))){ $tbl="v_$tbl"; }
		if(in_array($tbl, array('kecamatan'))){
			$field .= ", get_name_provinsi(id_provinsi) AS provinsi, get_name_kota(id_kota) AS kota";
		}elseif($tbl=='item_lokasi'){
			$field .= ", get_name_pasar(id_pasar) AS pasar, get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan, get_name_item_kategori(id_item_kategori) AS kategori";
		}
		foreach (list_fields($tbl) as $key => $value):
			$field .= ", $value";
		endforeach;
		$this->datatables->select("$field_id as id $field");
		$this->datatables->from($tbl);
		
		if (in_array($tbl, array('bank', 'provinsi', 'informasi', 'redirect', 'item_satuan', 'item_master', 'item_master_sub', 'pasar', 'item_lokasi', 'toko', 'toko_harga', 'pelanggan', 'sales','video','slide'))) {
			$this->datatables->where('status', $id);
		}
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data($tbl='', $id_kota='')
  {
		if($tbl==''){ exit; }
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['id_kota'] = $id_kota;
			$data['urlnya'] = base_url("$this->folder/simpan/$tbl");
			$tblnya=$tbl;
			$data['query'] = get_field($tblnya,array($field_id=>"$id"));
			// log_r($this->db->last_query());
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			if (in_array($tbl, array('item_master','item_master_sub'))) {
				$tbl = "item_master/$tbl";
			}
			view("$this->view/$this->folder/$tbl/$p", $data);
    }
  }

// SIMPAN =============================================
  function simpan($tbl='',$id='', $id_kota='')
  { 
		if($tbl==''){ exit; }
    if (isset($_POST)) {
			$this->db->trans_begin();
			$id  = decode($id);
			if($tbl=='pesan'){
				// log_r('controller pesan');
				model('M_master','pesan_simpan', $id);
				exit;
			}else {
				
				$post = post_all(array("id_$tbl","id",'simpan'));
			}
			if ($tbl=='redirect') {
				$post['tgl_update'] = tgl_now();
			}
			if ($id=='') {
				if ($tbl!='redirect') {
					$post['tgl_input'] = tgl_now();
				}
				$simpan = add_data($tbl,$post);
			}else{
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
// SIMPAN =============================================

	function hapus($tblnya='')
	{
		echo json_encode(array('stt'=>0, 'pesan'=>'Tidak bisa dihapus, Silahkan hubungi Admin'));
		exit;
		
	}
}