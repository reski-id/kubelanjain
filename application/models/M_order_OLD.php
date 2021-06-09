<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_order extends CI_Model
{

  public function add_order($aksi='')
  {
    if (!check_permission('view', 'create', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if (isset($_POST)) {
      $id_user = get_session('id_user');
      $pesan = '';
      $simpan=false;
      $no_transaksi = post('id_order');
      $id_paket     = post('paket');
      $jumlah       = abs(post('jumlah'));
      if ($aksi=='edit') {
        $this->db->select('id_order');
        $get_ORDER = get('order', array('no_transaksi'=>$no_transaksi))->row();
        if (empty($get_ORDER)) {
          echo json_encode(array("stt"=>0, 'pesan'=>'Data tidak valid!'));
          exit;
        }
      }
      $total_harganya=0;
      $this->db->trans_begin();
      $this->db->select('id_paketnya, paket, jenis, qty, pcs, unit, free_qty, harga, paketnya');
      $this->db->limit(1);
      $paketnya = get_field('paketnya', array('id_paketnya'=>$id_paket));
      if (empty($paketnya)) {
        $stt = 0; $pesan='Paket tidak valid!';
      }else {
        $KU_harga = substr(khususAngka(post('harga')), -3);
        $total_harganya = ($paketnya['harga']*$jumlah)+$KU_harga;
        $dt_order['id_paket'] = $paketnya['id_paketnya'];
        $dt_order['paket']    = $paketnya['paket'];
        $dt_order['jenis']    = $paketnya['jenis'];
        $dt_order['qty']      = $paketnya['qty'];
        $dt_order['pcs']      = $paketnya['pcs'];
        $dt_order['jenis_satuan'] = $paketnya['unit'];
        $dt_order['free_qty'] = $paketnya['free_qty']*$jumlah;
        $ongkir = 0;
        if (!empty(post('get_ongkir'))) {
          $ongkir = khususAngka(post('get_ongkir'));
        }
        $dt_order['ongkir']   = $ongkir;
        $dt_order['harga']    = $paketnya['harga'];
        $dt_order['jumlah']   = $jumlah;
        $dt_order['kode_unik_harga'] = $KU_harga;
        $dt_order['total_harga'] = $ongkir + $total_harganya;
        $dt_order['catatan']  = $paketnya['paketnya'];
        $T_jml = ($paketnya['qty']+$paketnya['free_qty'])*$jumlah;
        // $this->db->order_by('id_order', 'ASC');
        // $this->db->limit(1);
        // if (get('order', array('id_user'=>$id_user))->num_rows()==0) {
        if ($aksi!='edit') {
          $dt_order['no_transaksi'] = $no_transaksi;
          $dt_order['id_user']  = $id_user;
          $dt_order['status']   = '0';
          $dt_order['pembayaran_status'] = 'Belum Lunas';
          $dt_order['pembayaran_persen'] = 0;
          $dt_order['jasa_pengiriman']  = post('get_jasa_pengiriman');
          $dt_order['berat']            = post('beratnya');
          $dt_order['estimasi_sampai']  = post('estimasi_sampai');
          $dt_order['tgl_input'] = tgl_now();
          $simpan = add_data('order', $dt_order);
          $id_order = $this->db->insert_id();
        }else{
          $dt_order['tgl_update'] = tgl_now();
          $simpan = update_data('order', $dt_order, array('no_transaksi'=>$no_transaksi));
          $id_order = $get_ORDER->id_order;
        }
        if (!$simpan) {
          $stt=0;
        }else {
          $T_jml_karton = 0;
          $post_item=array();
          foreach (get_item_masternya('04')->result() as $key => $value) {
            $plu=$value->plu; $nama=$value->nama_item; $jml_karton=post('plu_'.$plu);
            $post_item[] = array('id_order'=>$id_order, 'no_transaksi'=>$no_transaksi, 'plu'=>$plu, 'nama_item'=>$nama, 'jumlah'=>$jml_karton, 'tgl_input'=>tgl_now());
            $T_jml_karton += $jml_karton;
          }
          if ($T_jml_karton > $T_jml) {
            $simpan=false; $pesan='Jumlah Request Varian tidak boleh melebihi Jumlah Pesan!';
          }elseif ($T_jml_karton < 0) {
            $simpan=false; $pesan='Jumlah Request Varian tidak boleh kurang dari 0!';
          }else {
            if (!empty($post_item)) {
              if ($aksi=='edit') {
                $simpan = delete_data('order_item', array('id_order'=>$id_order, 'no_transaksi'=>$no_transaksi));
                if ($simpan) {
                  $simpan = add_batch('order_item', $post_item);
                }
              }else{
                $simpan = add_batch('order_item', $post_item);
                if (!empty($_FILES['foto'])) {
                  if (!empty(post('nominal_pembayaran'))) {
                    if ($simpan) {
                      $tanggal = date('Y-m-d');
                      $simpan = $this->add_payment($no_transaksi, $id_order, $tanggal, 1);
                      if (!$simpan) {
                        $pesan = 'Gagal upload bukti, pastikan maksimal file 5 MB & File yang diizinkan .jpeg .jpg .png .gif .bmp';
                      }
                    }
                  }
                }else {
                  if (!empty(post('nominal_pembayaran'))) {
                    $simpan = false;
                    $pesan = 'Jika Foto Bukti Pembayaran diisi maka Nominal Pembayarannya wajib diisi, Terimakasih!';
                  }
                }
              }
            }else{
              $simpan = false;
            }
          }

        }
      }
      // log_r($post_item);
      if ($simpan) {
        $this->db->trans_commit();
        $stt = 1; $pesan='';
        if ($aksi=='edit') {
          $pesan = "Berhasil disimpan";
        }else {
          $this->load->helper("api_sms");
          $message .= "ID ORDER : $no_transaksi\nPAKET ".$paketnya['paket']." dengan Jumlah Pesan (Paket) $jumlah dan Jumlah Total Harga Rp. ".format_angka($total_harganya).",-\nInformasikan bukti pembayaran Anda, Terimakasih.";
          Send_message('wa', 'POST', get_session('username'), $message);

          $this->db->select('id_mitra, nama_lengkap');
          $this->db->limit(1);
          $get_usernya = get_field('user_biodata_reseller', array('id_user'=>$id_user));
          $id_res = $get_usernya['id_mitra'];
          $nama   = $get_usernya['nama_lengkap'];

          $this->db->select('pembayaran_status, pembayaran_persen');
          $this->db->limit(1);
          $get_ordernya = get_field('order', array('id_order'=>$id_order));

          $status = $get_ordernya['pembayaran_status'];
          $persen = $get_ordernya['pembayaran_persen'];
          // if ($persen >= 100) { $persen = 100; }
          $id_tele = get_bot_group(5, 'Order');
          $message = "<b>ID ORDER : $no_transaksi</b>\n\n";
          $message .= "<b>ID RESELLER : </b> $id_res\n";
          $message .= "<b>NAMA RESELLER : </b> $nama\n";
          $message .= "<b>PAKET :</b> ".$paketnya['paket']."\n";
          $message .= "<b>JUMLAH PESAN (PAKET) :</b> ".format_angka($jumlah)."\n";
          $message .= "<b>TOTAL BAYAR :</b> Rp. ".format_angka($total_harganya).",-";
          $message .= "\n<b>STATUS :</b> $status ($persen %)";
          SendMessage_tele($id_tele, $message);
        }
      }else {
        $this->db->trans_rollback();
        $stt = 0;
        if ($pesan=='') {
          $pesan = "Gagal disimpan, silahkan coba lagi!";
        }
      }

      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;
    }
  }

  public function get_list_paket($stt_order='', $limit='')
  {
    // STATUS = 0:order, 1:payment, 2:delivery, 3:done, 4:cancel
    if (isset($_POST)) {
      $id_user = get_session('id_user');
      $level   = get_session('level');
      $id_kota = get_session('id_kota');
      $id_mitra = get_session('id_mitra');
      $type_id  = get_session('type_id');
			$cari = post('cari');
      $tbl='order';

      if ($level==1) {
        $this->db->select('id_user');
        $Get_reseller = get('user_biodata_reseller', array('id_referal'=>$id_mitra));
        $id_res = array();
        foreach ($Get_reseller->result() as $key => $value) {
          $id_res[] = $value->id_user;
        }
        if ($type_id==1) {
          $this->db->select('id_mitra');
          $Get_mitra_2 = get('user_biodata_mitra', array('id_referal'=>$id_mitra,'type_id'=>'2'));
          foreach ($Get_mitra_2->result() as $key => $value) {
            $this->db->select('id_user');
            $Get_reseller2 = get('user_biodata_reseller', array('id_referal'=>$value->id_mitra));
            foreach ($Get_reseller2->result() as $key => $value2) {
              $id_res[] = $value2->id_user;
            }
          }
        }
        if (!empty($id_res)) {
          $this->db->where_in('id_user', $id_res);
        }else {
          $this->db->where('id_user', '');
        }
      }
      if ($level==2) { //list order reseller
        $this->db->where('id_user', $id_user);
      }
      $this->db->where('status', $stt_order);
			if (!empty($cari)) {
				$this->db->where("
        (no_transaksi LIKE '%". $cari."%' OR nama_lengkap LIKE '%".$cari."%' OR
        no_hp LIKE '%".$cari."%' OR paket LIKE '%".$cari."%' OR
        date_in LIKE '%".$cari."%')",null,false);
			}

			// if ($level==2 && in_array($stt_order, array('3','4'))) {
			// 	$this->db->where('tgl_buat >=', tgl_format(tgl_now(),'Y-m-d H:i:s', '-3 Days'));
			// 	$this->db->where('tgl_buat <=', tgl_now());
			// }

      // if ($level!=0) {
      //   if ($id_kota!='') {
      //     $this->db->where('id_kota', $id_kota);
      //   }
      // }

      $this->db->order_by('id_order','DESC');
      $this->db->order_by('nama_lengkap','ASC');

      if ($limit!='') { $this->db->limit($limit); }

      $get = get('v_user_'.$tbl);
			$arr = array();
			foreach ($get->result() as $key => $value) {
				$arr[] = $value;
			}
			echo '{"detailnya":' . json_encode($arr).'}';
		}
  }


  public function up_bukti()
  {
    if (!check_permission('view', 'update', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    $pesan='';
    $id = post('id_order_pembayaran');
    $this->db->select('a.id_user, a.tgl_input, a.no_transaksi');
    $this->db->join('order_pembayaran as b', 'a.id_order=b.id_order');
    $get_order = get('order as a', array('b.id_order_pembayaran'=>$id))->row();
    if (empty($get_order)) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Data tidak Valid!"));
      exit;
    }
    $no      = $get_order->no_transaksi;
    $id_user = $get_order->id_user;
    $tgl     = $get_order->tgl_input;
    $path_tgl = tgl_format($tgl,'Y').'/'.tgl_format($tgl,'m');
    $path    = get_path_img('custom', "order/$id_user/$path_tgl/$no");
    createPath($path, 0777);
    upload_config($path,'5','jpeg|jpg|png|gif|bmp');
    $foto = upload_file('foto_bukti',$path,'ajax', '', 1);
    if (!empty($foto['pesan'])) {
      $stt=0; $pesan = $foto['pesan'];
    }else{
      $stt=1; $post['foto_bukti'] = $foto;
      $cek_resize = resizeImage($foto,$path);
      if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
    }
    $simpan = false;
    if ($stt==1) {
      $post['update_by_id'] = get_session('id_user');
      $post['update_by_ip'] = $this->input->ip_address();
      $simpan = update_data('order_pembayaran', $post, array('id_order_pembayaran'=>$id));
      if (!$simpan) {
        $pesan = 'Data tidak Valid!!';
      }
    }
    if ($simpan) {
      $this->db->trans_commit();
      $pesan = 'Bukti berhasil diupload';
    }else{
      $this->db->trans_rollback();
      if(!empty($foto)){
        if (file_exists($foto)) { unlink($foto); }
      }
      if ($pesan=='') {
        $pesan = 'Gagal Upload, silahkan coba lagi!';
      }
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  public function add_payment($no='', $id='', $tanggal='', $aksi='')
  {
    if (!check_permission('view', 'create', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if (isset($_POST)) {
      if ($aksi=='') {
        $no = decode($no);
        $id = decode($id);
        $this->db->trans_begin();
      }
      $this->db->select('id_user, ongkir, total_harga, tgl_input');
      $get_order = get('order', array('no_transaksi'=>$no, 'id_order'=>$id))->row();
      if (empty($get_order)) {
        if ($aksi==1) {
          return false;
        }else {
          echo json_encode(array("stt"=>0, 'pesan'=>"Data tidak Valid!"));
          exit;
        }
      }
      $persen=0;
      $id_user = $get_order->id_user;
      $tgl   = $get_order->tgl_input;
      if ($tanggal=='') { $tanggal = post('tanggal_payment'); }
      $pesan = '';
      if (!empty($_FILES['foto'])) {
        $path_tgl = tgl_format($tgl,'Y').'/'.tgl_format($tgl,'m');
        $path  = get_path_img('custom', "order/$id_user/$path_tgl/$no");
        createPath($path, 0777);
        upload_config($path,'5','jpeg|jpg|png|gif|bmp');
        $foto = upload_file('foto',$path,'ajax', '', 1);
        if (!empty($foto['pesan'])) {
  				$stt=0; $pesan = $foto['pesan'];
  			}else{
          $stt=1; $post['foto_bukti'] = $foto;
          $cek_resize = resizeImage($foto,$path);
  				if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
        }
      }else{
        $stt=1; $post['foto_bukti'] = '';
      }
      $simpan=false;
      if ($stt==1) {
        $tbl_pembayaran = 'order_pembayaran';
        $ongkir = $get_order->ongkir;
        if (in_array($ongkir, array('', null))) { $ongkir=0; }
        $total_harga = $get_order->total_harga - $ongkir;
        $nominal     = preg_replace('/[Rp. ]/', '', post('nominal_pembayaran'));
        $this->db->select('COALESCE(SUM(nominal_pembayaran),0) AS totalnya');
        $get_payment = get($tbl_pembayaran, array('id_order'=>$id))->row();
        if (empty($get_payment)) {
          $total_nominal = $nominal;
        }else {
          $total_nominal = $get_payment->totalnya + $nominal;
        }
        $totalnya = $total_harga - $total_nominal;
        // if ($totalnya < 0) {
        //   $stt = 0;
        //   $pesan = 'Maaf, Nominal Pembayaran yang Anda input melebihi Total Harga';
        // }else {
          $persen = number_format(($total_nominal/$total_harga)*100,2,'.','');
          if ($persen>=100) {
            $stt_pembayaran = 'Lunas';
          }else {
            $stt_pembayaran = 'Belum Lunas';
          }
          $simpan = update_data('order', array('pembayaran_status'=>$stt_pembayaran, 'pembayaran_persen'=>$persen), array('id_order'=>$id));
          if ($simpan) {
            $post['id_order'] = $id;
            $post['id_user']  = $id_user;
            $post['tanggal']  = tgl_format($tanggal, 'Y-m-d');
            $post['nominal_pembayaran'] = $nominal;
            $post['input_by_id']  = get_session('id_user');
            $post['input_by_ip']  = $this->input->ip_address();
            $post['tgl_input'] = tgl_now();
            $simpan = add_data($tbl_pembayaran, $post);
          }
        // }
      }
      if ($simpan) {
        $this->load->helper("api_sms");
        $no_hp_reseller = get_no_hp_user($id_user);
        if ($persen>=100) {
          $message = "PEMBAYARAN MEEJU\nID ORDER : $no\nTanggal: $tanggal\nStatus Pembayaran : LUNAS\nTerimakasih.";
        }else{
          $message = "PEMBAYARAN MEEJU\nID ORDER: $no\nTanggal: $tanggal\nNominal: ".format_angka($nominal, 'rp')."\nSisa: ".format_angka($totalnya, 'rp')."\nStatus: BELUM LUNAS ($persen %)\nTerimakasih.";
        }
        Send_message('wa', 'POST', $no_hp_reseller, $message);
        if ($aksi==1) {
          return true;
        }else{
          $this->db->trans_commit();
          $stt = 1;
          $pesan = "Berhasil disimpan";
        }
      }else{
        if ($aksi==1) {
          // if(!empty($foto)){
          //   if (file_exists($foto)) { unlink($foto); }
          // }
          return false;
        }else{
          $this->db->trans_rollback();
          if(!empty($foto)){
            if (file_exists($foto)) { unlink($foto); }
          }
          $stt = 0;
          if ($pesan=='') {
            $pesan = "Gagal disimpan, silahkan coba lagi!";
          }
        }
      }
      if ($aksi!=1) {
        echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
        exit;
      }
    }
  }

  public function get_detail_payment($limit='')
  {
    if (isset($_POST)) {
      $cari = post('cari');
			$id   = decode(post('id'));
      $tbl  = 'order_pembayaran';
			if (!empty($cari)) {
				$this->db->where("
        (tanggal LIKE '%". $cari."%' OR nominal_pembayaran LIKE '%".$cari."%' OR
        foto_bukti LIKE '%".$cari."%')",null,false);
			}
      $this->db->order_by('id_order_pembayaran','ASC');
      if ($limit!='') { $this->db->limit($limit); }
      $get = get($tbl, array('id_order'=>$id));
			$arr = array();
			foreach ($get->result() as $key => $value) {
				$arr[] = $value;
			}
			echo '{"detailnya":' . json_encode($arr).'}';
		}
  }

  public function hapus_payment()
  {
    if (!check_permission('view', 'delete', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if (isset($_POST)) {
			$tbl = 'order_pembayaran';
			$id  = post('id');
			$where = array("id_$tbl"=>$id);
			$this->db->select('id_order, foto_bukti');
			$get_data = get($tbl, $where)->row();
			if (empty($get_data)) {
				echo json_encode(array('stt'=>0, 'pesan'=>'Data tidak valid!'));
				exit;
			}
			$foto = ''; $id_order=$get_data->id_order;
			if (!empty($get_data)) { $foto = $get_data->foto_bukti; }
			$simpan = false;
			$this->db->trans_begin();
			$hapus = delete_data($tbl, $where);
			if ($hapus) {
				$this->db->select('total_harga');
	      $get_order = get('order', array('id_order'=>$id_order))->row();
				$total_harga = $get_order->total_harga;

				$this->db->select('COALESCE(SUM(nominal_pembayaran),0) AS totalnya');
				$get_payment = get($tbl, array('id_order'=>$id_order))->row();
				if (empty($get_payment)) {
					$total_nominal = 0;
				}else {
					$total_nominal = $get_payment->totalnya;
				}
				$persen = number_format(($total_nominal/$total_harga)*100,2,'.','');
				if ($persen==100) {
					$stt_pembayaran = 'Lunas';
				}else {
					$stt_pembayaran = 'Belum Lunas';
				}
				$simpan = update_data('order', array('pembayaran_status'=>$stt_pembayaran, 'pembayaran_persen'=>$persen), array('id_order'=>$id_order));
			}
			if ($simpan) {
				$this->db->trans_commit();
				if (file_exists($foto)) { unlink($foto); }
				$stt=1; $pesan = 'Berhasil dihapus';
			}else{
				$this->db->trans_rollback();
				$stt=0; $pesan = 'Gagal Hapus, silahkan coba lagi!';
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
		}
  }


  public function delivery_order()
  {
    if (isset($_POST)) {
      $update=false;
      $where = array('id_order'=>decode(post('id')));
      // log_r($_POST);
      $this->db->select('total_harga');
      $get = get_field('order', $where);
      $this->db->trans_begin();
      if (!empty($get)) {
        $this->db->select('ongkir');
    		$get_ongkir = get_field('order', $where)['ongkir'];
        if (empty($get_ongkir)){ $get_ongkir=0; }
        $ongkir = khususAngka(post('ongkir'));
        $total_harga = $ongkir + ($get['total_harga'] - $get_ongkir);
        $update = update_data('order', array('status'=>2, 'ongkir'=>$ongkir, 'total_harga'=>$total_harga, 'alamat_pengantaran'=>post('alamat_pengantaran'), 'tgl_delivery'=>tgl_now()), $where);
      }
      if ($update) {
        $this->db->trans_commit();
        $stt=1; $pesan = '';
      }else{
        $this->db->trans_rollback();
        $stt=0; $pesan = 'Gagal, silahkan coba lagi!';
      }
      echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
      exit;
    }
  }

  public function done_order()
  {
    if (!check_permission('view', 'update', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if (isset($_POST)) {
      $where = array('id_order'=>post('id'));
      // log_r($_POST);
      $this->db->trans_begin();
      $update = update_data('order', array('status'=>3, 'tgl_done'=>tgl_now(), 'done_by'=>get_session('id_user')), $where);
      if ($update) {
        $this->db->trans_commit();
        $this->load->helper("api_sms");
    		$this->db->select('id_user');
    		$id_user = get_field('order', $where)['id_user'];
    		$tbl = 'user_fee';
    		$this->db->select('id_master, fee_master');
    		$send_mitra1 = get_field($tbl, array('id_child'=>$id_user));
    		if (!empty($send_mitra1)) {
    			$kirim=array(); $no_hp_mitra1=''; $fee_1='';
    			$get_fee_1 = $send_mitra1['fee_master'];
    			if ($get_fee_1!=0) {
    				$no_hp_mitra1 = get_no_hp_user($send_mitra1['id_master']);
    				$fee_1 = format_angka($get_fee_1, 'rp');
    				$kirim[] = array('no_hp'=>$no_hp_mitra1, 'fee'=>$fee_1);
    			}
    			$this->db->select('id_master, fee_child, fee_master');
    			$send_mitra2 = get_field($tbl, array('id_child'=>$send_mitra1['id_master']));
    			if (!empty($send_mitra2)) {
    				$get_fee_2 = $send_mitra2['fee_master'];
    				if ($get_fee_2!=0) {
    					$kirim=array();
    					$no_hp_mitra2 = get_no_hp_user($send_mitra2['id_master']);
    					if ($no_hp_mitra1!='') {
    						$fee_1 = format_angka($send_mitra2['fee_child'], 'rp');
    						$kirim[] = array('no_hp'=>$no_hp_mitra1, 'fee'=>$fee_1);
    					}
    					$fee_2 = format_angka($get_fee_2, 'rp');
    					$kirim[] = array('no_hp'=>$no_hp_mitra2, 'fee'=>$fee_2);
    				}
    			}
    			// if (!empty($kirim)) {
    			// 	foreach ($kirim as $key => $value) {
    			// 		$no_hp = $value['no_hp'];
    			// 		$message = "INFO MEEJU\nSelamat Anda mendapatkan Fee ".$value['fee'];
    			// 		Send_message('wa', 'POST', $no_hp, $message);
          //     sleep(1);
    			// 	}
    			// }
    		}
        $stt=1; $pesan = '';
      }else{
        $this->db->trans_rollback();
        $stt=0; $pesan = 'Gagal, silahkan coba lagi!';
      }
      echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
      exit;
    }
  }

  public function hapus_order()
  {
    if (!check_permission('view', 'delete', 'order')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if (isset($_POST)) {
      $where = array('id_order'=>decode(post('id')));
      // log_r($_POST);
      $this->db->trans_begin();
      $hapus = update_data('order', array('status'=>4, 'alasan_cancel'=>post('alasan'), 'tgl_cancel'=>tgl_now()), $where);
			// $hapus = delete_data('order_item', $where);
      // if ($hapus) {
      //   $hapus = delete_data('order', $where);
      // }
      // if ($hapus) {
      //   $foto=array();
      //   $this->db->select('foto_bukti');
  		// 	$get_data = get('order_pembayaran', $where);
      //   foreach ($get_data->result() as $key => $value) {
      //     $foto[] = $value->foto_bukti;
      //   }
      //   $hapus = delete_data('order_pembayaran', $where);
      // }
      if ($hapus) {
				$this->db->trans_commit();
        // foreach ($foto as $key => $fotonya) {
        //   if (file_exists($fotonya)) { unlink($fotonya); }
        // }
				$stt=1; $pesan = 'Berhasil Cancel';
			}else{
				$this->db->trans_rollback();
				$stt=0; $pesan = 'Gagal Cancel, silahkan coba lagi!';
			}
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
    }
  }


}
?>
