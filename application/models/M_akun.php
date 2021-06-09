<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akun extends CI_Model
{
  var $tbl  = 'user';
  var $tbl2 = 'user_biodata';
  var $tbl3 = 'user_bank';
  var $tbl4 = 'user_sosmed';

  // Proses Lengkapi Data!
  public function proses_up_data($platform='')
  {
    if ($platform=='mobile') {
      $id_user = post('id_user');
      $un_lama = post('un_lama');
      $level   = post('level');
    }else {
      $id_user = get_session('id_user');
      $un_lama = get_session('username');
      $level   = get_session('level');
    }
    $pesan='';

    $tbl_biodata = $this->tbl2;
    if ($level==1) {
      $tbl_biodata .= '_mitra';
    }elseif ($level==2) {
      $tbl_biodata .= '_reseller';
    }else {
      echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Silahkan coba lagi."));
      exit;
    }

    $email = post('email');

    if ($email!='' && $level!=0) {
      if (get_email($email, user('email', $id_user, $tbl_biodata))->num_rows()!=0) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Email <b>'$email'</b> sudah ada."));
        exit;
      }
    }

    if ($level==2) { //reseller
      $path= get_path_img($id_user).'/'.$id_user;
      createPath($path, 0777);
      upload_config($path,'5','jpeg|jpg|png|gif|bmp');
      $foto_lama = user('foto', $id_new, 'v_user');
      $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
      if (!empty($foto['pesan'])) {
				$stt=0; $pesan = $foto['pesan'];
			}else {
        $stt=1; $post['foto'] = $foto;
        $cek_resize = resizeImage($foto,$path);
				if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
      }
    }else {
      $stt=1;
    }

      if ($stt==1) {
        $this->db->trans_begin();
        $post['username'] = $un_lama;
        $simpan = update_data($this->tbl, $post, array('id_user'=>$id_user));
        if ($simpan) {
          if ($level!= 0) {
            $data = array('jenis_kelamin'=>post('jenis_kelamin'), 'email'=>strtolower(post('email')), 'alamat'=>post('alamat'), 'pekerjaan'=>post('pekerjaan'), 'alamat_pengantaran'=>post('alamat_pengantaran'), 'informasi_dari'=>post('informasi_dari'));
            $simpan2 = update_data($tbl_biodata, $data, array('id_user'=>$id_user));
            if (!$simpan2) { $stt=0; }
            else {
              if ($level==1) {
                $post_bank['id_bank'] = post('id_bank');
                $post_bank['nama']    = post('nama');
                $post_bank['no_rek']  = post('no_rek');
                $post_bank['id_user'] = $id_user;
                $post_bank['tgl_input']  = tgl_now();
                $simpan3 = add_data($this->tbl3, $post_bank);
                if (!$simpan3) { $stt=0; }
              }
              if ($level==2) {
                $id_paket = decode(post('paket'));
                $this->db->select('id_paketnya, paket, jenis, qty, pcs, unit, free_qty, harga, paketnya');
                $paketnya = get_field('paketnya', array('id_paketnya'=>$id_paket));
                if (empty($paketnya)) {
                  $stt = 0; $pesan='Paket tidak valid!';
                }else {
                  $jumlah = abs(post('jumlah'));
                  $KU_harga = substr(khususAngka(post('harga')), -3);
                  $dt_order = array(
                    'no_transaksi' => 'O',
                    'id_user'  => $id_user,
                    'id_paket' => $paketnya['id_paketnya'],
                    'paket'    => $paketnya['paket'],
                    'jenis'    => $paketnya['jenis'],
                    'qty'      => $paketnya['qty'],
                    'pcs'      => $paketnya['pcs'],
                    'jenis_satuan' => $paketnya['unit'],
                    'free_qty' => $paketnya['free_qty']*$jumlah,
                    'harga'    => $paketnya['harga'],
                    'jumlah'   => $jumlah,
                    'kode_unik_harga' => $KU_harga,
                    'total_harga' => ($paketnya['harga']*$jumlah)+$KU_harga,
                    'catatan'  => $paketnya['paketnya'],
                    'status'   => '0',
                    'pembayaran_status' => 'Belum Lunas',
                    'pembayaran_persen' => 0,
                    'tgl_input' => tgl_now()
                  );
                  $simpan_ORDER = add_data('order', $dt_order);
                  if (!$simpan_ORDER) {
                    $stt=0;
                  }else {
                    $data_SOSMED = array('facebook', 'instagram', 'twitter');
                    for ($i=0; $i <=2; $i++) {
                      if (post('sosmed'.$i)!='') {
                        $simpan3 = add_data($this->tbl4, array('sosmed'=>$data_SOSMED[$i], 'url'=>post('sosmed'.$i), 'id_user'=>$id_user, 'tgl_input'=>tgl_now()) );
                        if (!$simpan3) { $stt=0; }
                      }
                    }
                  }
                }
              }
            }
          }else{
            $stt=0;
          }
        }else {
          $stt=0;
        }
      }

      $UB = 'v_'.$tbl_biodata;
      if ($stt==1) {
        $jk = user('jenis_kelamin', $id_user, $UB);
        if ($jk=='') { $stt = 0; }
      }

      if ($stt == 0) {
        $this->db->trans_rollback();
        if (file_exists($foto)) { unlink($foto); }
        if ($pesan=='') {
          $pesan = "Gagal disimpan, silahkan coba lagi!";
        }
        SendMessage_tele('713398862', "Error Update user Meeju - ".$pesan);
      }elseif ($stt == 1) {
        $this->db->trans_commit();
        $this->Bot_ketele($UB, $id_user, $level);
        // - Send WA
        $this->load->helper("api_sms");
        if ($level==1) {
          $send_to = 'Mitra';
        }else {
          $send_to = 'Reseller';
        }
        $pesan_ke_user = get_field('set_notif_gate', array('nama'=>$send_to))['pesan'];
        Send_message('wa', 'POST', $un_lama, $pesan_ke_user);
        // - Send WA
        $pesan = "Selamat bergabung bersama MEEJU INDONESIA";
        if ($foto != $foto_lama) {
          if (file_exists($foto_lama)) { unlink($foto_lama); }
        }
      }
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;
  }


  public function Bot_ketele($UB='', $id_user='', $level='')
  {
    if($UB==''){ return ''; }
    $namanya='';
    $GO = get_field($UB, array('id_user'=>$id_user, 'level'=>$level));
    if ($level==1) {
      $namanya = 'Mitra '.$GO['type_id'];
      $ID_BOT  = get_bot_group(1, 'Register Mitra');
    }elseif ($level==2) {
      $namanya = 'Reseller';
      $ID_BOT  = get_bot_group(1, 'Register Reseller');
    }
    $pesan_tele  = "<b>New $namanya Success Register!</b>\n";
    $pesan_tele .= hari_id(date('Y-m-d')).", ".tgl_id(tgl_format(tgl_now(),'d-m-Y H:i:s'))."\n";
    $pesan_tele .= "\n";
    $pesan_tele .= "<b>Rincian Pendaftaran </b>\n";
    $pesan_tele .= "<b>ID $namanya :</b> ".$GO['id_mitra']."\n";
    $pesan_tele .= "<b>Nama Lengkap :</b> ".$GO['nama_lengkap']."\n";
    $pesan_tele .= "<b>No. HP :</b> ".$GO['no_hp']."\n";
    // $pesan_tele .= "<b>Password :</b> ".decode($GO['password'])."\n";
    $pesan_tele .= "<b>Provinsi :</b> ".get_name_provinsi($GO['id_provinsi'])."\n";
    $pesan_tele .= "<b>Kota :</b> ".get_name_kota($GO['id_kota'])."\n";
    $pesan_tele .= "<b>Jenis Kelamin :</b> ".$GO['jenis_kelamin']."\n";
    $email = $GO['email'];
    if ($email=='') { $email = '-'; }
    $pesan_tele .= "<b>Email :</b> ".strtolower($email)."\n";
    $pesan_tele .= "<b>Alamat Tinggal :</b> ".$GO['alamat']."\n";
    $pesan_tele .= "<b>Pekerjaan :</b> ".$GO['pekerjaan']."\n";

    $ip = $this->input->ip_address();
    if ($level==2) { //reseller
      $pesan_tele .= "<b>Alamat Pengantaran :</b> ".$GO['alamat_pengantaran']."\n";
      $pesan_tele .= "<b>Informasi Dari :</b> ".$GO['informasi_dari']."\n";
      $pesan_tele .= "<b>IP :</b> ".$ip."\n";
    }
    $id_mitranya = $GO['id_referal'];
    if (!in_array($id_mitranya, array('', null))) {
      $this->db->select('type_id');
      $n = get_field('user_biodata_mitra', array('id_mitra'=>$id_mitranya))['type_id'];
      if (!empty($n)) {
        $pesan_tele .= "\n";
        $pesan_tele .= "<b>Mitra $n:</b> ".get_name_mitra($id_mitranya)."\n";
        $pesan_tele .= "<b>ID Mitra $n:</b> ".$id_mitranya."\n";
        $pesan_tele .= "\n";
      }
    }
    if ($level!=2) {
      $pesan_tele .= "<b>IP :</b> ".$ip."\n";
    }
    SendMessage_tele($ID_BOT, $pesan_tele);
  }


  // Proses Profile!
  public function proses_profile($platform='')
  {
    if (!check_permission('view', 'update', 'users/profile')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }

    if ($platform=='mobile') {
      $id_user = post('id_user');
      $un_lama = post('un_lama');
      $level   = post('level');
    }else {
      $id_user = get_session('id_user');
      $un_lama = get_session('username');
      $level   = get_session('level');
    }
    $pesan='';
    $nama_lengkap = post('nama_lengkap');
    $username 		= post('username');
    $email 		    = post('email');

    if ($level!=0) {
      if (get_email($email, user('email', $un_lama, 'v_user'))->num_rows()!=0) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Email <b>'$email'</b> sudah ada."));
        exit;
      }
    }

    if (get_un($username, $un_lama)->num_rows()!=0) {
      $nm = CP_log('nama', $level);
      echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! $nm <b>'$username'</b> sudah ada."));
      exit;
    }

      $path='img/user';
      upload_config($path,'5','jpeg|jpg|png|gif|bmp');
      $foto_lama = user('foto', $un_lama, 'v_user');
      $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
      if (!empty($foto['pesan'])) {
				$stt=0; $pesan = $foto['pesan'];
			}else {
        $stt=1; $post['foto'] = $foto;
        $cek_resize = resizeImage($foto,$path);
				if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
      }
      if ($stt==1) {
        $this->db->trans_begin();
        $post['username'] = $username;
        $simpan = update_data($this->tbl, $post, array('id_user'=>$id_user));
        if ($simpan) {
          if ($level!= 0) {
            $tbl_biodata = $this->tbl2;
            if ($level==1) {
              $tbl_biodata .= '_mitra';
            }elseif ($level==2) {
              $tbl_biodata .= '_reseller';
            }
            $data = array('nama_lengkap'=>post('nama_lengkap'), 'jenis_kelamin'=>post('jenis_kelamin'), 'email'=>post('email'), 'alamat'=>post('alamat'), 'pekerjaan'=>post('pekerjaan'), 'no_hp'=>$username);
            $simpan2 = update_data($tbl_biodata, $data, array('id_user'=>$id_user));
            if (!$simpan2) { $stt=0; }
            else {
              if ($level==1) {
                $post_bank['id_bank'] = post('id_bank');
                $post_bank['nama']    = post('nama');
                $post_bank['no_rek']  = post('no_rek');
                $post_bank['id_user'] = $id_user;
                $post_bank['tgl_input']  = tgl_now();
                if (empty(get($this->tbl3, array('id_user'=>$id_user, 'id_bank'=>post('id_bank')))->row())) {
                  $simpan3 = add_data($this->tbl3, $post_bank);
                }else {
                  $simpan3 = update_data($this->tbl3, $post_bank, array('id_user'=>$id_user));
                }
                if (!$simpan3) { $stt=0;}
              }
              if ($level==2) {
                $data_SOSMED = array('facebook', 'instagram', 'twitter');
                for ($i=0; $i <=2; $i++) {
                  $simpan3 = update_data($this->tbl4, array('url'=>post('sosmed'.$i)) , array('id_user'=>$id_user, 'sosmed'=>$data_SOSMED[$i]) );
                  if (!$simpan3) { $stt=0; break; }
                }
              }
            }
          }
        }else {
          $stt=0;
        }
        $pesan = "Berhasil disimpan.";
      }

      if ($stt == 0 && $pesan=='') {
        $this->db->trans_rollback();
        if (file_exists($foto)) { unlink($foto); }
        $pesan = "Gagal disimpan, silahkan coba lagi!";
      }elseif ($stt == 1) {
        $this->db->trans_commit();
        if ($foto != $foto_lama) {
          if (file_exists($foto_lama)) { unlink($foto_lama); }
        }
        set_session('username', "$username");
      }
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;

  }

  // Proses Reset Password!
  public function proses_reset_password($platform='')
  {
    if (!check_permission('view', 'update', 'users/reset_password')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    if ($platform=='mobile') {
      $id_user = post('id_user');
      $un = post('username');
    }else {
      $id_user = get_session('id_user');
      $un = get_session('username');
    }
    $password1 = post('password1');
    $password2 = post('password2');
    $password3 = post('password3');

    $cek_data = get_un($un)->row();
    if (decode($cek_data->password) <> $password1) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Password Lama tidak cocok."));
      exit;
    }else {
      if ($password2 <> $password3) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Konfirmasi Password Baru tidak cocok."));
        exit;
      }
      $data = array('password'=> encode($password2));
      // update_data($this->tbl, $data, array('id_user'=>$id_user));
      update_data($this->tbl, $data, array('username'=>$un));
      echo json_encode(array("stt"=>1, 'pesan'=>"Berhasil disimpan."));
      exit;
    }
  }


