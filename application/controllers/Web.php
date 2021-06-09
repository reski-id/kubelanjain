<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends CI_Controller {

	var $view = "web";
	var $view_page = "page_mode";

	public function index()
	{
		$this->p();
	}

	public function ok($dari='KILOGRAM', $ke='ONS', $nilai=1)
	{
		$dari  = strtoupper((in_array(strtoupper($dari), array('KG'))) ? 'KILOGRAM' : $dari);
		$ke    = strtoupper((in_array(strtoupper($ke), array('KG'))) ? 'KILOGRAM' : $ke);
		$hasil = konversi_satuan(1, $dari, $ke, $nilai, 'item_satuan');
		echo "$nilai $dari == $hasil $ke";
	}

	function repot_price_list()
	{
		view('web/sub/beranda/pdf');
	}

	// function oks()
	// {
	// 	// $get = export_pdf_harga_pasar('', 'data');
  //   // $file_path = $get['file_path'];
  //   // $filename  = $get['filename'];
	// 	// echo "$file_path - $filename";
	// 	echo export_pdf_harga_pasar();
	// }
	// public function ok()
	// {
	// 	$data['id_order'] = 5;
	// 	$file_path = 'assets/file/order/'.$data['id_order'].'.pdf';
	// 	$nama_file = "BKYB - ORDER #12345";
	// 	$caption   = "Biar Kami Yang Belanja\n\nTerimakasih BAPAK ZUWITO telah mempercayakan belanja pada kami, Semoga hari anda menyenangkan.🙏🏿 😃";
	//
	// 	view('users/order/laporan/detail_order', $data);
	// 	$html = $this->output->get_output();
	//   // Load pdf library
	// 	$this->load->library('pdf');
	// 	$this->pdf->loadHtml($html);
	// 	$this->pdf->setPaper('A4', 'portrait');
	// 	$this->pdf->render();
	// 	// Output the generated PDF (1 = download and 0 = preview)
	// 	// $this->pdf->stream("html_contents.pdf", array("Attachment"=> 0));
  //   $output = $this->pdf->output();
	// 	file_put_contents($file_path, $output);
	// 	// $id_tele = '-478379103';
	// 	// SendDocument_tele($id_tele, $caption, FCPATH.$file_path, $nama_file);
	// 	$this->load->helper('api_sms');
	// 	$kirim = Send_file('wa', 'POST', '081277855601', web('website').'/'.$file_path, $nama_file, $caption);
	// 	if (json_decode("[$kirim]")[0]->sent) {
	// 		Send_message('wa', 'POST', '081277855601', $caption);
	// 	}
	// 	// echo "$send";
	// }

	// P & DETAIl
	// =======================================================
	public function p($offset=0)
	{
		$limit = (view_mobile()) ? '1' : '4';
		$tbl = 'video';
		$this->db->select('video');
		// $this->db->limit('4');
		$jml_baris = get($tbl)->num_rows();
		$config['base_url'] = base_url()."web/p";
		$config['total_rows']  = $jml_baris;
		$config['per_page']    = $limit; /*Jumlah data yang dipanggil perhalaman*/
		$config['uri_segment'] = 3; /*data selanjutnya di parse diurisegmen 3*/
		/*Class pagination yang digunakan*/
		$config['first_link']       = '<i class="bx bx-chevrons-left"></i>';
		$config['last_link']        = '<i class="bx bx-chevrons-right"></i>';
		$config['next_link']        = '<i class="bx bx-chevron-right"></i>';
		$config['prev_link']        = '<i class="bx bx-chevron-left"></i>';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav aria-label="..."><ul class="pagination pagination-success justify-content-center">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		$this->pagination->initialize($config);

			$this->db->select('judul, video')->where('status',1);
			$this->db->order_by("id_$tbl",'DESC');
			// $this->db->limit('4');
			$query = $this->db->get($tbl,$config['per_page'], $offset);

			$data = array(
				'judul_web' => 'Biar Kami Yang Belanja',
				'content' 	=> "web/page/beranda/index",
				// 'content' 	=> "web/sub/beranda/index",
				'halaman'		=> $this->pagination->create_links(),
				'offset'		=> $offset,
				'query'			=> $query,
				'footer'    => true
			);
			$this->load->view("web/index", $data);
	}


	public function cek()
	{
		$offset=0;
		if (!empty($_GET['per_page'])) {
			$offset = $_GET['per_page'];
		}
		$limit = (view_mobile()) ? '5' : '20';
		$tbl = 'update_harga'; $pkat = '';
		$this->db->select('id_update_harga');
		$this->db->where('harga >', 0);
		// $this->db->like('nama_item_sub', 'KILOGRAM');
		if (!empty($_GET['kat'])) {
			$this->db->where('id_item_kategori', decode($_GET['kat']));
			$pkat .= 'kat='.$_GET['kat'];
		}
		if (!empty($_GET['p'])) {
			$this->db->like('nama_item_sub', $_GET['p']);
			if ($pkat!='') { $pkat .= '&'; }
			$pkat .= 'p='.$_GET['p'];
		}
		$this->db->like('nama_item_sub', 'GRAM');
		$jml_baris = get($tbl)->num_rows();

		$config['base_url']         = base_url()."web/cek?$pkat";
		$config['total_rows']       = $jml_baris;
		$config['per_page']         = $limit;
		$config['uri_segment']      = 3;
  	    $config['page_query_string'] = TRUE;
		$config['first_link']       = '<i class="bx bx-chevrons-left"></i>';
		$config['last_link']        = '<i class="bx bx-chevrons-right"></i>';
		$config['next_link']        = '<i class="bx bx-chevron-right"></i>';
		$config['prev_link']        = '<i class="bx bx-chevron-left"></i>';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav aria-label="..."><ul class="pagination pagination-success justify-content-center">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		$this->pagination->initialize($config);

			$this->db->select('nama_item_sub as nama_item, harga, plu_sub');
			$this->db->where('harga >', 0);
			// $this->db->like('nama_item_sub', 'KILOGRAM');
			if (!empty($_GET['kat'])) {
				$this->db->where('id_item_kategori', decode($_GET['kat']));
			}
			if (!empty($_GET['p'])) {
				$this->db->like('nama_item_sub', $_GET['p']);
			}
			$this->db->like('nama_item_sub', 'GRAM');
			$this->db->order_by("id_item_kategori",'ASC');
			$this->db->order_by("nama_item_sub",'ASC');
			// $this->db->limit('4');
			$query = $this->db->get($tbl, $config['per_page'], $offset);
			// log_r($this->db->last_query());
			$data = array(
				'judul_web' => 'Biar Kami Yang Belanja',
				'content' 	=> "web/sub/beranda/index",
				'halaman'		=> $this->pagination->create_links(),
				'offset'		=> $offset,
				'query'			=> $query
			);
			$this->load->view("web/index", $data);
	}

	// function cek_pdf()
	// {
	// 	view('web/sub/beranda/pdf');
	// 	$html = $this->output->get_output();
	// 	// Load pdf library
	// 	$this->load->library('pdf');
	// 	$this->pdf->loadHtml($html, 'UTF-8');
	// 	$this->pdf->setPaper('A4', 'portrait');
	// 	// $this->pdf->set_option('defaultMediaType', 'all');
	// 	// $this->pdf->set_option('isFontSubsettingEnabled', true);
	// 	// $this->pdf->set_option('defaultFont', 'cp');
	// 	$this->pdf->render();
	// 	// Output the generated PDF (1 = download and 0 = preview)
	// 	$filename = "Katalog Produk BKYB ".date('d-m-Y').".pdf";
	// 	$this->pdf->stream($filename, array("Attachment"=> 0));
	// 	$output = $this->pdf->output();
	// 	// file_put_contents($file_path, $output);
	// }

	public function cek_pdf($stt='')
	{
		$file = 'assets/file/harga_pasar/'.date('d-m-Y').'.pdf';
		if (!file_exists($file)) {
			export_pdf_harga_pasar();
		}
		force_download($file, null);
	}

	public function scan_WA()
	{
		$arrContextOptions=array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);
		$port = 8001;
		$url  = 'https://wa-meeju.villacorp.id';
		ini_get('allow_url_fopen');
		$response = file_get_contents("$url:$port", false, stream_context_create($arrContextOptions));
		echo $response;
	}

	// function cek_send()
	// {
	// 	// $where = array('id_order'=>10);
	// 	$where = array('id_order'=>178);
	// 	$this->load->helper("api_sms");
	// 	$this->db->select('id_user');
	// 	$id_user = get_field('order', $where)['id_user'];
	// 	$tbl = 'user_fee';
	// 	$this->db->select('id_master, fee_master');
	// 	$send_mitra1 = get_field($tbl, array('id_child'=>$id_user));
	// 	if (!empty($send_mitra1)) {
	// 		$kirim=array(); $no_hp_mitra1=''; $fee_1='';
	// 		$get_fee_1 = $send_mitra1['fee_master'];
	// 		if ($get_fee_1!=0) {
	// 			$no_hp_mitra1 = get_no_hp_user($send_mitra1['id_master']);
	// 			$fee_1 = format_angka($get_fee_1, 'rp');
	// 			$kirim[] = array('no_hp'=>$no_hp_mitra1, 'fee'=>$fee_1);
	// 		}
	// 		$this->db->select('id_master, fee_child, fee_master');
	// 		$send_mitra2 = get_field($tbl, array('id_child'=>$send_mitra1['id_master']));
	// 		if (!empty($send_mitra2)) {
	// 			$get_fee_2 = $send_mitra2['fee_master'];
	// 			if ($get_fee_2!=0) {
	// 				$kirim=array();
	// 				$no_hp_mitra2 = get_no_hp_user($send_mitra2['id_master']);
	// 				if ($no_hp_mitra1!='') {
	// 					$fee_1 = format_angka($send_mitra2['fee_child'], 'rp');
	// 					$kirim[] = array('no_hp'=>$no_hp_mitra1, 'fee'=>$fee_1);
	// 				}
	// 				$fee_2 = format_angka($get_fee_2, 'rp');
	// 				$kirim[] = array('no_hp'=>$no_hp_mitra2, 'fee'=>$fee_2);
	// 			}
	// 		}
	//
	// 		if (!empty($kirim)) {
	// 			foreach ($kirim as $key => $value) {
	// 				$no_hp = $value['no_hp'];
	// 				$message = "INFO MEEJU<br />Selamat Anda mendapatkan Fee ".$value['fee'];
	// 				echo "Send $no_hp <br />$message<hr />";
	// 			}
	// 		}
	//
	// 	}
	// }

	// public function get_user_total()
	// {
	// 	$this->db->trans_begin();
	// 	$this->db->select('id_user');
	// 	foreach (get('user_biodata_mitra')->result() as $key => $value) {
	// 		$id_user = $value->id_user;
	// 		$simpan = save_benefit($id_user);
	// 		if (!$simpan) { break; }
	// 	}
	// 	if ($simpan) {
	// 		$this->db->trans_commit();
	// 		echo "<b style='color:green'>Success!</b>";
	// 	}else{
	// 		$this->db->trans_rollback();
	// 		echo "<b style='color:red'>Gagal!</b>";
	// 	}
	// }
	//
	// public function get_fee()
	// {
	// 	$data = array();
	// 	$this->db->trans_begin();
	// 	$this->db->where("type_id!=1 AND type_id!='-'");
	// 	$this->db->where("id_referal!=''");
	// 	$get_Data = get('user_biodata');
	// 	foreach ($get_Data->result() as $key => $value) {
	// 		$no_master = $value->id_referal; //tipe_in
	// 		$no_child  = $value->id_mitra; //tipe_out
	// 		$data = get_data_fee_arr($no_master, $no_child);
	// 		$simpan = add_data('user_fee', $data);
	// 		if (!$simpan) { break; }
	// 	}
	// 	if ($simpan) {
	// 		$this->db->trans_commit();
	// 		echo "<b style='color:green'>Success!</b>";
	// 	}else{
	// 		$this->db->trans_rollback();
	// 		echo "<b style='color:red'>Gagal!</b>";
	// 	}
	// 	// log_r($data);
	// }

	public function app_meeju($stt='')
	{
		if ($stt=='download') {
			$this->db->select('path, app');
			$get_app = get_field('set_app', array('nama'=>'Meeju APP'));
			$app = $get_app['path'].'/'.$get_app['app'];
			force_download($app, null);
		} else {
			$judul_web = "Donwload Aplikasi Android Meeju!";
			$this->_page('app_meeju', $judul_web);
		}
	}

	// public function sms()
	// {
	// 	$this->load->helper("api_sms");
	// 	$no_hp = '081277855601';
	// 	$pesan = 'Test SMS nya';
	// 	$get = Send_SMS('POST', $no_hp, $pesan);
	// 	log_r($get);
	// }
	//
	// public function wa()
	// {
	// 	$this->load->helper("api_sms");
	// 	$no_hp = '089604364883';
	// 	$pesan = 'Hallo';
	// 	$get = Send_WA_message('POST', $no_hp, $pesan);
	// 	log_r($get);
	// }

	// public function get($jp='', $stt='')
	// {
	// 	$this->load->helper("api_pengiriman");
	// 	$get='404'; $data='';
	// 	if ($jp=='sicepat') {
	// 		$data = "origin=TKG&destination=SUB10000&weight=10";
	// 		$get = DATA_Pengiriman($jp,'GET',$stt,'live', $data);
	// 		// $get = API_response($jp,'GET','origin','live', $data);
	// 		// $get = API_response($jp,'GET','destination','live', $data);
	// 	}elseif ($jp=='jne') {
	// 		// for ($i='A'; $i <='Z'; $i++) {
	// 		// 	$data = "s=tujuan&term=$i";
	// 		// 	$get = DATA_Pengiriman($jp,'GET',$stt,'', $data);
	// 		// 	foreach ($get as $key => $value) {
	// 		// 		if (!empty($value['id'])) {
	// 		// 			$post = array('id'=>$value['id'], 'value'=>$value['value']);
	// 		// 			if (get('jne_tujuan', $post)->num_rows()==0) {
	// 		// 				$simpan = add_data('jne_tujuan', $post);
	// 		// 				if ($simpan) {
	// 		// 					echo "<label style='color:green'>Sukses ".$value['value']."</label><br />";
	// 		// 				}else {
	// 		// 					echo "<label style='color:green'>Gagal ".$value['value']."</label><br />";
	// 		// 				}
	// 		// 			}
	// 		// 		}
	// 		// 	}
	// 		// }
	// 		$data = ['panel_type'=>'info', 'exp_name'=>'jne', 'exp_title'=>'JNE', 'kotaAsaljne'=>'BANDARLAMPUNG', 'kotaAsaljne_val'=>'VEtHMTAwMDBK', 'kotaTujuanjne'=>'BATAM', 'kotaTujuanjne_val'=>'QlRIMTAwMDBK', 'beratKgjne'=>'1', 'cacheDisabledjne'=>'10', 'captchajne'=>'492'];
	// 		$get = DATA_Pengiriman($jp,'POST',$stt,'', $data);
	// 	}
	// 	// log_r($get);
	// }

	// public function up_paket()
	// {
	// 	$new_paket = array(0, 7,8,9,10,11,12);
	// 	$this->db->select('id_order, no_transaksi, id_paket, jumlah, kode_unik_harga');
	// 	$this->db->where('pembayaran_persen', 0);
	// 	$this->db->where('tgl_input<=', '2020-08-31 23.00.00');
	// 	$get_order = get('order');
	// 	foreach ($get_order->result() as $key => $value) {
	// 		$id_order = $value->id_order;
	// 		$no_transaksi = $value->no_transaksi;
	// 		$id_paket = $new_paket[$value->id_paket];
	// 		$jumlah   = $value->jumlah;
	// 		$KU_harga = $value->kode_unik_harga;
	// 		$this->db->select('id_paketnya, paket, jenis, qty, pcs, unit, free_qty, harga, paketnya');
  //     $paketnya = get_field('paketnya', array('id_paketnya'=>$id_paket));
  //     if (empty($paketnya)) {
  //       echo '<label style="color:red">Paket '.$no_transaksi.' tidak valid!</label><br />';
  //     }else {
	// 			$dt_order['id_paket'] = $paketnya['id_paketnya'];
  //       $dt_order['paket']    = $paketnya['paket'];
  //       $dt_order['jenis']    = $paketnya['jenis'];
  //       $dt_order['qty']      = $paketnya['qty'];
  //       $dt_order['pcs']      = $paketnya['pcs'];
  //       $dt_order['jenis_satuan'] = $paketnya['unit'];
  //       $dt_order['free_qty'] = $paketnya['free_qty']*$jumlah;
  //       $dt_order['harga']    = $paketnya['harga'];
  //       $dt_order['jumlah']   = $jumlah;
  //       $dt_order['kode_unik_harga'] = $KU_harga;
  //       $dt_order['total_harga'] = ($paketnya['harga']*$jumlah)+$KU_harga;
  //       $dt_order['catatan']  = $paketnya['paketnya'];
  //         $dt_order['tgl_update'] = tgl_now();
  //         $simpan = update_data('order', $dt_order, array('id_order'=>$id_order));
  //         $id_order = $get_ORDER->id_order;
	// 	    echo '<label style="color:green">Success '.$no_transaksi.'!</label><br />';
  //     }
	// 	}
	// }

	// public function mitra_fee()
	// {
	// 	$this->db->trans_begin();
	// 	$this->db->select('id_referal, id_mitra, id_user');
	// 	$this->db->where_in('type_id', array(2,3));
	// 	$this->db->where("id_referal is not null AND id_referal!=''");
	// 	foreach (get('user_biodata')->result() as $key => $value) {
	// 		$no_master = $value->id_referal;
	// 		$no_child = $value->id_mitra;
	// 		$id_master = find_nomor_get_id_user($no_master);
	// 		$id_child  = find_nomor_get_id_user($no_child);
	// 		$id_user = $value->id_user;
	// 		$this->db->select('level, tgl_input');
	// 		$get_user = get_field('user', array('id_user'=>$id_user));
	// 		if (empty($get_user)) {
	// 			$stt=0; $pesan='Gagal dapatkan <b>data user</b>!';
	// 			break;
	// 		}
	// 		$level = $get_user['level'];
	// 		$tgl_input = $get_user['tgl_input'];
	// 		if (get('user_referal', array('no_master'=>$no_master, 'no_child'=>$no_child))->num_rows() == 0) {
	// 			$simpan3 = add_data('user_referal', array('id_master'=>$id_master, 'id_child'=>$id_child, 'no_master'=>$no_master, 'no_child'=>$no_child, 'tgl_input'=>$tgl_input));
	// 			if (!$simpan3) {
	// 				$stt=0; $pesan='Gagal input <b>user_referal</b>!'; break; //Gagal simpan referal
	// 			}else {
	// 				$stt=1;
	// 				if ($level==1) {
	// 					$fee_master = get_mitra_fee('mitra1');
	// 					$fee_child  = get_mitra_fee('mitra2');
	// 					$simpan4 = add_data('user_mitra_fee', array('id_master'=>$id_master, 'id_child'=>$id_child, 'no_master'=>$no_master, 'no_child'=>$no_child, 'fee_master'=>$fee_master, 'fee_child'=>$fee_child, 'tgl_input'=>$tgl_input));
	// 					if (!$simpan4) { $stt=0; $pesan='Gagal input <b>user_mitra_fee</b>!'; break; }
	// 				}
	// 			}
	// 		}
	// 	}
	//
	// 	if ($stt==1) {
  //     $this->db->trans_commit();
  //     $pesan = "Sukses!";
  //   }else {
  //     $this->db->trans_rollback();
	// 		if($pesan==''){ $pesan = "Gagal!"; }
  //   }
	// 	echo $pesan;
	// }

	// public function up_order($no_transaksi='', $id_paket='')
	// {
	// 	$i=1;
	// 	$this->db->select('id_paket, id_user, id_order, jumlah');
	// 	if ($no_transaksi!='') {
	// 		$this->db->where('no_transaksi', $no_transaksi);
	// 	}
	// 	// $this->db->where('paket is null');
	// 	foreach (get('order')->result() as $key => $value) {
	// 		echo $i++.'. ';
	// 		$old = $value->id_order;
	// 		if ($id_paket=='') {
	// 			$id_paket = $value->id_paket;
	// 		}
	// 		$status = $value->status;
	// 		if (in_array($status, array('', null))) {
	// 			$status=0;
	// 		}
	// 		$this->db->select('id_paketnya, paket, qty, free_qty, harga,paketnya');
	// 		$paketnya = get_field('paketnya', array('id_paketnya'=>$id_paket));
	// 		if (empty($paketnya)) {
	// 			echo "<b style='color:orange'>Paket tidak valid!</b>";
	// 		}else {
	// 			$dt_order['id_paket'] = $paketnya['id_paketnya'];
	// 			$dt_order['paket']    = $paketnya['paket'];
	// 			$dt_order['qty']      = $paketnya['qty'];
	// 			$dt_order['free_qty'] = $paketnya['free_qty'];
	// 			$dt_order['harga']    = $paketnya['harga'];
	// 			$dt_order['total_harga'] = $paketnya['harga']*$value->jumlah;
	// 			$dt_order['catatan']  = $paketnya['paketnya'];
	// 			$simpan_ORDER = update_data('order', $dt_order, array('id_user'=>$value->id_user, 'id_order'=>$old));
	// 			if ($simpan_ORDER) {
	// 				echo "<b style='color:green'>Success</b>";
	// 			}else {
	// 				echo "<b style='color:red'>Failed</b>";
	// 			}
	// 		}
	// 		echo " $id_paket <br />";
	// 	}
	// }

	// Icon
	public function boxicons()
	{
			$data = array(
				'judul_web' => 'Box Icons',
				'content'		=> "plugin/icon/boxicons",
			);
			$this->load->view("users/index", $data);
	}

	public function test_email()
	{
		check_permission('page', 'create', "setup/email");
		$level = get_session('level');
		if(!isset($level)) { redirect("web/login"); }
		if($level!=0){ redirect('404'); }
		sent_email('test');
	}

	function error_not_found(){
		if (get('menu', array('url'=>uri('x')))->num_rows()!=0) {
			redirect('web/coming-soon?url=dashboard');
		}
		$judul_web = "404 Halaman tidak ditemukan!";
		$this->_page('error_not_found', $judul_web);
	}

	function noscript(){
		$judul_web = "JavaScript tidak aktif dibrowser anda!";
		$this->_page('noscript', $judul_web);
	}

	function maintenance(){
		$judul_web = "Maintenance";
		$this->_page('maintenance', $judul_web);
	}

	function coming_soon(){
		$judul_web = "";
		$this->_page('coming_soon', $judul_web);
	}

	function _page($method='',$judul_web='')
	{
		if(!method_exists($this,$method)){ redirect('404'); }
		if ($judul_web=='') {
			$judul_web = web('title_web');
		}else {
			$judul_web = $judul_web." - ".web('title_web');
		}
		$data = array(
			'judul_web' => $judul_web,
			'content' 	=> "$this->view_page/$method"
		);
		$this->load->view("$this->view_page/index", $data);
	}


