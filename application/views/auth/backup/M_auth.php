<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_auth extends CI_Model
{

  public function proses_Send_aja($CP='', $status='')
  {
    $NOMOR_AKTIVASI = get_nomor('1');
    $message = "JANGAN BERIKAN kode ini kepada siapapun, TERMASUK TIM MEEJU. WASPADA PENIPUAN! untuk melanjutkan pendaftaran, masukkan kode RAHASIA $NOMOR_AKTIVASI";
    $this->load->helper('api_sms');
    if ($CP=='') {
      $Send = 0;
    }else {
      if ($CP==1) {
        $Send = Send_WA(post('no_hp'), $message);
        set_session('sent_to', 'Whatsapp');
      }else {
        $Send = Send_SMS(post('no_hp'), $message);
        set_session('sent_to', 'SMS');
      }
    }

    if ($status=='x') {
      return $Send;
    }else {
      if ($Send == '1') {
        $stt = 1; $pesan='';
        set_session('kode', encode($NOMOR_AKTIVASI));
      }else {
        $stt = 0; $pesan='Gagal Mengirim Aktivasi';
      }
      echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
      exit;
    }
  }

  public function proses_cek_register($CP=1, $platform='')
  {
    $this->session->sess_expiration = 0;
    $username = post('no_hp');
    $cek_data = get_un($username);
    if ($cek_data->num_rows() <> 0) {
      $pesan = "<b>Gagal!</b> Nomor Handphone <b>'$username'</b> sudah ada, silahkan coba lagi.";
      $stt   = 0;
    }else {
      $referal = post('id_referal');
      if (cek_Referal($referal, 'ajax') == 0) {
  			echo json_encode(array("stt"=>0, 'pesan'=>'Not Found!'));
        exit;
      }

      $get_X  = post('x');
      if ($get_X == 1 or $get_X == 2) {
        $ID = 'M';
      }elseif ($get_X == 3) {
        $ID = 'R';
      }else {
        echo json_encode(array("stt"=>0, 'pesan'=>'Not Found!'));
        exit;
      }

      $Send = $this->proses_Send_aja($CP, 'x');
      // $Send = 1;
      if ($Send == '1') {
        set_session('no_hp', encode(post('no_hp')));
        set_session('type', encode($ID));
        set_session('type_id', encode($get_X));
        set_session('id_referal', encode($referal));
        $stt = 1; $pesan='';
      }else {
        $stt = 0; $pesan='Gagal Mengirim Aktivasi';
      }

    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }

  public function proses_konfirmasi_register($CP=1, $platform='')
  {
    $this->session->sess_expiration = 0;
    $no_hp = decode(get_session('no_hp'));
    $kode  = decode(get_session('kode'));
    if ($kode == post('kode') && $no_hp == $CP) {
      echo json_encode(array("stt"=>1, 'pesan'=>'Berhasil dikonfirmasi'));
    }else {
      echo json_encode(array("stt"=>0, 'pesan'=>'Kode AKTIVASI tidak cocok, silahkan coba lagi!'));
    }
    exit;
  }

  // Proses Register!
  public function proses_register($CP=1, $platform='')
  {
    $post = post_all('BtnReg');
		foreach ($post as $key => $value) {
			if ($value=='') {
        $nmnya = strtoupper(preg_replace('/[_]/',' ',$key));
        echo json_encode(array("stt"=>0, 'pesan'=>"Kolom '".$nmnya."' wajib diisi!"));
				exit;
			}
		}
    $type_id  = decode(get_session('type_id'));
    $id_referal = decode(get_session('id_referal'));

    // $email    = post('email');
    $username = post('no_hp');
    $password = post('password');
    $cek_data = get_un($username);
    $stt=1; $pesan="<b>Gagal!</b> Silahkan coba lagi.";
    // cek username
    // if (get_email($email)->num_rows() <> 0) {
    //   $pesan = "<b>Gagal!</b> Email <b>'$email'</b> sudah ada, silahkan coba lagi.";
    //   $stt   = 0;
    // }else {
      if ($cek_data->num_rows() <> 0) {
        $pesan = "<b>Gagal!</b> Nomor Handphone <b>'$username'</b> sudah ada, silahkan coba lagi.";
        $stt   = 0;
      }else {
        // if ($password <> post('password2')) {
        //   $pesan = "<b>Gagal!</b> Password tidak cocok.";
        //   $stt   = 0;
        // }
      }
    // }

    $this->db->trans_begin();

    $id_mitra = get_nomor(decode(get_session('type')));
    if ($id_mitra=='') {
      $stt=0; $pesan='<b>Gagal!</b> coba CLEAR CACHE Browsernya...';
    }
    if ($stt==1) {
      $tbl_b = 'user_biodata';
      if ($type_id==3) {
        $level  = 2; //reseller
        $tbl_b .= '_reseller';
      }else {
        $level  = 1; //mitra
        $tbl_b .= '_mitra';
      }
      $post = array('username'=>$username, 'password'=>encode($password), 'level'=>$level, 'status'=>'1', 'mode'=>'0', 'tgl_input'=>tgl_now());
      $simpan = add_data('user', $post);
      if ($simpan) {
        $id_new = $this->db->insert_id();
        $post2 = array('id_user'=>$id_new, 'nama_lengkap'=>post('nama_lengkap'), 'no_hp'=>post('no_hp'), 'id_provinsi'=>post('id_provinsi'), 'id_kota'=>post('id_kota'), 'type_id'=>$type_id, 'id_mitra'=>$id_mitra, 'id_referal'=>$id_referal);
        $simpan2 = add_data($tbl_b, $post2);
        if ($simpan2) {
          if ($id_referal!='') {
            if (get('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra))->num_rows() == 0) {
              $simpan3 = add_data('user_referal', array('id_master'=>$id_referal, 'id_child'=>$id_mitra, 'tgl_input'=>tgl_now()));
              if (!$simpan3) {
                $stt=0; //Gagal simpan referal
              }
            }
          }
        }else {
          $stt=0; //Gagal simpan user & user_biodata
        }
      }else {
        $stt=0;
      }
    }

    if ($stt==1) {
      $this->db->trans_commit();
      del_nomor($id_mitra);
      $this->session->sess_expiration = 0;
      set_session('id_user', "$id_new");
      set_session('username', "$username");
      set_session('level', "$level");
      set_session('id_mitra', "$id_mitra");
      $pesan = "Selamat Datang ".$username.", Selamat beraktifitas :)";
    }else {
      $this->db->trans_rollback();
    }

    if ($platform=='mobile') { $pesan = htmlentities(strip_tags($pesan)); }
    echo json_encode(array("stt"=>$stt,'status'=>"$stt", 'pesan'=>$pesan));
    exit;
  }

  // Proses Login!
  public function proses_login($CP=1, $platform='', $level='', $un='', $pass='')
  {
    $log = CP_log('nama',$CP);//cek form login
    if ($platform=='mobile') { //mobile
      $username = post('no_telp');
    }else { //web
      $username = post('no_hp');
    }
    $password = post('password');
    if ($CP=='delivery') { $CP=2; }
    if ($level!=''){ $CP=$level; }
    if ($un!=''){ $username=$un; }
    if ($pass!=''){ $password=$pass; }
    if ($level==2) {
      $CP=2;
      $token = decode($un);
      // log_r($token);
      $get_ = get_field('user', array('id_user'=>$token));
      $username=$get_['username'];
      $password=decode($get_['password']);
    }
    $cek_data = get_un($username, '', $CP);
    $stt=1; $pesan='';
    if ($cek_data->num_rows() == 0) {
      $pesan = "<b>Gagal!</b> $log <b>'$username'</b> tidak ditemukan, silahkan coba lagi.";
      $stt   = 0;
    }else {
      $row = $cek_data->row();
      if ($password <> decode($row->password)) {
        $pesan = "<b>Gagal!</b> $log atau Password salah.";
        $stt   = 0;
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
      // if ($level==0) { //admin
      //   $time_exp = '14400'; //4 jam
      // }else {
        $time_exp = '0'; //unlimited
      // }
      // if (view_mobile()) {
        $this->session->sess_expiration = $time_exp;
      // }

      set_session('id_user', "$row->id_user");
      set_session('username', "$row->username");
      set_session('level', "$row->level");
      set_session('id_kota', $dt['id_kota']);
      set_session('id_mitra', $dt['id_mitra']);
      if ($row->level==0) {
        $nm = $username;
      }else {
        $nm = $dt['nama_lengkap'];
      }
      $pesan = "Selamat Datang ".$nm.", Selamat beraktifitas :)";
      pesan('success','msg_dashboard','',$pesan,'ajax');
    }

    if ($platform=='mobile') {
      if ($un!=''){
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
          'token'   => encode($dt['id_user'])
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
  public function proses_forgot_password()
  {
    $email = post('email');
    $cek_data = get_email($email);
    $stt=1; $pesan='';
    if ($cek_data->num_rows() == 0) {
      $pesan = "<b>Gagal!</b> Email <b>'$email'</b> tidak ditemukan, silahkan coba lagi.";
      $stt   = 0;
    }else {
      $row = $cek_data->row();
    }
    if ($stt == 1) {
      $kirim = sent_email($row->id_user, 'forgot_password');
      if ($kirim==1) {
        $pesan = "Link Reset Password berhasil dikirim, silahkan cek Email Anda. Terimakasih!";
        pesan('success','msg','',$pesan,'ajax');
      }else {
        $stt=0; $pesan="<b>Gagal!</b> Kirim ke <b>'$email'</b>, silahkan coba lagi.";
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
      $simpan = update_data('user', $data, array('id_user'=>$id));
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
