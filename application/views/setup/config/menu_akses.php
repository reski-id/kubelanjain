<?php
if ($stt=='view') {
  if(empty(get_session('id_user'))) { redirect("web/login"); }
  $url = uri('x');
}
$level = '';
if ($url!='') {
  if (get_session('level')==0) {
    $data = 1;
  }else{
    $cek_level = list_level(get_session('level'))->row();
    if (!empty($cek_level)) { $level = get_session('level'); }
    // if (!is_array($url)) { $url = array($url); }
    // $CI->db->where_in('url', $url);
    $get_data = get('v_level_akses', array('level'=>$level));
    $data = 0;
    foreach ($get_data->result() as $key => $value) {
      if (substr($value->url,0,2)=="0/") {
        $urlX = substr($value->url,2);
        $NAMA = preg_replace("/[ ]/","_",$NAMA);
        if ($urlX == strtolower($NAMA)) {
          // log_r($NAMA);
          $data=1; break;
        }
      }
      if (strpos($value->url,'/%')) {
        $url_arr = explode('/', $url);
        $vi = '';
        foreach ($url_arr as $k => $v) {
          $urinya = substr($value->url,0,-1);
          $vi .= "$v/";
          if ($urinya == $vi) { $data=1; break; }
        }
      }else {
        if ($value->url==$url) {
          $data=2; break;
        }
      }
    }
    // log_r($urinya);
    // log_r($CI->db->last_query());
  }
  if($data > 0){
    return $data;
  }else {
    if ($stt=='view') { redirect('404');
    }else { return $data; }
  }
}
?>
