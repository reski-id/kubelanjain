<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_update_harga extends CI_Model
{
  var $tabel_update_harga       = 'update_harga';

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

   //START
   public function update_harga($id='')
   {
     ini_set('max_execution_time', '0');
     $this->ajax_permission('create', 'update_harga');
   	 $id_user = get_session('id_user');
   	 $nama_input = "$id_user - ".user('nama_lengkap');
   	 $tgl_input = tgl_now();
     $tbl = $this->tabel_update_harga;

     $id_item_master_sub = post('id_item_master_sub');
     $id_provinsi = post('id_provinsi');
     $id_kota 		= post('id_kota');
     $id_pasar 		= post('id_pasar');
     $provinsi 		= get_name_provinsi($id_provinsi);
     $kota 				= get_name_kota($id_kota);
     $pasar 			= get_name_pasar($id_pasar);
     $harga_dasar = khususAngka(post('harga_dasar'));
     $harga_tawar = khususAngka(post('harga_tawar'));
     $harga 			= khususAngka(post('harga'));
     $harga_beli  = $harga - ($harga*(10/100));
     $gap  				= $harga - $harga_beli;

     $this->db->select('id_item_master_sub, id_item_kategori, item_kategori, plu_sub, nama_item');
     $cek_data = get_field('item_master_sub', array('id_item_master_sub'=>$id_item_master_sub));

     $post = array(
       'id_provinsi'    => $id_provinsi,
       'provinsi' 	    => $provinsi,
       'id_kota' 		    => $id_kota,
       'kota' 			    => $kota,
       'id_pasar' 	    => $id_pasar,
       'pasar' 			    => $pasar,
       'id_item_kategori'   => $cek_data['id_item_kategori'],
       'item_kategori'      => $cek_data['item_kategori'],
       'id_item_master_sub' => $cek_data['id_item_master_sub'],
       'plu_sub'		    => $cek_data['plu_sub'],
       'nama_item_sub'  => $cek_data['nama_item'],
       'harga_dasar'		=> $harga_dasar,
       'harga'					=> $harga,
       'harga_tawar'		=> $harga_tawar,
       'harga_beli'			=> $harga_beli,
       'gap'						=> $gap,
       'tgl_input'			=> $tgl_input,
       'input_by'				=> $nama_input
     );
     $this->db->select('id_update_harga');
     $cek_UH = get_field($tbl, array('plu_sub'=>$cek_data['plu_sub']));
     if (empty($cek_UH)) {
       $simpan = add_data($tbl, $post);
       if ($simpan) {
         $simpan = add_data($tbl.'_history', $post);
       }
     }else {
       $simpan = update_data($tbl, $post, array('id_update_harga'=>$cek_UH['id_update_harga']));
       if ($simpan) {
         $simpan = add_data($tbl.'_history', $post);
       }
     }

     if ($simpan) {
       $simpan = export_pdf_harga_pasar();
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

   function update_harga_up_status($id='')
   {
     $stt = post('id');
     if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
     $this->ajax_permission('update', 'update_harga');
     $tbl = $this->tabel_update_harga;

     $this->db->select("id_$tbl");
     $get_data = get_field($tbl, array("id_$tbl"=>$id));
     if (empty($get_data)) { $this->ajax_failed(); }

     $id_user = get_session('id_user');
     $nama_input = "$id_user - ".user('nama_lengkap');

     $where = array("id_$tbl"=>$id);
     $post  = array('status'=>$stt, 'tgl_update'=>tgl_now(), 'update_by'=>$nama_input);
     $simpan = update_data($tbl, $post, $where);
     if ($simpan) {
       $this->db->trans_commit();
       echo json_encode(array('stt'=>1, 'pesan'=>''));
       exit;
     }else {
       $this->db->trans_rollback();
       $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
     }
   }
}
?>