// AJAX =================
	function ajax_prov()
	{
		cekAjaxRequest();
		model('M_ajax','get_prov');
	}

	function ajax_pelanggan()
	{
		cekAjaxRequest();
		model('M_ajax','get_pelanggan');
	}

	function ajax_detail_pelanggan()
	{
		cekAjaxRequest();
		model('M_ajax','get_detail_pelanggan');
	}


	function ajax_type()
	{
		cekAjaxRequest();
		model('M_ajax','get_type');
	}

	// memanggil sub item unutk ditampilkan di update harga
	function ajax_sub_item()
	{
		model('M_ajax','get_sub_item');
	}
	//fungsi baru pemecahan tabel item master menjadi 2tbl masih di localhost
	// pwd :: master/item_master_sub
	function ajax_item_master2()
	{
		cekAjaxRequest();
		model('M_ajax','get_item_master2');
	}

	function ajax_jekel()
	{
		cekAjaxRequest();
		model('M_ajax','get_jekel');
	}

	function ajax_sales()
	{
		cekAjaxRequest();
		model('M_ajax','get_sales');
	}

	function ajax_item_master()
	{
		cekAjaxRequest();
		model('M_ajax','get_item_master');
	}

	function ajax_kota()
	{
		cekAjaxRequest();
		model('M_ajax','get_kota');
	}

	function ajax_kec()
	{
		cekAjaxRequest();
		model('M_ajax','get_kec');
	}

	function ajax_kec_multi()
	{
		if (isset($_POST)) {
			$id = substr(post('id'),6);
			$this->db->select('B.kecamatan as kec');
			$this->db->join('kecamatan AS B', 'B.id_kecamatan=A.id_kecamatan');
			if (!empty(post('status'))) {
				$this->db->where('A.status', post('status'));
			}
			$nama = get('pasar_kecamatan AS A', array('A.id_pasar'=>$id))->result_array();
			echo json_encode(array('nama'=>$nama,'id'=>$id));
		}
	}

	function ajax_kel()
	{
		cekAjaxRequest();
		model('M_ajax','get_kel');
	}

	function ajax_wilayah()
	{
		cekAjaxRequest();
		model('M_ajax','get_wilayah');
	}

	function ajax_plu()
	{
		cekAjaxRequest();
		model('M_ajax','get_plu');
	}

	function ajax_pasar()
	{
		cekAjaxRequest();
		model('M_ajax','get_pasar');
	}

	function ajax_item_kat()
	{
		cekAjaxRequest();
		model('M_ajax','get_item_kategori');
	}

	function ajax_item_lokasi()
	{
		cekAjaxRequest();
		model('M_ajax','get_item_lokasi');
	}

	function ajax_plu_sub()
	{
		cekAjaxRequest();
		model('M_ajax','get_plu_sub');
	}

	public function get_select($name='', $id_prov='')
	{
		if ($name!='') {
			if(isset($_GET['filter']) && $_GET['filter'] == 'yes') {
				  if ($name=='kota') {
						$tbl		= 'kota';
						$select = 'id_kota AS id, kota AS title';
						$like 	= 'kota';
					}else {
						echo json_encode(array());
						exit;
					}
					$this->db->select($select);
					if ($id_prov!='') {
						$this->db->where('id_provinsi', $id_prov);
					}
					if (!empty($_GET['q'])) {
						$this->db->like($like, $_GET['q']);
					}
					$this->db->order_by($like, 'ASC');
					$this->db->limit(10);
					$result = get($tbl);
			   $json = [];
				 foreach ($result->result() as $key => $value) {
					 $json[] = ['id'=>$value->id, 'text'=>$value->title];
				 }
			  echo json_encode($json);
			}
		}
	}


}
