<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model
{

  // public function proses_Send_aja($CP='', $status='')
  // {
  //   $NOMOR_AKTIVASI = get_nomor('1');
  //   $message = "JANGAN BERIKAN kode ini kepada siapapun, TERMASUK TIM MEEJU. WASPADA PENIPUAN! untuk melanjutkan pendaftaran, masukkan kode RAHASIA $NOMOR_AKTIVASI";
  //   $this->load->helper('api_sms');
  //   if ($CP=='') {
  //     $Send = 0;
  //   }else {
  //     if ($CP==1) {
  //       $Send = 1;//Send_WA(post('no_hp'), $message);
  //       set_session('sent_to', 'Whatsapp');
  //     }else {
  //       $Send = 1;//Send_SMS(post('no_hp'), $message);
  //       set_session('sent_to', 'SMS');
  //     }
  //   }
  //
  //   if ($status=='x') {
  //     return $Send;
  //   }else {
  //     if ($Send == '1') {
  //       $stt = 1; $pesan='';
  //       set_session('kode', encode($NOMOR_AKTIVASI));
  //     }else {
  //       $stt = 0; $pesan='Gagal Mengirim Aktivasi';
  //     }
  //     echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
  //     exit;
  //   }
  // }

  public function proses_cek_register($CP=1, $platform='')
  {
    $this->session->sess_expiration = 0;
    $username = post('no_hp');
    $cek_data = get_un($username);
    $referal  = post('id_referal');
    $get_X    = post('x');
    $pesan = '';
    if ($cek_data->num_rows() <> 0) {
      $stt = 1;
      if (get_block_USER($username, $get_X)) {
        $pesan = "<b>Gagal!</b> Nomor Handphone <b>'$username'</b> sudah ada, silahkan coba lagi.";
        $stt   = 0;
      }else {
        if (in_array($get_X, array(1,2))) {
          $this->proses_add_reseller_mitra($username, $referal, $get_X);
          exit;
        }
      }
    }else {
      if (cek_Referal($referal, 'ajax') == 0) {
  			echo json_encode(array("stt"=>0, 'pesan'=>'Not Found!'));
        exit;
      }
      if (get_type_id('ID', $get_X)=='') {
        echo json_encode(array("stt"=>0, 'pesan'=>'Not Found!'));
        exit;
      }
      $stt = 1;
    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }

  // Proses Lengkapi Data!
    public function proses_add_reseller_mitra($username='', $id_referal='', $get_X='')
    {
      $pesan='';
      $post=array();
      foreach (get('user', array('username'=>$username, 'level'=>'2'))->result_array() as $key => $value) {
        unset($value['id_user']);
        $post = $value;
      }
      $post['level'] = 1;
      $post['tgl_input'] = tgl_now();

      $this->db->trans_begin();
      $simpan = add_data('user', $post);
      $stt=1;
      if ($simpan) {
        $id_new = $this->db->insert_id();
        $tbl_biodata = 'user_biodata';
        $id_mitra = get_nomor('M');
        if ($id_mitra=='') {
          $stt=0; $pesan='<b>Gagal!</b> coba CLEAR CACHE Browsernya...';
        }else {
          $tbl_biodata .= '_mitra';
          $post_mitra=array();
          foreach (get('user_biodata_reseller', array('no_hp'=>$username))->result_array() as $key => $value) {
            unset($value['id_user_biodata']);
            $post_mitra = $value;
          }
            $post_mitra['type_id'] = $get_X;
            $post_mitra['id_user'] = $id_new;
            $post_mitra['id_mitra'] = $id_mitra;
            $post_mitra['id_referal'] = $id_referal;
            $simpan2 = add_data($tbl_biodata, $post_mitra);
            if (!$simpan2) { $stt=0; }
            else{
              $this->db->select('id_mitra');
              $id_mitra = get_field('user_biodata_reseller', array('id_user'=>$id_new))['id_mitra'];
              if (get('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra))->num_rows() == 0) {
                $simpan4 = add_data('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra, 'tgl_input'=>tgl_now()));
                if (!$simpan4) {
                  $stt=0; //Gagal simpan referal
                }
              }
            }

          if ($stt!=0) {
            $simpan5 = save_benefit($id_new);
            if (!$simpan5) { $stt=0; }
          }

        }
      }else {
        $stt=0;
      }

        if ($stt == 0 && $pesan=='') {
          $this->db->trans_rollback();
          $pesan = "Gagal disimpan, silahkan coba lagi!";
        }elseif ($stt == 1) {
          $this->db->trans_commit();
          $this->session->sess_expiration = 0;
          set_session('id_user', "$id_new");
          set_session('username', $username);
          set_session('level', "1");
          set_session('id_kota', $post_mitra['id_kota']);
          set_session('id_mitra', $id_mitra);
          set_session('type_id', $get_X);
          model('M_akun', 'Bot_ketele', 'v_user_biodata_mitra', $id_new, 1);
          $pesan = "Pendaftaran MITRA berhasil";
        }
        echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
        exit;
    }

  public function proses_konfirmasi_register($CP=1, $platform='')
  {
    $this->session->sess_expiration = 0;
    // $no_hp = decode(get_session('no_hp'));
    // $kode  = decode(get_session('kode'));
    $this->proses_cek_register($CP);
    // if ($kode == post('kode') && $no_hp == $CP) {
      // echo json_encode(array("stt"=>1, 'pesan'=>'Berhasil dikonfirmasi'));
    // }else {
    //   echo json_encode(array("stt"=>0, 'pesan'=>'Kode AKTIVASI tidak cocok, silahkan coba lagi!'));
    // }
    exit;
  }

  // Proses Register!
  public function proses_register($CP=1, $platform='')
  {
    if (dbnya()==$this->db->database) {
      // $view = true;
      $view = false;
    }else {
      $view = false;
    }
    $post_required = post_all('BtnReg');
		foreach ($post_required as $key => $value) {
      if (!in_array($key, array('id_referal','email','sosmed0','sosmed1','sosmed2'))) {
  			if ($value=='') {
          $nmnya = strtoupper(preg_replace('/[_]/',' ',$key));
          echo json_encode(array("stt"=>0, 'pesan'=>"Kolom '".$nmnya."' wajib diisi!"));
          exit;
        }
			}
		}

    if (is_numeric(post('nama_lengkap'))) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Nama Lengkap tidak boleh Angka"));
      exit;
    }

    if (!is_numeric(post('no_hp'))) {
      echo json_encode(array("stt"=>0, 'pesan'=>"No Handphone harus Angka"));
      exit;
    }

    $type_id  = post('x');
    $ID = get_type_id('ID', $type_id);
    // if ($ID=='') {
    //   echo json_encode(array("stt"=>0, 'pesan'=>$pesan='<b>ID tidak valid!</b> Silahkan CLEAR CACHE Browsernya...Terimakasih'));
    //   exit;
    // }
    if (!in_array($ID, array('M','R'))) {
      $stt=0; $pesan='<b>ID tidak valid!!</b> Silahkan CLEAR CACHE Browsernya...Terimakasih';
    }

    $id_referal = post('id_referal');

    $email    = post('email');
    $username = post('no_hp');
    $password = post('password');
    $cek_data = get_un($username);
    $stt=1; $pesan="<b>Gagal!</b> Silahkan coba lagi.";
    // cek username
    $add_duplicate_data = false;
    if ($cek_data->num_rows() <> 0) {
      $stt = 1;
      if (get_block_USER($username, $type_id)) {
        $pesan = "<b>Gagal!</b> Nomor Handphone <b>'$username'</b> sudah ada, silahkan coba lagi.";
        echo json_encode(array("stt"=>0, 'pesan'=>$pesan));
        exit;
      }else {
        if ($type_id==2) {
          $add_duplicate_data = true;
        }
      }
    }
    if ($email!='') {
      if (get_email($email)->num_rows() <> 0) {
        $pesan = "<b>Gagal!</b> Email <b>'$email'</b> sudah ada, silahkan coba lagi.";
        echo json_encode(array("stt"=>0, 'pesan'=>$pesan));
        exit;
      }
    }
        // if ($password <> post('password2')) {
        //   $pesan = "<b>Gagal!</b> Password tidak cocok.";
        //   $stt   = 0;
        // }

    $this->db->trans_begin();
    $id_mitra = get_nomor($ID);
    $simpan = false;
    if ($stt==1) {
      $tbl_b = 'user_biodata';
      if (in_array($type_id, get_type_id('mitra'))) {
        $level  = 1; //mitra
        $tbl_b .= '_mitra';
      }elseif (in_array($type_id, get_type_id('reseller'))) {
        $level  = 2; //reseller
        $tbl_b .= '_reseller';
      }else {
        $this->db->trans_rollback();
        echo json_encode(array("stt"=>0, 'pesan'=>"ID tidak valid!"));
        exit;
      }
      $post = array('username'=>$username, 'password'=>encode($password), 'level'=>$level, 'status'=>'1', 'mode'=>'0', 'tgl_input'=>tgl_now());
      $simpan = add_data('user', $post);
      if ($simpan) {
        $id_new = $this->db->insert_id();
        $id_user = $id_new;
        if ($view) {
          if ($level==2) {
            $path= get_path_img($id_user).'/'.$id_user;
            createPath($path, 0777);
            upload_config($path,'5','jpeg|jpg|png|gif|bmp');
            $foto_lama = user('foto', $id_new, 'v_user');
            $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
            if (!empty($foto['pesan'])) {
      				$stt=0; $pesan = $foto['pesan'];
      			}else {
              $stt=1; $post_user['foto'] = $foto;
              $cek_resize = resizeImage($foto,$path);
      				if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
            }
            $simpan = update_data('user', $post_user, array('id_user'=>$id_user));
          }
          $post2 = array('id_user'=>$id_new, 'nama_lengkap'=>post('nama_lengkap'), 'no_hp'=>post('no_hp'), 'jenis_kelamin'=>post('jenis_kelamin'), 'email'=>strtolower(post('email')), 'alamat'=>post('alamat'), 'pekerjaan'=>post('pekerjaan'), 'alamat_pengantaran'=>post('alamat_pengantaran'), 'informasi_dari'=>post('informasi_dari'), 'id_provinsi'=>post('id_provinsi'), 'id_kota'=>post('id_kota'), 'type_id'=>$type_id, 'id_mitra'=>$id_mitra, 'id_referal'=>$id_referal);
        }else {
          $post2 = array('id_user'=>$id_new, 'nama_lengkap'=>post('nama_lengkap'), 'no_hp'=>post('no_hp'), 'id_provinsi'=>post('id_provinsi'), 'id_kota'=>post('id_kota'), 'type_id'=>$type_id, 'id_mitra'=>$id_mitra, 'id_referal'=>$id_referal);
        }

        $simpan = add_data($tbl_b, $post2);
        if ($simpan) {
          if ($id_referal!='') {
            $id_master = find_nomor_get_id_user($id_referal);
            $id_child  = find_nomor_get_id_user($id_mitra);
            if (get('user_referal', array('no_master'=>$id_referal, 'no_child'=>$id_mitra))->num_rows() == 0) {
              $simpan = add_data('user_referal', array('id_master'=>$id_master, 'id_child'=>$id_child, 'no_master'=>$id_referal, 'no_child'=>$id_mitra, 'tgl_input'=>tgl_now()));
              if ($simpan){
                // if ($level==1) {
            			$data_fee = get_data_fee_arr($id_referal, $id_mitra, $id_master, $id_child);
                  $simpan = add_data('user_fee', $data_fee);
                // }
              }
            }
          }
        }

        if ($view) {
          if ($simpan) {
            if ($level==1) {
              $post_bank['id_bank'] = post('id_bank');
              $post_bank['nama']    = post('nama');
              $post_bank['no_rek']  = post('no_rek');
              $post_bank['id_user'] = $id_user;
              $post_bank['tgl_input']  = tgl_now();
              $simpan3 = add_data('user_bank', $post_bank);
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
                $simpan = add_data('order', $dt_order);
                if ($simpan) {
                  $data_SOSMED = array('facebook', 'instagram', 'twitter');
                  for ($i=0; $i <=2; $i++) {
                    if (post('sosmed'.$i)!='') {
                      $simpan = add_data('user_sosmed', array('sosmed'=>$data_SOSMED[$i], 'url'=>post('sosmed'.$i), 'id_user'=>$id_user, 'tgl_input'=>tgl_now()) );
                      if (!$simpan) { break; }
                    }
                  }
                }
              }
            }
          }
        }

      }
    }

    if ($level==1) {
      if ($simpan) {
        $simpan = save_benefit($id_user);
      }
    }

    if ($simpan) {
      $this->db->trans_commit();
      del_nomor($id_mitra);
      $this->session->sess_expiration = 0;
      set_session('id_user', "$id_new");
      set_session('username', "$username");
      set_session('level', "$level");
      set_session('id_mitra', "$id_mitra");
      set_session('type_id', $type_id);
      $pesan = "Selamat Datang ".$username.", Selamat beraktifitas :)";
      if ($view) {
        model('M_akun', 'Bot_ketele', "v_$tbl_b", $id_user, $level);
      }
    }else {
      $this->db->trans_rollback();
      SendMessage_tele('713398862', "Error Regiter $tbl_b Meeju - ".$pesan);
    }

    if ($platform=='mobile') { $pesan = htmlentities(strip_tags($pesan)); }
    echo json_encode(array("stt"=>$stt,'status'=>"$stt", 'pesan'=>$pesan));
    exit;
  }

  // Proses Login!
  public function proses_login($CP=1, $platform='', $level='', $un='', $pass='')
  {
    $tag_akun = ''; $id_gudang_kota=''; $id_gudang='';
    $log = CP_log('nama',$CP);//cek form login
    if ($platform=='mobile') { //mobile
      $username = post('no_telp');
    }else { //web
      $username = post('no_hp');
    }
    $password = post('password');
    $get_reset = get_field('reset_password', array('password'=>$password, 'no_hp'=>$username));

    if ($CP=='M_admin' || $CP=='backend') {
      $id_usernya='';
      $this->db->select('id_user, username');
      $cek_data1 = get_un($username, '', 0)->row();
      if (empty($cek_data1)) {
        $this->db->select('no_hp, id_user');
        $cek_data2 = get_no_hp($username, '', 'user_biodata_management')->row();
        if (empty($cek_data2)) {
          echo json_encode(array("stt"=>0, 'pesan'=>"<b>Gagal!</b> Username atau Password salah!"));
          exit;
        }else {
          $id_usernya = $cek_data2->id_user;
          $this->db->select('username');
          $username = get_field('user', array('id_user'=>$id_usernya))['username'];
        }
      }else {
        $id_usernya = $cek_data1->id_user;
      }

      $this->db->select('id_gudang, id_gudang_kota');
  		$get_user = get('user_biodata_management', array('id_user'=>$id_usernya))->row();
      if (empty($get_user)) {
        $tag_akun = 'Admin';
      }else {
        if ($get_user->id_gudang!=0) {
    			$tag_akun = get_name_gudang($get_user->id_gudang). ' - '.get_name_gudang_kota($get_user->id_gudang_kota);
          $id_gudang_kota = $get_user->id_gudang_kota;
          $id_gudang = $get_user->id_gudang;
        }
      }

    }

    if (!empty($get_reset)) {
      update_data('user', array('password'=>encode($password)), array('username'=>$username));
      delete_data('reset_password', array('no_hp'=>$username));
    }

    if ($level!=''){ $CP=$level; }
    if ($un!=''){ $username=$un; }
    if ($pass!=''){ $password=$pass; }
    if (in_array($level, array(1,2))) {
      $CP=$level;
      $token = decode($un);
      // log_r($token);
      $get_ = get_field('user', array('id_user'=>$token));
      $username=$get_['username'];
      $password=decode($get_['password']);
    }
    $this->db->order_by('level', 'ASC');
    $cek_data = get_un($username, '', $CP);
    $stt=1; $pesan='';
    if ($cek_data->num_rows() == 0) {
      $pesan = "<b>Gagal!</b> $log <b>'$username'</b> tidak ditemukan, silahkan coba lagi.";
      $stt   = 0;
    }else {
      $row = $cek_data->row();
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
          $pesan = "<b>Gagal!</b> $log atau Password salah.";
          $stt   = 0;
        }
      }
    }

    if ($stt == 1) {
      $dt = get_field('v_user', array('username'=>$username));
      if ($dt['status']=='0') {
        $pesan = "<b>Akun Anda tidak aktif</b>, untuk mengaktifkan kembali silahkan hubungi Admin. Terimakasih!";
        echo json_encode(array("stt"=>0, 'pesan'=>$pesan));
        exit;
      }
      $level = $cek_data->level;
      $this->session->sess_expiration = 0;
      set_session('id_user', "$row->id_user");
      set_session('username', "$row->username");
      set_session('level', "$row->level");
      set_session('id_kota', $dt['id_kota']);
      set_session('id_mitra', $dt['id_mitra']);
      set_session('type_id', $dt['type_id']);
      set_session('tag_akun', $tag_akun);
      set_session('id_gudang_kota', $id_gudang_kota);
      set_session('id_gudang', $id_gudang);
      if ($row->level==0) {
        $nm = $username;
      }else {
        $nm = $dt['nama_lengkap'];
      }
      $pesan = "Selamat Datang ".$nm.", Selamat beraktifitas :)";
      pesan('success','msg_dashboard','',$pesan,'ajax');
    }

    if ($platform=='mobile') {
      if ($un!='') {
        $pesan = "Selamat Datang ".$nm.", Selamat beraktifitas :)";
        pesan('success','msg_dashboard','',$pesan,'dashboard');
      }
      if ($stt==1) {
        $data = array('status'=>1, 'pesan'=>$pesan,
          'nama'    => $dt['nama_lengkap'],
          'no_telp' => $username,
          'level'   => $dt['level'],
          'id_user' => $dt['id_user'],
          'jenis_akun' => $dt['jenis_akun'],
          'id_kota' => $dt['id_kota'],
          'token'   => encode($dt['id_user']),
          'id_mitra' => $dt['id_mitra'],
          'type_id' => $dt['type_id']
        );
      }else {
        $data = array('status'=>0, 'pesan'=>htmlentities(strip_tags($pesan)));
      }
      echo json_encode($data);
    } else {
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    }
    exit;
  }

  // Proses Formgot Password!
  public function proses_forgot_password($status='')
  {
    $no_hp = post('no_hp');
    $cek_data = get_un($no_hp);
    $stt=1; $pesan='';
    if ($cek_data->num_rows() == 0) {
      $pesan = "<b>Gagal!</b> Nomor Handphone <b>'$no_hp'</b> tidak ditemukan, silahkan coba lagi.";
      $stt   = 0;
    }else {
      $row = $cek_data->row();
    }
    if ($stt == 1) {
      $id_user = $row->id_user;
      $time = tgl_format(tgl_now(), 'Y-m-d H:i:s', '+1 days');
      $id   = encode("$id_user | $time", '64');
      $url  = web('website')."/auth/reset-password/$id";
      // $message = "Link di Reset hanya akan berlaku selama 1x24 jam setelah pesan ini Anda buat. silahkan klik link ini : $url";
      $pass_new = get_nomor('P');
      $this->load->helper('api_sms');
      if (in_array($status, array(1,2))) {
        $message  = "PASSWORD INI HANYA BERSIFAT SEMENTARA, PASSWORD BARU ANDA ADALAH : $pass_new";
        if ($status==1) {
          $namanya='Whatsapp'; $sendTo='wa';
        }else {
          $namanya='SMS'; $sendTo='sms';
        }
        Send_message($sendTo, 'POST', $no_hp, $message);
        $kirim = 1;
        $pesan = "Password BARU ANDA berhasil dikirim ke $namanya, silahkan cek Handphone Anda. Terimakasih!";
      }elseif ($status==3) {
        $pesan = "Password BARU ANDA berhasil dikirim ke Admin, silahkan tunggu informasi dari Admin. Terimakasih!";
        $kirim = 1;
      }else {
        $kirim = 0;
      }
      if ($kirim==1) {
        $this->db->select('nama_lengkap');
        $GO = get_field('v_user', array('id_user'=>$id_user));
        $pesan_tele = "<b>UPDATE PASSWORD</b>\n";
        $pesan_tele .= "Nama   : ".$GO['nama_lengkap']."\n";
        $pesan_tele .= "No. HP : ".$no_hp."\n";
        $pesan_tele .= "Password : $pass_new\n\n";
        $pesan_tele .= "*PASSWORD INI HANYA BERSIFAT SEMENTARA\n";
        if (in_array($status, array(1,2))) {
          $pesan_tele .= "\nReset via $namanya\n";
        }
        $ID_BOT  = get_bot_group(2, 'Reset Password');
        SendMessage_tele($ID_BOT, $pesan_tele);

        delete_data('reset_password', array('no_hp'=>$no_hp));
        add_data('reset_password', array('password'=>$pass_new, 'no_hp'=>$no_hp, 'tgl_input'=>tgl_now()));
        // $pesan = "Link Reset Password berhasil dikirim ke $namanya, silahkan cek Handphone Anda. Terimakasih!";
        del_nomor($pass_new);
        pesan('success','msg','',$pesan,'ajax');
      }else{
        $stt=0; $pesan="<b>Gagal!</b> Kirim ke <b>'$no_hp'</b>, silahkan coba lagi.";
      }
    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }

  // Proses Reset Password!
  public function proses_reset_password($id='')
  {
    $password1 = post('password1');
    $password2 = post('password2');
    if ($password1 <> $password2) {
      $stt=0; $pesan="Gagal! Konfirmasi Password Baru tidak cocok.";
      exit;
    }else {
      $data = array('password'=> encode($password1));
      // $simpan = update_data('user', $data, array('id_user'=>$id));
      $this->db->select('username');
      $un = get_field('user', array('id_user'=>$id))['username'];
      if (empty($un)) {
        $simpan = false;
      }else {
        $simpan = update_data($this->tbl, $data, array('username'=>$un));
      }
      if ($simpan) {
        $stt=1; $pesan="Password berhasil diperbaharui.";
        pesan('success','msg','',$pesan,'ajax');
      }else {
        $stt=0; $pesan="Gagal! Silahkan coba lagi.";
      }
    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }

}
?>
