<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_order extends CI_Model
{

  var $tabel_order  = 'order';

  function ajax_failed($pesan='Failed', $stt=0)
  {
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  function ajax_permission($aksi='', $url='')
  {
    if (!check_permission('view', $aksi, $url)) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
  }

  public function get_item_plu($stt='')
  {
    if (isset($_POST)) {
      $json = array();
      $pasar = post('pasar');
      $plu  = post('plu');
      $plu2 = post('plu2');
      $item_plu = post('item_plu');
      if ($stt=='cek_select') {
        $sel_data = array_unique(json_decode(html_entity_decode($this->input->post('sel'))));
      }else{
        $sel_data = array();
      }
      $cari = post('cari');
      $this->db->select('A.id_item_master, A.plu, B.nama_item');
      $this->db->join('toko_harga AS B', 'A.id_item_master=B.id_item_master');
      $this->db->where("(A.plu LIKE '%".$cari."%' OR A.nama_item LIKE '%".$cari."%')",null,false);
      if (!empty($sel_data)) {
        if (!empty($item_plu)) {
          $sel_datax=array();
          foreach ($sel_data as $key => $value) {
            $sel_datax[] = explode('-', $value)[1];
          }
          $sel_data = array();
          $sel_data = $sel_datax;
        }
        if (!empty($sel_data)) {
          $this->db->where_not_in('A.plu', $sel_data);
        }
      }
      if (!empty($plu)) {
        if (empty($plu2)) {
          $this->db->like('A.plu', $plu, 'after');
        }
      }
      if (!empty($plu2)) {
        $this->db->where("(A.plu LIKE '".$plu."%' OR A.plu LIKE '%".$plu2."%')",null,false);
      }
      $this->db->order_by('A.plu', 'ASC');
      $this->db->group_by('A.plu');
      $get = get('item_master AS A', array('B.status'=>1, 'B.id_pasar'=>$pasar));
  		foreach ($get->result() as $key => $value) {
        if (!empty($item_plu)) {
          $idnya = $value->id_item_master. '-' .$value->plu. '-' .$value->nama_item;
        }else {
          $idnya = $value->plu;
        }
        $json[] = ['id'=>$idnya, 'text'=>"$value->nama_item"];
      }
      // log_r($this->db->last_query());
      echo json_encode($json);
      exit;
    }
  }

  public function get_item_update_harga($stt='')
  {
    if (isset($_POST)) {
      $json = array();
      $pasar = post('pasar');
      $plu  = post('plu');
      $plu2 = post('plu2');
      $item_plu = post('item_plu');
      if ($stt=='cek_select') {
        $sel_data = array_unique(json_decode(html_entity_decode($this->input->post('sel'))));
      }else{
        $sel_data = array();
      }
      $cari = post('cari');
      $this->db->select('A.id_item_master_sub AS id_item_master, A.plu_sub AS plu, A.nama_item_sub AS nama_item');
      $this->db->where("(A.plu_sub LIKE '%".$cari."%' OR A.nama_item_sub LIKE '%".$cari."%')",null,false);
      if (!empty($sel_data)) {
        if (!empty($item_plu)) {
          $sel_datax=array();
          foreach ($sel_data as $key => $value) {
            $sel_datax[] = explode('-', $value)[1];
          }
          $sel_data = array();
          $sel_data = $sel_datax;
        }
        if (!empty($sel_data)) {
          $this->db->where_not_in('A.plu_sub', $sel_data);
        }
      }
      if (!empty($plu)) {
        if (empty($plu2)) {
          $this->db->like('A.plu_sub', $plu, 'after');
        }
      }
      if (!empty($plu2)) {
        $this->db->where("(A.plu_sub LIKE '".$plu."%' OR A.plu_sub LIKE '%".$plu2."%')",null,false);
      }
      $this->db->order_by('A.plu_sub', 'DESC');
      $this->db->order_by('A.nama_item_sub', 'DESC');
      $this->db->group_by('A.plu_sub');
      $get = get('update_harga AS A', array('A.id_pasar'=>$pasar));
  		foreach ($get->result() as $key => $value) {
        if (!empty($item_plu)) {
          $idnya = $value->id_item_master. '-' .$value->plu. '-' .$value->nama_item;
        }else {
          $idnya = $value->plu_sub;
        }
        $json[] = ['id'=>$idnya, 'text'=>"$value->nama_item"];
      }
      // log_r($this->db->last_query());
      echo json_encode($json);
      exit;
    }
  }

  public function get_harga()
  {
    if (isset($_POST)) {
      $p = explode('-', post('p'))[0];
      if (post('tbl')=='update_harga') {
        $this->db->select('harga, harga_beli');
        $this->db->order_by('id_update_harga', 'DESC');
        $this->db->limit(1);
        $data = get_field('update_harga', array('id_item_master_sub'=>$p, 'id_pasar'=>post('p2')));
      }else {
        $this->db->select('harga, harga_beli');
        $data = get_field('toko_harga', array('status'=>1, 'id_item_master'=>$p, 'id_pasar'=>post('p2')));
      }
      echo json_encode(array('harga'=>$data['harga'], 'harga_beli'=>$data['harga_beli']));
      exit;
  	}
  }


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
          $message = "<b>ID ORDER : $no_transaksi</b>\n\n";
          $message .= "<b>ID RESELLER : </b> $id_res\n";
          $message .= "<b>NAMA RESELLER : </b> $nama\n";
          $message .= "<b>PAKET :</b> ".$paketnya['paket']."\n";
          $message .= "<b>JUMLAH PESAN (PAKET) :</b> ".format_angka($jumlah)."\n";
          $message .= "<b>TOTAL BAYAR :</b> Rp. ".format_angka($total_harganya).",-";
          $message .= "\n<b>STATUS :</b> $status ($persen %)";
          foreach (get_bot_group(5) as $key => $id_tele) {
            SendMessage_tele($id_tele, $message);
          }
          // $id_tele = get_bot_group(5, 'Order - JUNK_BOT');
          // SendMessage_tele($id_tele, $message);
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


  public function arr_split($data=array())
  {
    $val='';
    unset($data[0]);
    foreach ($data as $key => $value) {
      $val .= $value.' ';
    }
    if ($val!='') {
      $val = substr($val, 0, -1);
    }
    return $val;
  }

  public function order_proses_simpan($id='', $id_pelanggan='')
  {
   $this->ajax_permission('create', 'order');
   $id_user = get_session('id_user');
   $nama_input = "$id_user - ".user('nama_lengkap');
   $tgl_input  = tgl_now();
   $unset = array('plux','harga','harga_beli','qty','total','note','simpan');
   foreach ($unset as $key => $value) {
     unset($_POST[$value]);
   }
   $tbl  = $this->tabel_order;
   $tbl1 = $this->tabel_order.'_item';
   // START SET MASTER
   if ($id=='') {
     $no_transaksi = post('id_order');
     $post['no_transaksi']  = $no_transaksi;
   }
   $post['id_provinsi']     = post('id_provinsi');
   $post['id_kota']         = post('id_kota');
   $post['id_pelanggan']    = post('id_pelanggan');
   $this->db->select('pelanggan, nohp_satu, id_jekel');
   $get_pelanggan = get_field('pelanggan', array('id_pelanggan'=>$post['id_pelanggan']));
   $jk = get_name_jekel($get_pelanggan['id_jekel']);
   $post['nama_pelanggan']  = $jk.' '.$get_pelanggan['pelanggan'];
   $post['no_hp']           = $get_pelanggan['nohp_satu'];
   $post['id_pasar']        = post('id_pasar');
   $post['ongkir']          = khususAngka(post('ongkir'));
   $post['diskon']          = khususAngka(post('diskon'));
   $post['tgl_pengantaran'] = tgl_format(post('tgl_pengantaran'), 'Y-m-d'). ' ' .post('jam_pengantaran').':00';
   $post['catatan']         = post('catatan');
   if ($id=='') {
     $post['status']        = 0;
     $post['tgl_input']     = $tgl_input;
     $post['input_by']      = $nama_input;
   }else {
     $post['tgl_update']    = $tgl_input;
     $post['update_by']     = $nama_input;
   }
   $sub_total      = 0;
   $total_belanja = 0;
   // END SET MASTER

   // START SET ITEM
   $post1 = array(); $i=0;
   $plunya = array_unique(json_decode(html_entity_decode($this->input->post('plunya'))));
   $harganya      = array_unique(json_decode(html_entity_decode($this->input->post('harganya'))));
   $harga_belinya = array_unique(json_decode(html_entity_decode($this->input->post('harga_belinya'))));
   $qtynya        = array_unique(json_decode(html_entity_decode($this->input->post('qtynya'))));
   $notenya       = array_unique(json_decode(html_entity_decode($this->input->post('notenya'))));
   if (!empty($plunya)) {
     foreach ($plunya as $key => $value) {
       $exp = explode('-', $value);
       if ($id=='') {
         $post1[$i]['no_transaksi'] = $no_transaksi;
       }else {
         $post1[$i]['id_order']     = $id;
       }
       $harga = explode(' ', $harganya[$key])[1];
       $qty   = explode(' ', $qtynya[$key])[1];
       $hb    = explode(' ', $harga_belinya[$key])[1];
       $note  = $this->arr_split(explode(' ', $notenya[$key]));
       $id_item_sub = $exp[0];
       $this->db->select('id_item_master, plu, id_item_satuan, kode_satuan, nilai_satuan');
       $get_sub = get_field('item_master_sub', array('id_item_master_sub'=>$id_item_sub));
       if (empty($get_sub)) { continue; }
       $isian   = $get_sub['nilai_satuan'];
       $sum_qty = $isian * $qty;
       $to_ons  = konversi_satuan(1, $get_sub['kode_satuan'], 'G', $sum_qty, 'kode');

       $post1[$i]['id_item_master']   = $get_sub['id_item_master'];
       $post1[$i]['plu_master']       = $get_sub['plu'];
       $post1[$i]['nama_item_master'] = get_name_item_plu($get_sub['plu']);
       $post1[$i]['id_item_satuan']   = $get_sub['id_item_satuan'];
       $post1[$i]['kode_satuan']      = $get_sub['kode_satuan'];
       $post1[$i]['plu']       = $exp[1];
       $post1[$i]['nama_item'] = $exp[2];
       $post1[$i]['harga']     = $harga;
       $post1[$i]['qty']       = $qty;
       $post1[$i]['total']     = $harga * $qty;
       $post1[$i]['isian']     = $isian;
       $post1[$i]['sum_qty']   = $sum_qty;
       $post1[$i]['to_ons']    = $to_ons;
       $post1[$i]['note']      = $note;
       $post1[$i]['hb']        = $hb;
       $post1[$i]['gap']       = $harga - $hb;
       $post1[$i]['tgl_input'] = $tgl_input;
       $sub_total += $harga * $qty;
       $i++;
     }
   }

   $manualnya = array_unique(json_decode(html_entity_decode($this->input->post('item_manualnya'))));
   $harganya2      = array_unique(json_decode(html_entity_decode($this->input->post('harganya2'))));
   $harga_belinya2 = array_unique(json_decode(html_entity_decode($this->input->post('harga_belinya2'))));
   $qtynya2        = array_unique(json_decode(html_entity_decode($this->input->post('qtynya2'))));
   $notenya2       = array_unique(json_decode(html_entity_decode($this->input->post('notenya2'))));
   if (!empty($manualnya)) {
     foreach ($manualnya as $key => $value) {
       $nama_item = $this->arr_split(explode(' ', $value));
       if ($id=='') {
         $post1[$i]['no_transaksi'] = $no_transaksi;
       }else {
         $post1[$i]['id_order']     = $id;
       }
       $harga = explode(' ', $harganya2[$key])[1];
       $qty   = explode(' ', $qtynya2[$key])[1];
       $hb    = explode(' ', $harga_belinya2[$key])[1];
       $note  = $this->arr_split(explode(' ', $notenya2[$key]));

       $isian   = 1;
       $sum_qty = $isian * $qty;
       $to_ons  = $sum_qty;

       $post1[$i]['id_item_master']   = 0;
       $post1[$i]['plu_master']       = 0;
       $post1[$i]['nama_item_master'] = $nama_item;
       $post1[$i]['id_item_satuan']   = 0;
       $post1[$i]['kode_satuan']      = 0;
       $post1[$i]['plu']       = 0;
       $post1[$i]['nama_item'] = $nama_item;
       $post1[$i]['harga']     = $harga;
       $post1[$i]['qty']       = $qty;
       $post1[$i]['total']     = $harga * $qty;
       $post1[$i]['isian']     = $isian;
       $post1[$i]['sum_qty']   = $sum_qty;
       $post1[$i]['to_ons']    = $to_ons;
       $post1[$i]['note']      = $note;
       $post1[$i]['hb']        = $hb;
       $post1[$i]['gap']       = $harga - $hb;
       $post1[$i]['tgl_input'] = $tgl_input;
       $sub_total += $harga * $qty;
       $i++;
     }
   }
   // END SET ITEM

   // START SET MASTER
   $post['sub_total']     = $sub_total;
   $post['total_belanja'] = ($sub_total + $post['ongkir']) - $post['diskon'];
   // END SET MASTER

   // log_r($post1);
   // START SAVE MASTER
   $id_NEW = '';
   if ($id=='') { //jika simpan data baru
     $simpan = add_data($tbl, $post);
     if ($simpan) { $id_NEW = $this->db->insert_id(); }
   }else { //jika update
     $simpan = add_update($tbl, $post, array('id_order'=>$id));
     if ($simpan) { $id_NEW = $id; }
   }
   // END SAVE MASTER
   if ($simpan) {
     // START SAVE ITEM
     if ($id=='') {
       for ($n=0; $n < $i; $n++) {
         $post1[$n]['id_order'] = $id_NEW;
       }
       $simpan = add_batch($tbl1, $post1);
     }else {
       $simpan = update_batch($tbl1, $post1, array('id_order'=>$id));
     }
     // END SAVE ITEM
   }
   if ($simpan) {
     $this->db->trans_commit();
     echo json_encode(array('stt'=>1, 'pesan'=>''));
     exit;
   }else {
     $this->db->trans_rollback();
     $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
   }
  }


  function order_cancel($id='')
  {
     $stt = 3;
     if (!in_array($stt, array(3))) { $this->ajax_failed(); }
     $this->ajax_permission('delete', 'order');
     $tbl = $this->tabel_order;

     $this->db->select("id_$tbl");
     $get_data = get_field($tbl, array("id_$tbl"=>$id));
     if (empty($get_data)) { $this->ajax_failed(); }

     $alasan = post('id');
     if ($alasan=='') { $this->ajax_failed('Alasan wajib diisi!'); }

     $id_user = get_session('id_user');
     $nama_input = "$id_user - ".user('nama_lengkap');

     $where = array("id_$tbl"=>$id);
     $post  = array('alasan_cancel'=>$alasan, 'status'=>$stt, 'tgl_cancel'=>tgl_now(), 'cancel_by'=>$nama_input);
     $simpan = update_data($tbl, $post, $where);
     if ($simpan) {
       $this->db->trans_commit();
       echo json_encode(array('stt'=>1, 'pesan'=>''));
     }else {
       $this->db->trans_rollback();
       $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
     }
     exit;
  }
  //END OF ORDER

  public function order_payment($id='')
  {
    $stt = 1;
    if (!in_array($stt, array(1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'order');
    $tbl = $this->tabel_order;

    $this->db->select("id_$tbl, no_transaksi, nama_pelanggan, no_hp, total_belanja, tgl_input");
    $get_data = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($get_data)) { $this->ajax_failed(); }

    $tp = post('id');
    if ($tp=='') { $this->ajax_failed('Metode Pembayaran belum dipilih!!'); }

    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');

    $where = array("id_$tbl"=>$id);
    $post  = array('type_pembayaran'=>$tp, 'status'=>$stt, 'tgl_payment'=>tgl_now(), 'payment_by'=>$nama_input);
    $simpan = update_data($tbl, $post, $where);
    if ($simpan) {
      $this->db->trans_commit();
      // Membuat PDF
        $data['id_order'] = $id;
        $nama_file  = "BIAR KAMI YANG BELANJA - ORDER #".$get_data['no_transaksi'].".pdf";
    		$nama_file2 = "BKYB - ORDER #".$get_data['no_transaksi'];
        $file_path = 'assets/file/order/'.$data['id_order'].'.pdf';
    		view('users/order/laporan/detail_order', $data);
    		$html = $this->output->get_output();
    	  // Load pdf library
    		$this->load->library('pdf');
    		$this->pdf->loadHtml($html);
    		$this->pdf->setPaper('A4', 'portrait');
    		$this->pdf->render();
    		// Output the generated PDF (1 = download and 0 = preview)
    		// $this->pdf->stream("html_contents.pdf", array("Attachment"=> 0));
        $output = $this->pdf->output();
    		file_put_contents($file_path, $output);
      // ----------------
      $tanggal = $get_data['tgl_input'];
      $pesannya = 'Terimakasih '.$get_data['nama_pelanggan'].' telah mempercayakan belanja pada kami, Semoga hari anda menyenangkan. 🙏🏿 😃';
      $caption  = "<b>NO. ORDER : ".$get_data['no_transaksi']."</b>\n\n";
      $caption .= "<b>".hari_id($tanggal).", ".tgl_id($tanggal, 'd-m-Y')." ".tgl_format($tanggal, 'H:i')."</b>\n";
      $caption .= "<b>NAMA : </b> ".$get_data['nama_pelanggan']."\n";
      $caption .= "<b>No.HP :</b> ".$get_data['no_hp']."\n";
      $caption .= "<b>TOTAL :</b> Rp. ".format_angka($get_data['total_belanja']).",-\n\n";
      $caption .= $pesannya;
      foreach (get_bot_group(5) as $key => $id_tele) {
        // SendMessage_tele($id_tele, $message);
        SendDocument_tele($id_tele, $caption, FCPATH.$file_path, $nama_file, 'HTML');
      }
      // $id_tele = get_bot_group(5, 'Order - JUNK_BOT');
      // SendDocument_tele($id_tele, $caption, FCPATH.$file_path, $nama_file, 'HTML');
      // Kirim ke WA
      $this->load->helper('api_sms');
  		$kirim = Send_file('wa', 'POST', $get_data['no_hp'], web('website').'/'.$file_path, $nama_file2);
  		if (json_decode("[$kirim]")[0]->sent) {
  			Send_message('wa', 'POST', $get_data['no_hp'], "Biar Kami Yang Belanja\n\n$pesannya");
  		}
      echo json_encode(array('stt'=>1, 'pesan'=>''));
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
    exit;
  }

  public function order_done($id='')
  {
    $stt = 2;
    if (!in_array($stt, array(2))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'order');
    $tbl = $this->tabel_order;

    $this->db->select("id_$tbl, total_belanja");
    $get_data = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($get_data)) { $this->ajax_failed(); }

    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');

    $real_payment = khususAngka(post('real_payment'));
    $gap     = $get_data['total_belanja'] - $real_payment;
    $benefit = preg_replace('/[Rp. ]/', '', post('benefit'));

    $where = array("id_$tbl"=>$id);
    $post  = array('real_payment'=>$real_payment, 'gap_real'=>$gap, 'benefit'=>$benefit, 'status'=>$stt, 'tgl_done'=>tgl_now(), 'done_by'=>$nama_input);
    $simpan = update_data($tbl, $post, $where);
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
    exit;
  }

}
?>
