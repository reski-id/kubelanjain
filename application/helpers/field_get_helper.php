<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function web($field='')
{ $CI = &get_instance(); $tbl='set_web'; $get='';
  if ($CI->db->field_exists($field, $tbl))
  {
    $get = get_field($tbl, array('id_set_web'=>1))[$field];
    if (!empty($get)) { return $get; }
  }
  return $get;
}

// Email -===========================================================
function status_email()
{ $CI = &get_instance();
  return get('set_email', array('status'=>1))->num_rows();
}

function email($field='')
{ $CI = &get_instance(); $tbl='set_email'; $get='';
  if ($CI->db->field_exists($field, $tbl))
  {
    $get = get_field($tbl, array('id_set_email'=>1))[$field];
    if (!empty($get)) { return $get; }
  }
  return $get;
}

function sent_email($id_user, $tipe='')
{ $CI = &get_instance();
  $CI->load->model('M_email');
  return $CI->M_email->sent_mail($id_user, $tipe);
}

function get_field_email($id='')
{ $CI = &get_instance();
  return $CI->M_email->get_field($id);
}
// Email -===========================================================


// =========================================================
function get($tbl='',$where_arr='')
{ $CI = &get_instance(); $get='';
  if ($tbl!='') {
    if ($where_arr!='') {
      foreach ($where_arr as $key => $value) {
        $CI->db->where($key,$value);
      }
    }
    $get = $CI->db->get($tbl);
  }
  return $get;
}

function field($tbl,$field,$where_arr='')
{ $CI = &get_instance();
  $CI->db->select($field);
  if ($where_arr!='') {
    foreach ($where_arr as $key => $value) {
      $CI->db->where($key,$value);
    }
  }
  $v = $CI->db->get($tbl);
  return $v;
}

function get_field($tbl,$where_arr='')
{ $CI = &get_instance();
  return get($tbl, $where_arr)->row_array();
}

// CRUD
function add_data($tbl,$data)
{ $CI = &get_instance();
  return $CI->db->insert($tbl,$data);
}
function update_data($tbl,$data,$d1='')
{ $CI = &get_instance();
  return $CI->db->update($tbl,$data,$d1);
}
function delete_data($tbl,$where='')
{ $CI = &get_instance();
  if ($where=='') {
    return $CI->db->delete($tbl);
  }else {
    return $CI->db->delete($tbl,$where);
  }
}

// BATCH
function add_batch($tbl,$data)
{ $CI = &get_instance();
  return $CI->db->insert_batch($tbl,$data);
}
function update_batch($tbl,$data,$id='')
{ $CI = &get_instance();
  return $CI->db->update_batch($tbl,$data,$id);
}
// BATCH

function convert_phone($no_hp, $negara='ID')
{
  $cek_ = substr($no_hp, 0, 2);
  $get_ = substr($no_hp, 1);
  if ($negara=='ID') {
    if ($cek_ == '08') {
      return '+62'.$get_;
    }
  }
}


function get_name_bc_info_group($id='')
{
  return get_field('bc_info_group', array('id_bc_info_group'=>$id))['nama_group'];
}

function get_name_provinsi($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('provinsi');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('provinsi', array('id_provinsi'=>$id))['provinsi'];
}

function get_name_kota($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('kota');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('kota', array('id_kota'=>$id))['kota'];
}

function get_name_kecamatan($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('kecamatan');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('kecamatan', array('id_kecamatan'=>$id))['kecamatan'];
}

function get_name_type($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('type');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('type', array('id_type'=>$id))['type'];
}

function get_name_sales($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('sales');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('sales', array('id_sales'=>$id))['sales'];
}

function get_name_item($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('nama_item');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('nama_item', array('id_item_master'=>$id))['nama_item'];
}

function get_name_kelurahan($id='')
{ $CI = &get_instance();
  $CI->db->select('kelurahan');
  return get_field('kelurahan', array('id_kelurahan'=>$id))['kelurahan'];
}

function get_name_badan_usaha($id='')
{ $CI = &get_instance();
  $CI->db->select('nm_badan_usaha');
  return get_field('badan_usaha', array('id_badan_usaha'=>$id))['nm_badan_usaha'];
}

