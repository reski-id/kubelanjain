<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

  function save_benefit($id_user='')
  { $CI = &get_instance();
    if($id_user==''){ return false; }
    $simpan = false;
    $cek = $CI->db->query("CALL cek_benefit($id_user, @saldonya);");
    $get = $CI->db->query("SELECT @saldonya AS total")->row();
    if (empty($get)) {
      $total = 0;
    }else {
      $total = $get->total;
    }
    $where = array('id_user'=>$id_user);
    if (get('user_total', $where)->num_rows()==0) {
      $simpan = add_data('user_total', array('id_user'=>$id_user, 'total_benefit'=>$total));
    }else {
      $simpan = update_data('user_total', array('total_benefit'=>$total), $where);
    }
    return $simpan;
  }

  function get_fee_value($in='', $out='')
  { $CI = &get_instance();
    $CI->db->select('value_in, value_out');
    $CI->db->where('tipe_in!=0 AND tipe_out!=0');
    $get = get_field('set_fee', array('tipe_in'=>$in, 'tipe_out'=>$out));
    if (empty($get)) {
      $in=0; $out=0;
    }else {
      $in=$get['value_in']; $out=$get['value_out'];
    }
    return array('in'=>$in, 'out'=>$out);
  }

  function max_fee()
  {
    return get_field('set_fee', array('tipe_in'=>0, 'tipe_out'=>0))['value_in'];
  }

  function get_data_fee_arr($no_master='', $no_child='', $id_master='', $id_child='', $stt='')
  {
    // $no_master = $value->id_referal; //tipe_in
    // $no_child  = $value->id_mitra; //tipe_out
    if ($id_master==''){ $id_master = find_nomor_get_id_user($no_master); }
    if ($id_child=='') { $id_child  = find_nomor_get_id_user($no_child); }
    $tipe_in   = find_nomor_get_type_id($no_master, 'fee');
    $tipe_out  = find_nomor_get_type_id($no_child, 'fee');
    $get_fee 	 = get_fee_value($tipe_in, $tipe_out);
    $fee_master = $get_fee['in'];
    $fee_child  = $get_fee['out'];
    $tgl_stt    = 'tgl_input';
    if ($stt=='up') { $tgl_stt='tgl_update'; }
    $data = array(
      'tipe_in'     => $tipe_in,
      'tipe_out'    => $tipe_out,
      'id_master'   => $id_master,
      'no_master'   => $no_master,
      'id_child'    => $id_child,
      'no_child'    => $no_child,
      'fee_master'  => $fee_master,
      'fee_child'   => $fee_child,
      "$tgl_stt"    => tgl_now(),
    );
    return $data;
  }

  function get_name_tipe_fee($tipe='')
  {
    $data = array('', 'Mitra 1', 'Mitra 2', 'Reseller');
    return $data[$tipe];
  }

  function get_color_tipe_fee($tipe='')
  {
    $data = array('', 'blue', 'green', 'red');
    return $data[$tipe];
  }

?>
