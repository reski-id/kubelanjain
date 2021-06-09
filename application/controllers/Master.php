<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

	var $view 	= "users";
	var $re_log = "auth/login";
	var $folder = "master";
	var $judul  = "Master";

	function __construct() {
			parent::__construct();
			$this->load->library('datatables');
			$this->load->library('excel');
			$id_user = get_session('id_user');
			$level = get_session('level');
			if(!isset($id_user)) { redirect($this->re_log); }
			if($level==0 && $id_user==1){
			}else{
				if (!in_array(uri(2),array('bc_info','bc_info_group'))) {
					if (!in_array(uri(3),array('bc_info','bc_info_group'))) {
						redirect('404');
					}
				}
			}
	}

  public function index()
	{
		redirect('404');
	}

	public function approval_tipe()
	{
		$this->_data('approval_tipe');
	}

	public function bc_info_group()
	{
		$this->_data('bc_info_group');
	}

	public function bc_info()
	{
		$this->_data('bc_info');
	}

	public function bank()
	{
		$this->_data('bank');
	}

	public function informasi()
	{
		$this->_data('informasi');
	}

	public function set_fee()
	{
		$this->_data('set_fee');
	}

	public function provinsi()
	{
		$this->_data('provinsi');
	}

	public function kota()
	{
		$this->_data('kota');
	}

	public function kecamatan()
	{
		$this->_data('kecamatan');
	}

	public function redirect()
	{
		$this->_data('redirect');
	}

	public function video()
	{
		check_permission('page', 'read', 'master/video');
		$this->_data('video');
	}

	public function slide()
	{
		check_permission('page', 'read', 'master/slide');
		$this->_data('slide');
	}


	public function item_kategori()
	{
		check_permission('page', 'read', 'master/item_kategori');
		$this->_data_plu('item_kategori');
	}

	public function item_satuan()
	{
		check_permission('page', 'read', 'master/item_satuan');
		$this->_data('item_satuan');
	}

	public function item_master()
	{
		check_permission('page', 'read', 'master/item_master');
		$this->_data('item_master', 'item_master');
	}

	public function item_master_sub()
	{
		check_permission('page', 'read', 'master/item_master_sub');
		$this->_data('item_master_sub', 'item_master');
	}

	public function pasar()
	{
		check_permission('page', 'read', 'master/pasar');
		$this->_data('pasar');
	}

	public function toko()
	{
		check_permission('page', 'read', 'mastxer/toko');
		$this->_data('toko');
	}

	public function toko_harga()
	{
		check_permission('page', 'read', 'master/toko_harga');
		$this->_data('toko_harga');
	}

	public function item_lokasi()
	{
		check_permission('page', 'read', 'master/item_lokasi');
		$this->_data('item_lokasi');
	}

	public function pelanggan()
	{
		check_permission('page', 'read', 'master/pelanggan');
		$this->_data('pelanggan');
	}

	public function sales()
	{
		check_permission('page', 'read', 'master/sales');
		$this->_data('sales');
	}

	public function _data($tbl='', $folder='')
	{
		if (in_array($tbl, array('bc_info', 'bc_info_group'))) {
			check_permission('page', 'read', "users/bc_info");
		}else {
			check_permission('page', 'read', "master/$tbl");
		}
		if (!table_exists($tbl)){ redirect('404'); }
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
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
		}elseif($tbl=='kelurahan'){
			$field .= ", get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan";
		}elseif($tbl=='kota'){
			$field .= ", get_name_provinsi(id_provinsi) AS provinsi";
		}elseif($tbl=='item_master'){
			$field .= ", get_name_item_kategori(id_item_kategori) AS item_kategori";
		}elseif($tbl=='sub_item_master'){
			$field .= ", CONCAT(nama_item, ' ', nilai_satuan, ' ', get_name_item_satuan(id_item_satuan)) AS nama_item_full, get_name_item_kategori(id_item_kategori) AS item_kategori";
		}elseif($tbl=='pasar'){
			$field .= ", get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan";
		}elseif($tbl=='toko'){
			$field .= ", toko, CONCAT( get_name_pasar(id_pasar), ' - ', get_name_kota(id_kota), ' - ', get_name_kecamatan(id_kecamatan)) AS pasarnya";
		}elseif($tbl=='toko_harga'){
			$field .= ", get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan, harga";
			$this->datatables->where('id_toko', decode($id2));
		}elseif($tbl=='pelanggan'){
			$field .= ", CONCAT(get_name_jekel(id_jekel), ' ', pelanggan ) AS nama_pelanggan, get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan, nohp_satu";
		}elseif($tbl=='sales'){
			$field .= ", sales, no_hp";
		}elseif($tbl=='video'){
			$field .= ", video";
		}elseif($tbl=='slide'){
			$field .= ", slide";
		}elseif($tbl=='item_lokasi'){
			$field .= ", get_name_pasar(id_pasar) AS pasar, get_name_kota(id_kota) AS kota, get_name_kecamatan(id_kecamatan) AS kecamatan, get_name_item_kategori(id_item_kategori) AS kategori";
		}
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
  { cekAjaxRequest();
		if($tbl==''){ exit; }
    if (isset($_POST)) {
			$this->db->trans_begin();
			$id  = decode($id);
			if($tbl=='approval_tipe'){
				model('M_master','add_approval_tipe', $id);
				exit;
			}elseif($tbl=='set_fee'){
				model('M_master','add_set_fee', $id);
				exit;
			}elseif($tbl=='item_satuan'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_kategori'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_master'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_master_import'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_master_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='item_master_sub'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_master_sub_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='sub_item_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='pasar'){
				model('M_master','pasar_simpan', $id);
				exit;
			}elseif($tbl=='pasar_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='sales'){
				model('M_master','sales_simpan', $id);
				exit;
			}elseif($tbl=='sales_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='toko'){
				model('M_master','toko_simpan', $id);
				exit;
			}elseif($tbl=='toko_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='video'){
				model('M_master','video_simpan', $id);
				exit;
			}elseif($tbl=='video_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='slide'){
				model('M_master','slide_simpan', $id);
				exit;
			}elseif($tbl=='slide_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='toko_harga'){
				model('M_master','harga_simpan', $id);
				exit;
			}elseif($tbl=='pelanggan'){
				model('M_master','pelanggan_simpan', $id);
				exit;
			}elseif($tbl=='pesan'){
				log_r("ini ada di pesan");
				model('M_master','pesan_simpan', $id);
				exit;
			}elseif($tbl=='pelanggan_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='harga_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}elseif($tbl=='item_lokasi'){
				model('M_master',$tbl, $id);
				exit;
			}elseif($tbl=='item_lokasi_up_status'){
				if (isset($_POST['id'])) { model('M_master',$tbl, $id); }
				exit;
			}else {
				if (!in_array($tbl, array('kecamatan', 'kelurahan','redirect','bc_info_group','bc_info'))) {
					if($id!=''){ $this->db->where("$tbl!=", get_field($tbl, array("id_$tbl"=>$id))[$tbl]); }
					$cek_d = get($tbl, array($tbl=>post($tbl)));
					if ($cek_d->num_rows() != 0) {
						$this->db->trans_rollback();
						$pesan = strtoupper($tbl).' "'.post($tbl).'" sudah ada!';
						echo json_encode(array('stt'=>0, 'pesan'=>$pesan));
						exit;
					}
				}
				if ($tbl=='bc_info_group') {
					if($id!=''){ $this->db->where("nama_group!=", get_field($tbl, array("id_$tbl"=>$id))['nama_group']); }
					$cek_d = get($tbl, array('nama_group'=>post('nama_group')));
					if ($cek_d->num_rows() != 0) {
						$this->db->trans_rollback();
						$pesan = 'Nama Group "'.post('nama_group').'" sudah ada!';
						echo json_encode(array('stt'=>0, 'pesan'=>$pesan));
						exit;
					}
				}
				$post = post_all(array("id_$tbl","id",'simpan'));
			}
			if ($tbl=='redirect') {
				$post['tgl_update'] = tgl_now();
			}
			if ($id=='') {
				if (!check_permission('view', 'create', 'master/'.$tbl)) {
					$this->db->trans_rollback();
					echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
					exit;
				}
				if ($tbl!='redirect') {
					$post['tgl_input'] = tgl_now();
				}
				$simpan = add_data($tbl,$post);
			}else{
				if (!check_permission('view', 'update', 'master/'.$tbl)) {
					$this->db->trans_rollback();
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
// SIMPAN =============================================

	function hapus($tblnya='')
	{
		echo json_encode(array('stt'=>0, 'pesan'=>'Tidak bisa dihapus, Silahkan hubungi Admin'));
		exit;
		// if($tblnya==''){ exit; }
		// if (isset($_POST)) {
		// 	$id = decode(post('id'));
		// 	$where = array("id_$tblnya"=>$id);
		// 	if (in_array($tblnya, array('paketnya', 'paketnya_group'))) {
		// 		$hapus = update_data($tblnya, array($tblnya.'_hapus'=>'1'), $where);
		// 	}else {
		// 		if (in_array($tblnya, array('provinsi','kota','kemasan','item_satuan'))) {
		// 			$stt=0; $pesan='Maaf, '.ucwords($tblnya).' tidak boleh dihapus!';
		// 			// $gambar = get_field($tblnya, array("id_$tblnya"=>$id))['gambar'];
		// 			// if (file_exists($gambar)) { unlink($gambar); }
		// 		}else {
		// 			$hapus = delete_data($tblnya, $where);
		// 		}
		// 	}
		// 	if ($hapus) {
		// 		$stt=1; $pesan='Data berhasil dihapus';
		// 	}else {
		// 		$stt=0; $pesan='Gagal Hapus, silahkan coba lagi!';
		// 	}
		// 	echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
		// 	exit;
		// }
	}


// ========== IMPORT ==========
	public function import($tbl='', $v='')
	{
		if (!table_exists($tbl)){ redirect('404'); }
		if (isset($_POST)) {
			$id_user = get_session('id_user');
			$level 	 = get_session('level');
			$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
			$judul = "Import Data $namanya";
			$p = 'import';
			if ($v!='') { $p .= "/$v"; }
			$data = array(
				'url_import' => base_url("$this->folder/aksi_import/$tbl"),
				'tbl'				 => $tbl,
			);
			if (in_array($tbl, array('item_master','item_master_sub'))) {
				$tbl = "item_master/$tbl";
			}
			$this->load->view("$this->view/$this->folder/$tbl/$p", $data);
		}
	}

	function aksi_import($tbl='', $aksi=''){
		$id_user = get_session('id_user');
		$nama_input = "$id_user - ".user('nama_lengkap');
		$tgl_input = tgl_now();
		if (!table_exists($tbl)){ redirect('404'); } $nm_aksi='';
		if(isset($_FILES["file"]["name"])){
      $path = $_FILES["file"]["tmp_name"];
      $object = PHPExcel_IOFactory::load($path);
			$data=array(); $set_blm_ada=array(); $i=0;
      foreach($object->getWorksheetIterator() as $worksheet){
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        for($row=2; $row<=$highestRow; $row++){
					 if ($tbl=='provinsi') { $nm_aksi='Provinsi';
						 	$provinsi = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							if ($provinsi!='') {
								$id_provinsi = get_field('provinsi', array('provinsi'=>$provinsi))['id_provinsi'];
								if (empty($id_provinsi)) {
									$data[] = array( 'provinsi'=>$provinsi, 'tgl_input'=>tgl_now() );
								}else {
									$set_blm_ada[] = strtoupper($provinsi);
								}
							}
					 }elseif ($tbl=='kota') { $nm_aksi='Provinsi & Kota';
							 $provinsi = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
							 $id_provinsi = get_field('provinsi', array('provinsi'=>$provinsi))['id_provinsi'];
							 if (!empty($id_provinsi)) {
								 $kota = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
								 $id_kota = get_field('kota', array('id_provinsi'=>$id_provinsi, 'kota'=>$kota))['id_kota'];
								 	if (empty($id_kota)) {
										$data[] = array( 'id_provinsi'=>$id_provinsi, 'kota'=>$kota, 'tgl_input'=>tgl_now() );
									}
							 }else {
							 	 $set_blm_ada[] = strtoupper($provinsi);
							 }
					 }elseif ($tbl=='kecamatan') { $nm_aksi='Kota';
						 $kota = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 $id_kota = get_field('kota', array('kota'=>$kota))['id_kota'];
						 if (!empty($id_kota)) {
							  $kecamatan = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
							  $data[] = array( 'id_kota'=>$id_kota, 'kecamatan'=>$kecamatan, 'tgl_input'=>tgl_now() );
						 }else {
						 		$set_blm_ada[] = strtoupper($kota);
						 }
           }elseif ($tbl=='kelurahan') { $nm_aksi='Kota & Kecamatan';
						 $kota = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 $id_kota = get_field('kota', array('kota'=>$kota))['id_kota'];
						 $kecamatan = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						 $id_kecamatan = get_field('kecamatan', array('id_kota'=>$id_kota, 'kecamatan'=>$kecamatan))['id_kecamatan'];
						 if (!empty($id_kota) || !empty($id_kecamatan)) {
							 $kelurahan = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
							 $data[] = array( 'id_kota'=>$id_kota, 'id_kecamatan'=>$id_kecamatan, 'kelurahan'=>$kelurahan, 'tgl_input'=>tgl_now() );
						 }else {
						 		$set_blm_ada[] = 'KOTA: '.strtoupper($kota).' - KEC: '.strtoupper($kecamatan);
						 }
           }elseif ($tbl=='bc_info') { $nm_aksi='BC Information';
						 $group = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 if ($group=='') { continue; }
						 $nama  = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						 $no_hp = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						 $this->db->select('id_bc_info_group');
						 $id_group = get_field('bc_info_group', array('nama_group'=>$group))['id_bc_info_group'];
						 	if (empty($id_group)) {
								 $set_blm_ada[] = "Nama Group '<b>$group</b>' tidak ditemukan!";
						 	}else{
								 $this->db->select('no_hp');
								 $bc_info = get_field($tbl, array('id_bc_info_group'=>$id_group, 'no_hp'=>$no_hp))['no_hp'];
		 						 if (empty($bc_info)) {
		 							 $data[] = array( 'id_bc_info_group'=>$id_group, 'no_hp'=>$no_hp, 'nama'=>$nama, 'tgl_input'=>tgl_now() );
		 						 }else {
		 						 	 $set_blm_ada[] = "Nama Group '<b>$group</b>' & No HP '<b>$no_hp</b>' sudah Ada!";
		 						 }
							}
           }elseif ($tbl=='item_master') {
						 $nm_aksi='ITEM MASTER';
						 $warna=''; $ket=''; $plu='-';
						 $id_item_kategori  = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 $this->db->select('kode, nama');
						 $get_kat = get_field('item_kategori', array('id_item_kategori'=>$id_item_kategori));
						 if (empty($get_kat)) {
							 $warna='pink'; $ket.="<br /><label>ID KATEGORI '$id_item_kategori' tidak ditemukan!</label>";
						 }else {
							 del_nomor('ket', "item master import $id_user");
							 $plu = get_nomor($get_kat['kode'], "item master import $id_user");
						 }
						 $nama_item = $worksheet->getCellByColumnAndRow(1, $row)->getValue();

						 $this->db->select('plu');
				     $cek_data = get('item_master', array('id_item_kategori'=>$id_item_kategori, 'nama_item'=>$nama_item))->row();
				     if (!empty($cek_data)) { $ket .= "<br /><label>ID Kategori : $id_item_kategori & Nama Item : $nama_item</label>"; }
				      if ($ket=='') {
								$data[$i] = array('id_item_kategori'=>$id_item_kategori, 'plu'=>$plu, 'nama_item'=>$nama_item, 'status'=>1, 'tgl_input'=>$tgl_input, 'input_by'=>$nama_input);
							}else {
								$set_blm_ada[] = $ket;
							}
						$i++;
					}elseif ($tbl=='item_master_sub') {
						 $nm_aksi='SUB ITEM MASTER';
						 $warna=''; $ket=''; $plu='-';
						 $plu  	= $worksheet->getCellByColumnAndRow(0, $row)->getValue();
						 $isian = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
						 $kode  = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
						 $plu_sub = $plu.$kode.$isian;

						 $this->db->select('plu_sub');
				     $cek_data = get_field($tbl, array('plu_sub'=>$plu_sub));
				     if (!empty($cek_data)){
							 $warna='pink'; $ket.="<br /><label>PLU : $plu, Isian : $isian & Kode Satuan : $kode</label>";
						 }
						 if ($ket=='') {
							 // item master
					     $this->db->select("A.id_item_master, A.id_item_kategori, A.plu, A.nama_item");
					     $get_item = get_field('item_master AS A', array('A.status'=>1, 'plu'=>$plu));
					     if (empty($get_item)){
								 $warna='pink'; $ket.="<br /><label>Item tidak ditemukan!</label>";
								 $set_blm_ada[] = $ket;
							 }else {
							 	 $id_item_master   = $get_item['id_item_master'];
								 $id_item_kategori = $get_item['id_item_kategori'];
								 $plu = $get_item['plu'];

								 // item satuan
						     $this->db->select('id_item_satuan, kode, item_satuan');
						     $get_satuan = get_field('item_satuan', array('kode'=>$kode));
								 if (empty($get_satuan)){
									 $warna='pink'; $ket.="<br /><label>Satuan tidak ditemukan!</label>";
									 $set_blm_ada[] = $ket;
								 }else {
								 	 $nama_item = $get_item['nama_item'] . ' ' . $isian . ' ' .$get_satuan['item_satuan'];
									 $data[$i] = array('id_item_master'=>$id_item_master, 'id_item_kategori'=>$id_item_kategori, 'item_kategori'=>get_name_item_kategori($id_item_kategori), 'plu'=>$plu, 'nama_item'=>$nama_item, 'plu_sub'=>$plu_sub, 'nilai_satuan'=>$isian, 'id_item_satuan'=>$get_satuan['id_item_satuan'], 'kode_satuan'=>$kode, 'status'=>1, 'tgl_input'=>$tgl_input, 'input_by'=>$nama_input);
								 }
							 }
						 }else {
							 $set_blm_ada[] = $ket;
						 }
						$i++;
					 }
        }
      }

			if ($tbl=='item_master' && $aksi=='view') {
				// echo '{"detailnya":' . json_encode($data).'}';
				$stt=0; $pesan='';
				if (!empty($data)) {
					$simpan = add_batch('item_master', $data);
					if ($simpan) {
						$stt=1;
						if (!empty($set_blm_ada)) {
							$set_ls = "<hr />Tidak tersimpan: <br />";
							foreach (array_unique($set_blm_ada) as $key => $value) {
								if ($value!='') {
									$set_ls .= " <b style='color:red;'>$value</b>, ";
								}
							}
							$pesan .= substr($set_ls,0,-2);
						}
					}else{
						$pesan='Gagal Simpan!';
					}
				}else {
					$pesan='Data Kosong atau Data Sudah ada di Database!';
				}
				echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
				exit;
			}else {
				$set_ls=''; $pesan='';
				if (!empty($set_blm_ada)) {
					$set_ls = "<hr />Nama $nm_aksi yang tidak tersimpan: <br />";
					foreach (array_unique($set_blm_ada) as $key => $value) {
						if ($value!='') {
							$set_ls .= " <b style='color:red;'>$value</b>, ";
						}
					}
					$set_ls = substr($set_ls,0,-2);
				}
				if (!empty($data)) {
					$simpan = add_batch($tbl,$data);
					if ($simpan) {
						$stt=1; $pesan='Data berhasil di Import';
					}else {
						$stt=0; $pesan='Gagal Import, silahkan coba lagi!';
					}
				}else {
					$stt='x'; $pesan='Data tidak valid, jadi tidak bisa di Import!';
				}
				$pesan .= $set_ls;
				echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
				exit;
			}
    }
  }
// ========== IMPORT ==========



// DATA MASTER PLU ======
	public function _data_plu($tbl='', $judul='')
	{
		check_permission('page', 'read', "master/$tbl");
		if (!in_array($tbl, array('item_kategori'))) {
			exit;
		}
		if (!table_exists($tbl)){ redirect('404'); }
		$id_user = get_session('id_user');
		$level 	 = get_session('level');
		if ($judul=='') {
			$namanya = ucwords(preg_replace('/[_]/',' ',$tbl));
			$judul = "Data $namanya";
		}
		$p='tabel';
		$head_tambah = '';
		$data = array(
			'judul_web' => "$judul",
			'content'		=> "$this->view/$this->folder/$tbl/$p",
			'url'				=> "$this->folder",
			'url_modal'	=> base_url("$this->folder/view_data_plu/$tbl"),
			'head_tambah' => $head_tambah,
			'tbl'				=> $tbl,
			'col'				=> '12'
		);
		$this->load->view("$this->view/index", $data);
	}

	public function list_data_plu($tbl='', $stt='')
	{
		if($tbl==''){ exit; }
		$field_id="A.id_$tbl";
		cekAjaxRequest();
		$field = '';
		if ($tbl=='item_kategori') {
			foreach (list_fields($tbl) as $key => $value):
				$field .= ", A.$value";
			endforeach;
		}
		$this->datatables->select("$field_id as id $field");
		$this->datatables->from("$tbl A");
		$this->datatables->where('A.status', $stt);
		$this->datatables->add_column('id_x','$1','encode(id)');
    echo $this->datatables->generate();
	}

	public function view_data_plu($tbl='')
  {
		if($tbl==''){ exit; }
		if (!in_array($tbl, array('item_kategori'))) {
			exit;
		}
		$field_id="id_$tbl"; $id='';
		if (isset($_POST)) {
			$id  = decode(post("id"));
			if($id==''){ $stt=''; }else{ $stt=1; }
			$data['tbl'] 		= $tbl;
			$data['stt']		= $stt;
			$data['id'] 		= $id;
			$data['urlnya'] = base_url("$this->folder/simpan/$tbl");
			$tblnya=$tbl;
			$data['query'] = get_field($tblnya,array($field_id=>"$id"));
			if (post("input")==1) {
				$p = 'form';
			}else {
				$p = 'detail';
			}
			view("$this->view/$this->folder/$tbl/$p", $data);
    }
  }

	function up_status_plu($tbl='', $stt='')
	{
		if ($tbl==''){ exit; }
		if (!in_array($tbl, array('item_kategori'))) {
			exit;
		}
		if (!check_permission('view', 'read', "master/$tbl")) {
			echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
			exit;
		}
		if (isset($_POST)) {
			if (!in_array($stt, array(0,1))) {
				echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!!'));
				exit;
			}
			$id = decode(post('id'));
			$where = array("id_$tbl"=>$id);
			$up = update_data($tbl, array('status'=>$stt, 'update_date'=>tgl_now(), 'update_by'=>get_session('id_user')), $where);
			if ($up) {
				$stt=1; $pesan='';
			}else {
				$stt=0; $pesan='Gagal, silahkan coba lagi!';
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
		}
	}

	function ajax_get_kode($tbl='')
	{
		$stt=0; $pesan='Failed!';
		if (!check_permission('view', 'read', "master/$tbl")) {
			echo json_encode(array('stt'=>0, 'pesan'=>'Permission Denied!'));
			exit;
		}
		$id_user = get_session('id_user');
		$this->db->select('kode');
		$kode = get_field('item_kategori', array('id_item_kategori'=>post('id')))['kode'];
		del_nomor('ket', "item master $id_user");
    $get = get_nomor($kode, "item master $id_user");
		if (!empty($get)) { $stt=1; $pesan=$get; }
		echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
		exit;
	}

	function ajax_item_lokasi()
	{
		cekAjaxRequest();
		model('M_master','get_item_lokasi');
	}

	function ajax_cek_item_lokasi()
	{
		cekAjaxRequest();
		model('M_master','cek_item_lokasi');
	}

}