function get_name_pelanggan($id='')
{ $CI = &get_instance();
  $CI->db->select('pelanggan');
  return get_field('pelanggan', array('id_pelanggan'=>$id))['pelanggan'];
}

function get_name_toko($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('toko');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('toko', array('id_toko'=>$id))['toko'];
}

function get_name_pasar($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('pasar');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('pasar', array('id_pasar'=>$id))['pasar'];
}

function get_type_pembayaran($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('pembayaran');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('type_pembayaran', array('id_type_pembayaran'=>$id))['pembayaran'];
}

function get_name_jekel($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('jekel');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('jekel', array('id_jekel'=>$id))['jekel'];
}

function get_name_item_satuan($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('item_satuan');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('item_satuan', array('id_item_satuan'=>$id))['item_satuan'];
}

function get_name_item_plu($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('nama_item');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('item_master', array('plu'=>$id))['nama_item'];
}

function get_name_item_kategori($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('nama');
  if ($status!=''){ $CI->db->where('status', $status); }
  return get_field('item_kategori', array('id_item_kategori'=>$id))['nama'];
}

function get_name_bank($id='', $status='')
{ $CI = &get_instance();
  $CI->db->select('bank');
  if ($status!='') { $CI->db->where('status', $status); }
  return get_field('bank', array('id_bank'=>$id))['bank'];
}

function get_name_mitra($id='')
{ $CI = &get_instance();
  $CI->db->select('nama_lengkap');
  return get_field('user_biodata_mitra', array('id_mitra'=>$id))['nama_lengkap'];
}

function get_name_reseller($id='')
{ $CI = &get_instance();
  $CI->db->select('nama_lengkap');
  return get_field('user_biodata_reseller', array('id_mitra'=>$id))['nama_lengkap'];
}

function find_nomor_get_id_user($id='')
{ $CI = &get_instance();
  $CI->db->select('id_user');
  return get_field('user_biodata', array('id_mitra'=>$id))['id_user'];
}

function find_nomor_get_type_id($id='', $ket='')
{ $CI = &get_instance();
  $CI->db->select('type_id');
  $get = get_field('user_biodata', array('id_mitra'=>$id))['type_id'];
  if ($ket=='fee' && $get >= 3) {
    return 3;
  }
  return $get;
}

function find_id_user_get_type_id($id='', $ket='')
{ $CI = &get_instance();
  $CI->db->select('type_id');
  $get = get_field('user_biodata', array('id_user'=>$id))['type_id'];
  if ($ket=='fee' && $get >= 3) {
    return 3;
  }
  return $get;
}

function get_no_user($id='')
{ $CI = &get_instance();
  $CI->db->select('id_mitra');
  return get_field('user_biodata', array('id_user'=>$id))['id_mitra'];
}

function get_no_mitra($id='')
{ $CI = &get_instance();
  $CI->db->select('id_mitra');
  return get_field('user_biodata_mitra', array('id_user'=>$id))['id_mitra'];
}

function get_no_reseller($id='')
{ $CI = &get_instance();
  $CI->db->select('id_mitra');
  return get_field('user_biodata_reseller', array('id_user'=>$id))['id_mitra'];
}

function get_sosmed()
{
  $get_sosmed[] = array('icon'=>'bxl-facebook-square', 'nama'=>'Facebook');
  $get_sosmed[] = array('icon'=>'bxl-instagram-alt', 'nama'=>'Instagram');
  $get_sosmed[] = array('icon'=>'bxl-twitter', 'nama'=>'Twitter');
  return $get_sosmed;
}

function up_status_ORDER($stt='')
{
  $sttnya = array('open','pickup','delivery','done','cancel','transfer');
  if (empty($sttnya[$stt])) { return $stt; }
  return $sttnya[$stt];
}

function up_log_ORDER($log='', $stt='')
{
  if ($log=='') { return serialize(array('status'=>'open', 'tgl'=>tgl_now())); }
  foreach (unserialize($log) as $key => $value) {
    $list[] = $value;
  }
  $list[] = array('status'=>up_status_ORDER($stt), 'tgl'=>tgl_now());
  return serialize($list);
}

