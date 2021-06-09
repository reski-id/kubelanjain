<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function select_datanya($tbl='',$idnya='',$dt='',$where='')
{ $CI = &get_instance();
  $datanya=array(); $get_data=array();
  if (in_array($tbl, array('sektor','lokasinya'))) {
    $CI->db->select('id_kelurahan as id, CONCAT(kelurahan, " (KEC: ", get_name_kecamatan(id_kecamatan), ")") AS nama'); $CI->db->order_by('kelurahan', 'ASC');
    $get_data = get('kelurahan')->result();
  }else{
    if ($tbl=='berat') {
      $nama = "( CASE WHEN $tbl='-' THEN '-' ELSE CONCAT($tbl, ' KG') END ) as nama ";
    }elseif ($tbl=='jarak' && $dt=='min') {
      $nama = "( CASE WHEN $tbl='-' THEN 'Tanpa Batas' ELSE CONCAT('>= ', $tbl, ' KM') END ) as nama";
    }elseif ($tbl=='jarak' && $dt=='max') {
      $nama = "( CASE WHEN $tbl='-' THEN 'Tanpa Batas' ELSE CONCAT('< ', $tbl, ' KM') END ) as nama";
    }elseif ($tbl=='user_all') {
      $tbl  = 'user';
      $nama = "get_nama_user(id_user) as nama";
    }else {
      $nama = "$tbl as nama";
    }
    $CI->db->select("id_$tbl as id, $nama");
    if ($where!='') {
      $CI->db->where($where);
    }
    if (in_array($tbl, array('berat','jarak'))) {
      $CI->db->order_by("CAST(`$tbl` AS decimal)", 'ASC');
    }elseif (in_array($tbl, array('user'))) {
      $CI->db->order_by("id_$tbl", 'ASC');
    }else {
      $CI->db->order_by("$tbl", 'ASC');
    }
    $get_data = get($tbl)->result();
  }
  foreach ($get_data as $key => $value) {
    $datanya[] = array('id'=>$value->id, 'nama'=>$value->nama, 'sel'=>selected_datanya($tbl, $idnya, $value->id));
  }
  return $datanya;
}

function selected_datanya($tbl='',$idnya='',$id2='')
{ $CI = &get_instance();
  $selected='';
  if (get($tbl, array("id_$tbl"=>$idnya))->num_rows()!=0) {
    $selected='selected';
  }
  return $selected;
}

?>
