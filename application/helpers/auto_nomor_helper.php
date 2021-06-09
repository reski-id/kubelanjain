<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_nomor($KD='', $ket=null)
{ $CI = &get_instance(); $tbl='no_random'; $id_field='id_'.$tbl;
  $simpan = add_data($tbl, array($tbl=>$KD, 'ket'=>$ket, 'tgl_input'=>tgl_now()));
  if ($simpan) {
    $id = $CI->db->insert_id();
    return get_field($tbl, array($id_field=>$id))[$tbl];
  }else {
    return '';
  }
}

function del_nomor($KD='', $ket='')
{
  $tbl='no_random';
  if ($KD=='ket') {
    return delete_data($tbl, array($KD=>$ket));
  }else {
    return delete_data($tbl, array($tbl=>$KD));
  }
}

function cek_Referal($referal='', $ket='')
{ $CI = &get_instance();
  $stt=1;
  if ($referal!='') {
    $CI->db->select('type_id');
    $CI->db->where_in('type_id', array('1','2'));
    $cek_data = get('user_biodata', array('id_mitra'=>$referal))->row();
    if (!empty($cek_data)) {
      if ($ket=='mitra' && $cek_data->type_id=='2') {
        $stt=0;
      }else {
        $stt=1;
      }
    }else {
      $stt=0;
    }
  }
  if ($ket=='ajax') {
    return $stt;
  }else {
    if($stt==0){ redirect(web('index_redirect')); }
  }
}
?>
