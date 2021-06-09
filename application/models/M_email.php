<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_email extends CI_Model {

  var $tabel = "set_email";
  var $id_pk = "id_set_email";

  //Sent mail
  	public function sent_mail($id_user, $tipe='')
  	{
      ini_set('max_execution_time','-1');
      if($tipe==''){$tipe='test'; $judul='Test Kirim Email'; }else{ $judul=ucwords($tipe); }
      $email_admin = get_field_email(1)['email_penerima'];
      if ($id_user=='test') {
        $username = get_field_email(1)['email_penerima'];
        $email    = get_field_email(1)['email_penerima'];
      }else {
        if ($tipe=='forgot_password') {
          $tblnya = 'user_biodata'; $judul='Reset Password';
          $username = get_field($tblnya,array('id_user'=>$id_user))['nama_lengkap'];
          $email    = get_field($tblnya,array('id_user'=>$id_user))['email'];
        }else {
          $username = get_field_user($id_user)['username'];
          $email    = get_field_profil($id_user)['email'];
        }
      }

      $get = get_field_email(1);
  		$email_saya = $get['username'];
  		$pass_saya  = $get['password'];

      //konfigurasi email
  		$config = array(
        'charset'      => 'utf-8',
    		'useragent'    => $get['nama_pengirim'],
    		'protocol'     => "smtp",
    		'mailtype'     => "html",
    		'smtp_host'    => $get['host'],
    		'smtp_port'    => $get['port'],
    		'smtp_timeout' => "465",
    		'smtp_user'    => "$email_saya",
    		'smtp_pass'    => "$pass_saya",
    		'crlf'         => "\r\n",
    		'newline'      => "\r\n",
    		'wordwrap'     => TRUE,
      );
      $this->email->initialize($config);

  		$data=array('view'=>$tipe,'id_user'=>$id_user, 'email'=>$email, 'judul'=>$judul);
  		$pesan = $this->load->view("email/index", $data,TRUE);
  		$this->email->from($get['email_pengirim'], $get['nama_pengirim']);
      $this->email->to($email);
      $this->email->subject($judul);
      $this->email->message($pesan);
      if ($id_user=='test') {
        echo "<title>$judul</title>";
    		if($this->email->send()) {
            echo '<span style="color:green;">Email berhasil dikirim ke <b>'.$email.'</b></span>';
            echo '<script>setTimeout(function(){window.close();},1000*5)</script>';
        }else {
            echo '<span style="color:red;">Email gagal dikirim ke <b>'.$email.'</b</span>';
            echo '<hr />';
            echo $this->email->print_debugger();
        }
      }else {
        if($this->email->send()) {
          return 1;
        }else {
          return 0;
        }
      }
  	}
  //End Sent mail

  public function field($field,$id='')
  {
    $this->db->select($this->id_pk);
    $this->db->select($field);
    if ($id!='') { $this->db->where("$this->id_pk","$id"); }
    $v = $this->db->get($this->tabel);
    return $v;
  }

  public function get_field($id='')
  {
    $fields = $this->db->list_fields($this->tabel);
    $field_ar = array();
    foreach ($fields as $field)
    {
      $field_ar [$field] = '';
      if ($id!='') {
        $data=$this->field($field,$id);
        if ($data->num_rows()!=0) {
          $field_ar [$field] = $data->row()->$field;
        }
      }
    }
    return $field_ar;
  }

}