function get_no_hp_user($id_user='')
{
  $CI = &get_instance();
  $CI->db->select('no_hp');
  $get = get_field('user_biodata', array('id_user'=>$id_user))['no_hp'];
  if (!empty($get)) {
    return $get;
  }
}
// CEK
function get_block_USER($no_HP='', $get_X='')
{ $CI = &get_instance();
  $block_USER = true;
  if (in_array($get_X, array(1,2))) {
    $CI->db->select('type_id');
    $get_M = get('user_biodata_mitra', array('no_hp'=>$no_HP))->row();
    if (empty($get_M)) { //jika belum pernah mendaftar sebagai mitra 2
      $block_USER = false;
    }
  }
  return $block_USER;
}

function get_item_satuan($status='')
{ $CI = &get_instance();
  if ($status!='') { $CI->db->where('status', $status); }
  $CI->db->order_by('item_satuan', 'ASC');
  return get('item_satuan');
}

function get_management_akses($field='')
{ $CI = &get_instance();
  if ($field!='') { $CI->db->select($field); }
  $CI->db->order_by('nama_akses', 'ASC');
  return get('management_akses');
}

function get_name_management_akses($id='', $stt='')
{ $CI = &get_instance();
  $CI->db->select('nama_akses');
  // if ($stt!='') { $CI->db->where('status', $stt); }
  return get_field('management_akses', array('id_management_akses'=>$id))['nama_akses'];
}

function get_item_kat($field='', $stt='')
{ $CI = &get_instance();
  if ($field!='') { $CI->db->select($field); }
  if ($stt!='')   { $CI->db->where('status', $stt); }
  $CI->db->order_by('kode', 'ASC');
  return get('item_kategori');
}

function get_status_order($stt='')
{
  $data = array('OPEN', 'PAYMENT', 'DELIVERY', 'DONE', 'CANCEL');
  if (!empty($data[$stt])) { return $data[$stt]; }
  return '-';
}

function get_name_akses_user_approval($jenis_akses='', $nama_gudang='', $nama_akses='')
{
  $nama = '-';
  if ($jenis_akses == 1) {
    $nama = $nama_gudang;
  }else {
    $nama = $nama_akses;
    if ($nama=='-') {
      $nama = 'Admin';
    }
  }
  return $nama;
}


function get_list_approval($aksi='form', $id='', $id2='')
{ $CI = &get_instance();
  if ($id==''){ return array(); }
  if ($aksi=='detail') { $usernya=''; }else { $usernya=array(); }
  $id_user=array(); $get_user=$usernya;
  if (empty($id2)) {
    $CI->db->select('A.id_user, B.nama_lengkap, B.jenis_akses, get_name_gudang(B.id_gudang) AS nama_gudang, get_name_management_akses(B.id_management_akses) AS nama_akses');
    $CI->db->join('user_biodata_management AS B', 'B.id_user=A.id_user');
    $CI->db->order_by('B.nama_lengkap', 'ASC');
    $CI->db->group_by('A.id_user');
    $user = get('approval AS A', array('A.id_approval_tipe'=>$id))->result();
    foreach ($user as $key => $value) {
      $nama = get_name_akses_user_approval($value->jenis_akses, $value->nama_gudang, $value->nama_akses);
      if ($aksi=='detail') {
        $usernya .= '<div class="badge badge-primary" style="margin-bottom:3px;">'."$value->nama_lengkap [ $nama ]".'</div> ';
      }else {
        $usernya[] = array('id'=>$value->id_user, 'text'=>"$value->nama_lengkap [ $nama ]");
      }
      $id_user[] = $value->id_user;
    }
    if ($aksi=='detail') {
      $get_user = '<label style="font-size:20px;">User</label><br />'.$usernya;
    }else {
      $get_user = $usernya;
    }
  }
  $CI->db->select('A.id_user_approval, C.jenis_akses, C.nama_lengkap, get_name_gudang(C.id_gudang) AS nama_gudang, get_name_management_akses(C.id_management_akses) AS nama_akses');
  $CI->db->join('approval_tipe AS B', 'A.id_approval_tipe=B.id_approval_tipe');
  $CI->db->join('user_biodata_management AS C', 'C.id_user=A.id_user_approval');
  if (!empty($id2)) {
    $CI->db->where('A.id_user', $id2);
  }else {
    if (!empty($usernya)) {
      $CI->db->where_in('A.id_user', $id_user);
    }
  }
  $CI->db->order_by('A.id_approval', 'ASC');
  $CI->db->group_by('A.id_user_approval');
  $get_list = get('approval AS A', array('A.id_approval_tipe'=>$id));
  return array('get_list'=>$get_list, 'get_user'=>$get_user);
}


