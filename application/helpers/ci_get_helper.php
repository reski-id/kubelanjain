<?php
///START SESSION
function set_session($nama='',$key='') //add or update session
{ $CI = &get_instance();
  if ($nama!='') { $CI->session->set_userdata($nama, $key); }
}

function get_session($ket='') //get session
{ $CI = &get_instance();
  if ($ket!='') { return $CI->session->userdata($ket); }
}

function del_session($ket='') //remove session
{ $CI = &get_instance();
  if ($ket!='') { $CI->session->has_userdata($ket); }
}

function del_all_session() //remove all session
{ $CI = &get_instance();
  $CI->session->sess_destroy();
}

function logout($redirect='')
{
  del_all_session();
  if ($redirect!='') {
    redirect($redirect);
  }
}
//END SESSION

function set_POST($data)
{
  return htmlentities(strip_tags($data));
}
//START POST
function post($ket,$stt='')
{ $CI = &get_instance();
  if ($stt=='1') {
    return $CI->input->post($ket);
  }else {
    return htmlentities(strip_tags($CI->input->post($ket)));
  }
}

function post_all($no_post='')
{ $CI = &get_instance();
  $post = array();
  if (!is_array($no_post)) { $no_post = array($no_post); }
  // log_r($no_post);
  foreach ( $_POST as $key => $value )
  {
    if(!in_array($key,$no_post)){
      $post[$key] = post($key);
    }
  }
  return $post;
}
//END POST

//START MODEL
function model($model,$func='',$data='', $data2='', $data3='', $data4='', $data5='', $data6='')
{ $CI = &get_instance();
  if ($CI->load->model($model)) {
    return $CI->$model->$func($data, $data2, $data3, $data4, $data5, $data6);
  }else {
    return 'Function tidak ditemukan!';
  }
}
//END MODEL

//START VIEW
function view($view='', $data='', $stt='', $stt2='')
{ $CI = &get_instance();
  if($view==''){ return ''; }
  return $CI->load->view($view,$data,$stt,$stt2);
}
//END VIEW

//START URI
function uri($data='')
{ $CI = &get_instance();
  if ($data=='x') {
    for ($i=1; $i <=$CI->uri->total_segments(); $i++) { $url_arr[] = uri($i); }
    $not_view = array('index','#','edit');
    $get_url = '';
    foreach ($url_arr as $key => $value) {
      if (!in_array($value,$not_view)) {
        if ($value=='%') { break; }
        $get_url .= $value."/";
      }
    }
    if ($get_url!='') {
      return substr($get_url,0,-1);
    }else{ return ''; }
  }else {
    return $CI->uri->segment(preg_replace("/[^0-9]/","",$data));
  }
}
//END URI


//CEK
//==== TABEL ====//
function list_tables(){
  $CI = &get_instance();
  return $CI->db->list_tables();
}

function table_exists($tbl=''){
  $CI = &get_instance();
  return $CI->db->table_exists($tbl);
}
//==== FIELD ====//
function list_fields($tbl=''){
  $CI = &get_instance();
  return $CI->db->list_fields($tbl);
}

function field_exists($tbl='',$field=''){
  $CI = &get_instance();
  return $CI->db->field_exists($field, $tbl);
}

function field_data($tbl=''){
  $CI = &get_instance();
  return $CI->db->field_data($tbl);
}
//CEK
?>