// RESELLER ======================
// Proses Lengkapi Data!
  public function proses_add_mitra_reseller($platform='')
  {
    if ($platform=='mobile') {
      $id_user = post('id_user');
      $un_lama = post('un_lama');
      $level   = post('level');
    }else {
      $id_user = get_session('id_user');
      $un_lama = get_session('username');
      $level   = get_session('level');
    }
    $pesan='';

    $post=array();
    foreach (get('user', array('id_user'=>$id_user))->result_array() as $key => $value) {
      unset($value['id_user']);
      $post = $value;
    }
    $post['level'] = 2;
    $post['tgl_input'] = tgl_now();

    $this->db->trans_begin();
    $simpan = add_data('user', $post);
    if ($simpan) {
      $id_new = $this->db->insert_id();
      $stt=1;
      $path= get_path_img($id_new).'/'.$id_new;
      createPath($path, 0777);
      upload_config($path,'5','jpeg|jpg|png|gif|bmp');
      $foto_lama = user('foto', $id_new, 'v_user');
      $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
      if (!empty($foto['pesan'])) {
        $stt=0; $pesan = $foto['pesan'];
      } else {
        $stt=1;
        $cek_resize = resizeImage($foto,$path);
        if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
        $simpan = update_data('user', array('foto'=>$foto), array('id_user'=>$id_new));
        if (!$simpan) {
          $stt=0;
        }
      }

      if ($stt==1) {
        $tbl_biodata = $this->tbl2;
        $id_mitra = get_nomor('R');
        if ($id_mitra=='') {
          $stt=0; $pesan='<b>Gagal!</b> coba CLEAR CACHE Browsernya...';
        }else {

          $tbl_biodata .= '_reseller';
          $post_reseller=array(); $id_referal='';
          foreach (get('user_biodata_mitra', array('id_user'=>$id_user))->result_array() as $key => $value) {
            unset($value['id_user_biodata']);
            $post_reseller = $value;
            $id_referal = $value['id_mitra'];
          }
          if (get_session('type_id')==1) {
            $typenya = 3;
          }elseif (get_session('type_id')==2) {
            $typenya = 4;
          }else {
            del_all_session();
            echo json_encode(array("stt"=>0, 'pesan'=>'Session Berakhir! Silahkan Refresh Halaman & login kembali, Terimakasih'));
            exit;
          }
          $post_reseller['type_id'] = $typenya;
          $post_reseller['id_user'] = $id_new;
          $post_reseller['alamat_pengantaran'] = post('alamat_pengantaran');
          $post_reseller['informasi_dari'] = 'Grup Whatsapp';
          $post_reseller['id_mitra'] = $id_mitra;
          $post_reseller['id_referal'] = $id_referal;
          $simpan2 = add_data($tbl_biodata, $post_reseller);
          if (!$simpan2) { $stt=0; }
          else {
              $stt_P=1;
              $this->db->select('id_paketnya, paket, jenis, qty, pcs, unit, free_qty, harga, paketnya');
              $paketnya = get_field('paketnya', array('id_paketnya'=>decode(post('paket'))));
              if (empty($paketnya)) { $stt_P=0; }
              if ($paketnya['paket']=='P') { $stt_P=0; }
              if ($stt_P==0) {
                $this->db->trans_rollback();
                if (file_exists($foto)) { unlink($foto); }
                $stt = 0; $pesan='Paket tidak valid!';
                echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
                exit;
              }
              $jumlah = abs(post('jumlah'));
              $KU_harga = substr(khususAngka(post('harga')), -3);
              $dt_order = array(
                'no_transaksi' => 'O',
                'id_user'  => $id_new,
                'id_paket' => $paketnya['id_paketnya'],
                'paket'    => $paketnya['paket'],
                'jenis'    => $paketnya['jenis'],
                'qty'      => $paketnya['qty'],
                'pcs'      => $paketnya['pcs'],
                'jenis_satuan' => $paketnya['unit'],
                'free_qty' => $paketnya['free_qty']*$jumlah,
                'harga'    => $paketnya['harga'],
                'jumlah'   => $jumlah,
                'kode_unik_harga' => $KU_harga,
                'total_harga' => ($paketnya['harga']*$jumlah)+$KU_harga,
                'catatan'  => $paketnya['paketnya'],
                'status'   => '0',
                'pembayaran_status' => 'Belum Lunas',
                'pembayaran_persen' => 0,
                'tgl_input' => tgl_now()
              );
              $simpan_ORDER = add_data('order', $dt_order);
              if (!$simpan_ORDER) {
                $stt=0;
              }else {
                $data_SOSMED = array('facebook', 'instagram', 'twitter');
                for ($i=0; $i <=2; $i++) {
                  $simpan3 = add_data($this->tbl4, array('sosmed'=>$data_SOSMED[$i], 'url'=>post('sosmed'.$i), 'id_user'=>$id_new, 'tgl_input'=>tgl_now()) );
                  if (!$simpan3) { $stt=0; }
                }
                $this->db->select('id_mitra');
                $id_mitra = get_field('user_biodata_reseller', array('id_user'=>$id_new))['id_mitra'];
                if (get('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra))->num_rows() == 0) {
                  $simpan4 = add_data('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra, 'tgl_input'=>tgl_now()));
                  if (!$simpan4) {
                    $stt=0; //Gagal simpan referal
                  }
                }
              }
          }

        }
      }

    }else {
      $stt=0;
    }

      if ($stt == 0 && $pesan=='') {
        $this->db->trans_rollback();
        if (file_exists($foto)) { unlink($foto); }
        $pesan = "Gagal disimpan, silahkan coba lagi!";
      }elseif ($stt == 1) {
        $this->db->trans_commit();
        $this->Bot_ketele('v_user_biodata_reseller', $id_new, 2);
        $pesan = "Pendaftaran RESELLER berhasil";
        if ($foto != $foto_lama) {
          if (file_exists($foto_lama)) { unlink($foto_lama); }
        }
      }
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;
  }



  public function proses_simpan_foto($id='', $platform='')
  {
    if ($platform=='mobile') {
      if($id==''){ $id = post('id_user'); }
    }else {
      if($id==''){
        $id = get_session('id_user');
      }else {
        $id = decode($id);
      }
    }
		if (isset($_POST)) {
			$tbl  = "user"; //user
      $this->db->select('foto, level');
			$data_lama = get_field($tbl, array('id_user'=>$id));
			$level = $data_lama['level'];

      $path= get_path_img($id).'/'.$id;
      createPath($path, 0755);
      upload_config($path,'5','jpeg|jpg|png|gif|bmp');
      $foto_lama = $data_lama['foto'];
      $foto = upload_file('foto', $path, 'ajax', $foto_lama, 1);
      if (!empty($foto['pesan'])) {
				$stt=0; $pesan = $foto['pesan'];
			}else {
        $stt=1; $post['foto'] = $foto;
        $cek_resize = resizeImage($foto,$path);
				if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
      }

      if ($stt==0) {
        echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
  			exit;
      }
			// log_r($post);
			$where_user = array("id_$tbl"=>$id);
			$simpan = update_data($tbl,$post, $where_user);
			if ($simpan) {
				$this->db->trans_commit();
        if ($foto != $foto_lama) {
          if (file_exists($foto_lama)) { unlink($foto_lama); }
        }
				$stt=1; $pesan='Berhasil Upload Foto';
			}else {
				$this->db->trans_rollback();
        if (file_exists($foto)) { unlink($foto); }
				$stt=0; $pesan='Gagal, silahkan coba lagi!';
      }
			echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
			exit;
    }
  }


  // Proses Login Mitra - Reseller!
  public function proses_cek_login_mitra_reseller($id='')
  {
    $id_user = decode($id);
    $password = post('password');
    $this->db->select('password, nama_lengkap, username, level, id_kota, id_mitra, type_id');
    $cek_data = get('v_user', array('id_user'=>$id_user));
    $stt=1; $pesan='';
    $pesan = "<b>Password salah</b>";
    if ($cek_data->num_rows() == 0) {
      $stt   = 0;
    }else {
      $row = $cek_data->row();
      // if (decode($row->password) != $password) {
      //   $stt = 0;
      // }
      if ($password <> decode($row->password)) {
        $this->db->select('password');
        $pass_admin = get('user', array('level'=>'0', 'status'=>'1'));
        $stt_X = 0;
        foreach ($pass_admin->result() as $key => $value) {
          if ($password <> decode($value->password)) {
            $stt_X++;
          }else {
            $stt_X = 0; break;
          }
        }
        if ($stt_X >= 1) {
          $pesan = "Password salah";
          $stt   = 0;
        }
      }
    }
    if ($stt == 1) {
      $nm  = $row->nama_lengkap;
      $time_exp = '0'; //unlimited
      $this->session->sess_expiration = $time_exp;
      set_session('id_user', "$id_user");
      set_session('username', "$row->username");
      set_session('level', "$row->level");
      set_session('id_kota', $row->id_kota);
      set_session('id_mitra', $row->id_mitra);
      set_session('type_id', $row->type_id);
      $pesan = "Selamat Datang ".$nm.", Selamat beraktifitas :)";
      pesan('success','msg_dashboard','',$pesan,'ajax');
    }

    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }


  public function proses_update_data($id_user='', $level='')
  {
    $id_user = decode($id_user);
    if (in_array($level, array(1))) {
      $anwarsptr = check_permission('view', 'update', 'user_mitra/v/'.find_id_user_get_type_id($id_user));
    }else {
      $anwarsptr = check_permission('view', 'update', 'user_reseller');
    }
    if (!$anwarsptr) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    $pesan='';
    $email = post('email');
    // $no_hp = post('no_hp');

    $stt=1;

    $tbl_biodata = $this->tbl2;
    if ($level==1) {
      $tbl_biodata .= '_mitra';
    }elseif ($level==2) {
      $tbl_biodata .= '_reseller';
    }else {
      echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Silahkan coba lagi..."));
      exit;
    }

    if ($email!='' && $level!=0) {
      if (get_email($email, user('email', $id_user, $tbl_biodata))->num_rows()!=0) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Gagal! Email <b>'$email'</b> sudah ada."));
        exit;
      }
    }

      if ($stt==1) {
        $this->db->trans_begin();
        // $post['username'] = $no_hp;
        // $simpan = update_data($this->tbl, $post, array('id_user'=>$id_user));
        // if ($simpan) {
          if ($level!= 0) {
              $data_X['nama_lengkap']  = post('nama_lengkap');
              $data_X['id_provinsi']   = post('id_provinsi');
              $data_X['id_kota']       = post('id_kota');
              $data_X['jenis_kelamin'] = post('jenis_kelamin');
              $data_X['email']         = strtolower(post('email'));
              $data_X['alamat']        = post('alamat');
              $data_X['pekerjaan']     = post('pekerjaan');
              $data_X['alamat_pengantaran'] = post('alamat_pengantaran');
              $data_X['informasi_dari'] = post('informasi_dari');
              if (get_session('level')==0) {
                $data_X['id_referal']    = post('id_referal');
              }
            $simpan2 = update_data($tbl_biodata, $data_X, array('id_user'=>$id_user));
            if (!$simpan2) { $stt=0; }
            else {
              if ($level==1) {
                $post_bank['id_user'] = $id_user;
                $post_bank['id_bank'] = post('id_bank');
                $post_bank['nama']    = post('nama');
                $post_bank['no_rek']  = post('no_rek');
                $this->db->select('id_user');
                if (get($this->tbl3, array('id_user'=>$id_user))->num_rows()==0) {
                  $post_bank['tgl_input'] = tgl_now();
                  $simpan3 = add_data($this->tbl3, $post_bank);
                }else {
                  $simpan3 = update_data($this->tbl3, $post_bank, array('id_user'=>$id_user));
                }
                if (!$simpan3) { $stt=0; }
              }
              if ($level==2) {
                    $tbl_sosmed = $this->tbl4;
                    $data_SOSMED = array('facebook', 'instagram', 'twitter');
                    for ($i=0; $i <=2; $i++) {
                      $url_sosmed = post('sosmed'.$i);
                      $where_sosmed = array('sosmed'=>$data_SOSMED[$i], 'id_user'=>$id_user);
                      if ($url_sosmed!='') {
                        $post_sosmed  = array('sosmed'=>$data_SOSMED[$i], 'url'=>$url_sosmed, 'id_user'=>$id_user, 'tgl_input'=>tgl_now());
                        if (get($tbl_sosmed, $where_sosmed)->num_rows()==0) {
                          $simpan3 = add_data($tbl_sosmed, $post_sosmed);
                        }else {
                          $simpan3 = update_data($tbl_sosmed, $post_sosmed, $where_sosmed);
                        }
                      }else {
                        $simpan3 = delete_data($tbl_sosmed, $where_sosmed);
                      }
                      if (!$simpan3) { $stt=0; }
                    }
              }
            }
          }else{
            $stt=0;
          }
        // }else {
        //   $stt=0;
        // }
      }

      $namanya='';
      $UB = $tbl_biodata;
      if ($stt==1) {
        $jk = user('jenis_kelamin', $id_user, $UB);
        if ($jk=='') { $stt = 0; }

        $tbl_spesial = 'user_harga_spesial';
        if (post('paket_khusus')==1) {
          $this->db->select('id_user');
          $get_spesial = get($tbl_spesial, array('id_user'=>$id_user))->row();
          $post_spesial = array(
            'id_user' => $id_user,
            'input_by' => get_session('username'),
            'ip' => $this->input->ip_address(),
            'tgl_input' => tgl_now(),
          );
          if (empty($get_spesial)) {
            $save = add_data($tbl_spesial, $post_spesial);
          }else {
            $save = update_data($tbl_spesial, $post_spesial, array('id_user'=>$id_user));
          }
        }else{
          $save = delete_data($tbl_spesial, array('id_user'=>$id_user));
        }
        if ($save) { $stt=1; }else{ $stt=0; }

      }

      if ($stt == 0) {
        $this->db->trans_rollback();
        // if (file_exists($foto)) { unlink($foto); }
        if ($pesan=='') {
          $pesan = "Gagal disimpan, silahkan coba lagi!";
        }
      }elseif ($stt == 1) {
        $this->db->trans_commit();
        $pesan = "Berhasil disimpan";
        // if ($foto != $foto_lama) {
        //   if (file_exists($foto_lama)) { unlink($foto_lama); }
        // }
      }
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;
  }


  function proses_send_bc_info()
  {
    if (!check_permission('view', 'create', 'users/bc_info')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }

    $data_select = array_unique(json_decode(html_entity_decode($this->input->post('via'))));
    if (empty($data_select)) {
      echo json_encode(array('stt'=>0, 'pesan'=>'Kirim Via Belum dipilih!'));
      exit;
    }
    $select_user = array_unique(json_decode(html_entity_decode($this->input->post('id_user'))));
    $jenis   = post('jenis');
    $no_hp   = post('no_hp');
    $message = post('pesan');
    $via     = array('sms','wa');
    $this->load->helper("api_sms");
    $status = 0; $data_kirim=array(); $tbl_nya='';
    if ($jenis==0) {
      $data_kirim[] = $no_hp;
    }elseif (in_array($jenis, array(1,2,3))) {
      if (empty($select_user)) {
        $this->db->select('no_hp');
        if ($jenis==1) {
          $tbl_nya = 'v_user_biodata_mitra';
          $this->db->where('type_id', $jenis);
        }elseif ($jenis==2) {
          $tbl_nya = 'v_user_biodata_mitra';
          $this->db->where('type_id', $jenis);
        }elseif ($jenis==3) {
          $tbl_nya = 'v_user_biodata_reseller';
        }
        if ($tbl_nya=='') {
          echo json_encode(array('stt'=>0, 'pesan'=>'Gagal kirim, silahkan hubungi IT!'));
          exit;
        }
        $get_user = get($tbl_nya, array('status'=>'1'));
        foreach ($get_user->result() as $key => $value) {
          $data_kirim[] = $value->no_hp;
        }
      }else {
        foreach ($select_user as $key2 => $value2) {
          $data_kirim[] = $value2;
        }
      }
    }else {
      $id_group = explode('-', $jenis)[1];
      $this->db->select('no_hp');
      foreach (get('bc_info', array('id_bc_info_group'=>$id_group))->result() as $key => $value) {
        $data_kirim[] = $value->no_hp;
      }
      if (empty($data_kirim)) {
        echo json_encode(array('stt'=>0, 'pesan'=>'Tidak ditemukan adanya No HP di Group ini, silahkan ditambahkan terlebih dahulu, Terimakasih!'));
        exit;
      }
    }
    foreach ($data_select as $key => $value) {
      $send = $via[$value];
      if (in_array($send, array('', null))) {
        continue;
      }
      foreach ($data_kirim as $key => $value) {
        $kirim = Send_message($send, 'POST', $value, $message, '1');
        if ($kirim=='') {
          echo json_encode(array('stt'=>0, 'pesan'=>'Gagal, silahkan hubungi IT!'));
          exit;
        }else{
          $status = 0;
          $json = "[$kirim]";
          $arr  = json_decode($json, true)[0];
          if ($arr["status"]) { $status = 1; }
          if($status==0){ break; }
        }
      }
    }
    if ($status==1) {
      $pesan = 'Berhasil dikirim';
    }else {
      $pesan = 'Gagal kirim';
    }
    echo json_encode(array('stt'=>$status, 'pesan'=>$pesan));
    exit;
  }


  public function proses_edit_fee($id='')
  {
    $tbl = 'user_fee'; $pesan='';
    $max_fee = max_fee();
    $id = decode($id);
    $post['fee_master'] = khususAngka(post($id.'_in'));
    $post['fee_child']  = khususAngka(post($id.'_out'));
    if (get_session('level')==0) {
      $post['tgl_update'] = tgl_now();
    }else{
      $post['tgl_edit'] = tgl_now();
    }
    $total = $post['fee_master'] + $post['fee_child'];
    if ($total > $max_fee) {
      $simpan = false; $pesan = 'Fee tidak boleh melebihi '.format_angka($max_fee);
    }else {
      $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
    }
    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='Fee berhasil di edit';
    }else {
      $this->db->trans_rollback();
      $stt=0; if ($pesan==''){ $pesan='Gagal Simpan, silahkan coba lagi!'; }
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

}
?>