function add_approval_list($tipe='', $no_surat='', $id_user='')
{ $CI = &get_instance();
  $CI->db->select('id_approval, id_user_approval');
  $CI->db->order_by('id_approval', 'ASC');
  $get_app = get('approval', array('id_approval_tipe'=>$tipe, 'id_user'=>$id_user));
  if (!empty($get_app->row())) {
    $data=array();
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tgl_input = tgl_now();
    foreach ($get_app->result() as $key => $value) {
      $data[$key]['id_approval']      = $value->id_approval;
      $data[$key]['no_surat']         = $no_surat;
      $data[$key]['id_user']          = $id_user;
      $data[$key]['id_user_approval'] = $value->id_user_approval;
      $data[$key]['status']           = 0;
      $data[$key]['tgl_input']        = $tgl_input;
      $data[$key]['input_by']         = $nama_input;
    }
    if (!empty($data)) {
      return add_batch('approval_list', $data);
    }
    return false;
  }
  return false;
}

function get_list_approval_status($no_surat='')
{ $CI = &get_instance();
  $CI->db->select('A.id_user, A.status, A.id_user_approval, B.jenis_akses, B.nama_lengkap, get_name_gudang(B.id_gudang) AS nama_gudang, get_name_management_akses(B.id_management_akses) AS nama_akses, A.tgl_update');
  $CI->db->join('user_biodata_management AS B', 'B.id_user=A.id_user_approval');
  $CI->db->order_by('A.id_approval', 'ASC');
  $CI->db->group_by('A.id_user_approval');
  $get_list = get('approval_list AS A', array('A.no_surat'=>$no_surat));
  return $get_list;
}

function up_approval_list($no_po='', $id_user='', $stt='', $nama_input='')
{ $CI = &get_instance();
  $tbl_app = 'approval_list';
  $where2 = array('no_surat'=>$no_po, 'id_user_approval'=>$id_user);
  $CI->db->select('no_surat');
  $get = get_field($tbl_app, $where2);
  if (!empty($get)) {
    $post['status']     = $stt;
    $post['tgl_update'] = tgl_now();
    $post['update_by']  = $nama_input;
    $simpan = update_data($tbl_app, $post, $where2);
    if ($simpan) {
      $CI->db->select('no_surat');
      $get = get_field($tbl_app, array('no_surat'=>$no_po, 'status'=>'0'));
      if (empty($get)) {
        return array('stt'=>1, 'pesan'=>''); //approval finish
      }else{
        return array('stt'=>2, 'pesan'=>''); //approval proses
      }
    }
  }
  return array('stt'=>0, 'pesan'=>'User Approval belum ditentukan!');
}

function export_pdf_harga_pasar($tgl='', $aksi='')
{
  $CI = &get_instance();
  if ($tgl=='') { $tgl = date('d-m-Y'); }
  $filename  = "Katalog Produk BKYB ".$tgl.".pdf";
  $file_path = "assets/file/harga_pasar/".$tgl.".pdf";
  $arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
      ),
  );
  // view('web/sub/beranda/pdf');
  // $html = $CI->output->get_output();
  $html = file_get_contents(web('website').'/web/repot_price_list', false, stream_context_create($arrContextOptions));
  // Load pdf library
  $CI->load->library('pdf');
  // $CI->pdf->loadHtml($html, 'UTF-8');
  $CI->pdf->loadHtml($html);
  $CI->pdf->setPaper('A4', 'portrait');
  $CI->pdf->render();
  $output = $CI->pdf->output();
  file_put_contents($file_path, $output);
  if ($aksi=='data') {
    return array('file_path'=>$file_path, 'filename'=>$filename);
  }else {
    return true;
  }
}
?>
