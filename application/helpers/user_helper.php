<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function hapus_user_global($level='', $id='')
{
  $CI = &get_instance();
  $CI->db->trans_begin();
  $tbl = 'user';
  $where = array("id_$tbl"=>$id);
  delete_data('user_biodata_mitra', $where);
  delete_data('user_biodata_reseller', $where);
  delete_data('user_bank', $where);
  delete_data('user_identitas', $where);
  delete_data('user_kendaraan', $where);
  delete_data('user_usaha', $where);
  $hapus = delete_data($tbl, $where);
  if ($hapus) {
    $CI->db->trans_commit();
    delete_directory("img/$level/$id");
    return true;
  }else {
    $CI->db->trans_rollback();
    return false;
  }
}

function jenis_akun()
{
  if (get_session('level')==0){ //jika admin
    return "";//"Admin";
  }elseif (get_session('level')==1) { //jika Mitra
    return "Mitra";
  }elseif (get_session('level')==2) { //jika Reseller
    return "Reseller";
  }else {
    return user('username');
  }
}

function user($field='',$id='', $tbl='')
{ $CI = &get_instance(); $get='';
  if($tbl==''){ $tbl='user'; $tbl="v_$tbl"; }
  if ($CI->db->field_exists($field, $tbl))
  {
    if($id==''){
      if (empty(get_session('id_user'))) { $id = post('id_user'); }else { $id = get_session('id_user'); }
      if (get_session('id_user')==1 && $field=='nama_lengkap') { return get_session('username'); }
    }
    $CI->db->select($field);
    $get = get_field($tbl,array('id_user'=>$id))[$field];
    if (!empty($get)) { return $get; }
  }
  return $get;
}

function user_usaha($field='',$id='', $tbl='')
{ $CI = &get_instance(); $get='';
  if($tbl==''){ $tbl='user_usaha';  if (get_session('level') != 0) { $tbl = "v_$tbl"; } }
  if ($CI->db->field_exists($field, $tbl))
  {
    if($id==''){
      if (empty(get_session('id_user'))) { $id = post('id_user'); }else { $id = get_session('id_user'); }
    }
    $CI->db->select($field);
    $get = get_field($tbl,array('id_user'=>$id))[$field];
    if (!empty($get)) { return $get; }
  }
  return $get;
}

function get_un($un='', $un2='', $status='', $tbl='user')
{ $CI = &get_instance(); $get='';
  if($status!=''){ $CI->db->where('level', $status); }
  if($un2!=''){ $CI->db->where('username!=', $un2); }
  return get($tbl, array('username'=>$un));
}

function get_email($email='', $email2='')
{ $CI = &get_instance(); $tbl='user_biodata'; $get='';
  if($email2!=''){ $CI->db->where('email!=', $email2); }
  return get($tbl, array('email'=>$email));
}

function get_no_hp($no_hp='', $no_hp2='', $tbl='user_biodata')
{ $CI = &get_instance();
  if($no_hp2!=''){ $CI->db->where("no_hp!=", $no_hp2); };
  $CI->db->where('no_hp', $no_hp);
  return get($tbl);
}

function get_user_sosmed($nama='', $id='')
{ $CI = &get_instance();
  if($id==''){ $id=get_session('id_user'); }
  $CI->db->select('url');
  return get_field('user_sosmed', array('id_user'=>$id, 'sosmed'=>strtolower($nama)))['url'];
}

function get_path_img($id='', $set_path='')
{ $CI = &get_instance();
  $path = 'img/';
  if ($id=='hari_ini') {
    return $path.'/'.date('Y').'/'.date('m').'/'.date('d');
  }elseif ($id=='custom') {
    return $path.$set_path;
  }

  if ($id=='') { $id = get_session('id_user'); }
  $CI->db->select('tgl_input, level');
  $get = get('user', array('id_user'=>$id))->row();
  if (!empty($get)) {
    $tgl   = $get->tgl_input;
    $level = $get->level;
      if ($level==1) {
        $path .= 'mitra';
      }elseif ($level==2) {
        $path .= 'reseller';
      }else {
        $path .= 'user';
      }
    $foldernya = $path.'/'.tgl_format($tgl,'Y').'/'.tgl_format($tgl,'m').'/'.tgl_format($tgl,'d');
    return $foldernya;
  }else {
    return $path.'user';
  }
}

?>
