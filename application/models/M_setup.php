<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_setup extends CI_Model
{
  var $tbl_web = 'set_web';

  // Proses WEB!
  public function proses_web()
  {
    $path='img'; $tbl=$this->tbl_web; $stt=0;
		upload_config($path,'5','jpeg|jpg|png|gif|bmp');
    $this->db->trans_begin();
		$post = post_all('btnsimpan','footer');
    $post['footer'] = post('footer',1);
		$favicon = upload_file('favicon',$path,'ajax',web('favicon'));
		$logo 	 = upload_file('logo',$path,'ajax',web('logo'));
    if (!empty($favicon['pesan'])) {
      $stt=0; $pesan = $favicon['pesan'];
    }else {
      $stt=1; $post['favicon'] = $favicon;
      $cek_resize = resizeImage($favicon,$path);
      if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Favicon Gagal!'; }
    }
    if (!empty($logo['pesan'])) {
      $stt=0; $pesan = $logo['pesan'];
    }else {
      $stt=1; $post['logo'] = $logo;
      $cek_resize = resizeImage($logo,$path);
      if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Logo Gagal!'; }
    }
		if ($stt==1) {
      $simpan = update_data($tbl, $post, array('id_'.$tbl=>1));
  		if ($simpan) {
  			$this->db->trans_commit();
        $stt=1;
        $pesan = "Sukses! Data berhasil disimpan";
      }else {
  			$this->db->trans_rollback();
        $pesan = "Gagal! Silahkan coba lagi";
      }
    }
    echo json_encode(array("stt"=>$stt, 'pesan'=>$pesan));
    exit;
  }


  // Proses SIMPAN!
  function proses_simpan($tbl='',$id='')
  { $stt=1;
		if($tbl==''){
      $stt=0; $pesan="Tabel tidak ditemukan!";
    }
    if ($stt==1) {
      $this->db->trans_begin();
			$post  = post_all(array("id_$tbl","id"));
			if ($id=='') {
				$simpan = add_data($tbl,$post);
			}else{
        if (empty(post("id"))) {
          $id = decode($id);
        }else {
          $id = decode(post("id"));
        }
        $post['tgl_update'] = tgl_now();
        if (!empty($post['status'])) {
          if ($post['status']=='on') { $n_stt = 1; } else { $n_stt = 0; }
          $post['status'] = $n_stt;
        }
        if ($tbl=="set_mode_page") {
          $field_id = "$tbl";
        }else {
          $field_id = "id_$tbl";
        }
				$simpan = update_data($tbl,$post, array("$field_id"=>$id));
			}
			if ($simpan) {
				$this->db->trans_commit();
        $stt=1; $pesan="Sukses! Data berhasil disimpan";
      }else {
				$this->db->trans_rollback();
				$stt=0; $pesan="Gagal! Silahkan coba lagi";
      }
    }
		echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
		exit;
  }

}
?>
