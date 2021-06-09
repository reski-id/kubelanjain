<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_type_id($aksi='', $post_id='')
{
  if ($aksi=='mitra') {
    return array(1,2);
  }elseif ($aksi=='reseller') {
    return array(3,4,5);
  }elseif ($aksi=='ID') {
    if (in_array($post_id, get_type_id('mitra'))) { //mitra
      return 'M';
    }elseif (in_array($post_id, get_type_id('reseller'))) { //reseller
      return 'R';
    }else{
      return '';
    }
  }else {
    return '';
  }
}

function cek_redirect()
{ $CI =& get_instance();
  $url = uri(1);
  $CI->db->select('redirect');
  $get = get('redirect', array('link'=>$url,'status'=>1))->row();
  if (!empty($get)) {
    redirect($get->redirect);
  }
}

function GET_KEY($nama='')
{ $CI =& get_instance();
  if($nama==''){ return ''; }
  $CI->db->select('api_key');
  $cek = get('set_api', array('nama'=>$nama))->row();
  if (!empty($cek)) {
    $api_key = $cek->api_key;
    $get_key = explode(', ', $api_key);
    if (count($get_key)==1) {
      return $api_key;
    }else {
      return $get_key;
    }
  }
}

function SuperAdmin()
{
  if (get_session('level')==0 && get_session('id_user')==1) {
    return true;
  }else {
    return false;
  }
}

function domainnya()
{
  return 'meeju.id';
}

function dbnya()
{
  return 'meeju';
}

function view_mobile()
{ $CI =& get_instance();
  if ($CI->agent->is_mobile()) {
    return true;
  }else {
    return false;
  }
}

function get_data($tabel,$primary_key,$id,$select)
{
  $CI =& get_instance();
  $data = $CI->db->query("SELECT $select FROM $tabel where $primary_key='$id' ")->row_array();
  return $data[$select];
}

function ccFormat_Number($number, $jml_op=4, $op='-') {
  $dt=''; $i=0;
  foreach(str_split($number) as $value){
    if($i==$jml_op){ $X=$op; $i=0; }else{ $X=''; }
    $dt .= $X.$value;
    $i++;
  }
  return $dt;
}

function ccMasking($number, $mask = 'X') {
  return str_repeat($mask, strlen($number)-2).substr($number,-2);
}

function delete_directory($folder='')
{
  if($folder==''){ return '-'; }
  if (!is_dir($folder)) { return '-'; }
  $files = glob($folder.'/*');
  foreach ($files as $file) {
      // $lastModifiedTime = filemtime($file);
      // $currentTime      = time();
      // $timeDiff         = abs($currentTime - $lastModifiedTime)/(60*60); // in hours
      // if(is_file($file) && $timeDiff > 10) // check if file is modified before 10 hours
      if(is_file($file))
      unlink($file); // hapus file
  }
   $fd = rmdir ($folder);
   //mengecek proses rmdir
   if ($fd) {
     return  "Folder <b>".$folder."</b> berhasil dihapus";
   }
   else {
     return  "Folder <b>".$folder."</b> gagal dihapus";
   }
}

function createPath($path, $mode=0775)
{
  if (!is_dir($path)) {
    mkdir("$path", $mode, true);
    // mkdir("$path");
    // chmod("$path", $mode);
    return true;
  }else {
    return false;
  }
}

function CP_log($stt='', $uri='')
{
  if($uri==''){ $uri=uri(3); }
  if (in_array($uri, array('M_admin','0'))) {
    if ($stt=='nama') { return 'Username'; }
    return true;
  }else {
    if ($stt=='nama') { return 'Nomor Handphone'; }
    return false;
  }
}

function mode($stt='')
{
  $cek = get('user', array('id_user'=>get_session('id_user'), 'mode'=>'1'))->num_rows();
  if ($cek!=0) { //jika mode gelap
    return $stt=='icon' ? 'none' : 'dark-layout';
  }else { //jika mode terang
    return $stt=='icon' ? 'darker' : '';
  }
}

function lv()
{
  $lv = array('admin','mitra','reseller');
  if (get_session('level')!='') {
    return $lv[get_session('level')];
  }else {
    redirect('auth');
  }
}


function siteURL($stt='') {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domainName = $_SERVER['HTTP_HOST'] . '/';
    if ($stt=='domain') {
      return $domainName;
    }else {
      return $protocol . $domainName;
    }
}

function log_r($string = null, $var_dump = false)
{
    if ($var_dump) {
        var_dump($string);
    } else {
        echo "<pre>";
        print_r($string);
    }
    exit;
}

function objectToArray($d) {
      if (is_object($d)) {
          $d = get_object_vars($d);
      }
      if (is_array($d)) {
          return array_map(__FUNCTION__, $d);
      }
      else {
          return $d;
      }
}

function arrayToObject($d) {
      if (is_array($d)) {
          return (object) array_map(__FUNCTION__, $d);
      }
      else {
          return $d;
      }
}

//======================== UPLOAD =============================
function upload_config($path='',$size='',$tipe='')
{ $CI = &get_instance();
  if($path==''){$path='assets/uploads';} if($tipe==''){$tipe='*';} if($size==''){$size=1;}
  $file_size = 1024 * $size;
  $CI->upload->initialize(array(
    "upload_path"   => "./$path",
    "allowed_types" => "$tipe",
    "max_size" => "$file_size",
    "remove_spaces" => TRUE,
    "encrypt_name" => TRUE,
  ));
}

function upload_file($filename='',$path='',$url='',$file='',$unlink_file='')
{ $CI = &get_instance();
  // if(!file_exists($filename)){ return ''; }
  if($path==''){$path='assets/uploads';}
  if (empty($_FILES[$filename])) {
    return $file;
  }else{
    if ($_FILES[$filename]['error'] <> 4) {
        if ( $CI->upload->do_upload($filename))
        {
          if ($unlink_file=='') {
            if (file_exists($file)) { unlink($file); }
          }
            $uploadData = $CI->upload->data();
            $filename = "$path/".$uploadData['file_name'];
            $file = preg_replace('/ /', '_', $filename);
            return $file;
        }else {
          $error = $CI->upload->display_errors();
          if ($url=='ajax') {
            return array('pesan'=>$error);
          }else {
            pesan('danger','msg','Gagal!',$error,"$url");
          }
        }
    }else {
      return $file;
    }
  }
}

function resizeImage($filename='',$path='')
{ $CI = &get_instance();
  if(!file_exists($filename)){ return '1'; }
  if (empty($_FILES[$filename])) { return '1'; }
  if($path==''){$path='assets/uploads';}
      $source_path = './'.$filename;
      $target_path = './'.$path.'/';
      $default_width  = 600;
      $default_height = 600;
      $config_manip = array(
          'image_library' => 'gd2',
          'source_image'  => $source_path,
          'create_thumb'  => FALSE,
          'maintain_ratio' => TRUE,
          'quality'       => '90%',
          'width'         => $default_width,
          'height'        => $default_height,
          'master_dim'    => 'auto',
          'new_image'     => $target_path,
      );
      $CI->image_lib->initialize($config_manip);
      if ($CI->image_lib->resize()) {
        $CI->image_lib->clear();
        if (file_exists($filename)) { unlink($filename); }
        return '1';
      }else{
        $CI->image_lib->clear();
        return $CI->image_lib->display_errors();
      }
}
// ============================================================

//======================== TANGGAL ============================
function tgl_now($aksi='')
{
 date_default_timezone_set('Asia/Jakarta');
   if ($aksi=='tgl') {
      $v = date('Y-m-d');
   }elseif ($aksi=='jam') {
      $v = date('H:i:s');
   }elseif ($aksi=='x') {
      $v = date('YmdHis');
   }else {
      $v = date('Y-m-d H:i:s');
   }
   return $v;
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

function tgl_format($date,$format,$custom='')
{
  if ($custom=='') {
    return date($format,strtotime($date));
  }else {
    return date($format,strtotime($custom, strtotime($date)));
  }
}

function tgl_id($date, $format='')
{
   date_default_timezone_set('Asia/Jakarta');
   if ($format!='') {
     $date = tgl_format($date,$format);
   }
   $str = explode('-', $date);
   $hasil = $str['0'] . " " . bln_id($str[1]) . " " .$str[2];
   return $hasil;
}

function bln_id($bln)
{
  date_default_timezone_set('Asia/Jakarta');
    $bulan = array(
      '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni',
      '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember',
    );
  return $bulan[$bln];
}

function hari_id($tanggal)
{
  $day = date('D', strtotime($tanggal));
  $dayList = array( 'Sun'=>'Minggu', 'Mon'=>'Senin', 'Tue'=>'Selasa', 'Wed'=>'Rabu', 'Thu'=>'Kamis', 'Fri'=>"Jum'at", 'Sat'=>'Sabtu');
  return $dayList[$day];
}

function waktu($data='')
{
  if($data==''){ $data=tgl_now(); }
  $tgl_n = date('d-m-Y H:i:s',strtotime($data));
  $hari = hari_id($tgl_n);
  $tgl  = tgl_id($tgl_n);
  return $hari.", ".$tgl;
}
//=============================================================================

function cek_file($file='')
{
  $data = "img/null.png";
  if ($file != '') {
    if(file_exists("$file")){
      $data = $file;
    }
  }
  return $data;
}

function cek_foto($file='',$stt='null.png')
{
  $data = "img/$stt";
  if ($file != '') {
    if(file_exists("$file")){
      $data = $file;
    }
  }
  return $data;
}

function format_angka($data='',$data2='')
{
  if($data==''){ return 0; }
  $v = number_format($data,0,",",".");
  if ($data2=='rp') {
    $v = "Rp. ".$v;
  }
  return $v;
}

function singkatkan_nilai($data='')
{
  if (strlen($data) >= 4) {
    return format_angka(substr($data,0,-3)).'K';
  }else {
    return format_angka($data);
  }
}

function khususAngka($number=0)
{
  $fix = preg_replace('/[^0-9]+/', '', $number);
  if ($number < 0){ $fix = '-'.$fix; }
  return $fix;
}

// ==========================================
// Encrypt
function encode($string='',$base='')
{ error_reporting(0);
  $CI = &get_instance();
  if ($base=='64') {
    $cryptKey = $CI->config->item('encryption_key');
    $id = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5( $cryptKey), $string, MCRYPT_MODE_CBC, md5(md5($cryptKey))));
  }else {
    $id = $CI->encrypt->encode($string);
  }
  $id = str_replace("/", "==11==", $id);
  $id = str_replace("+", "==22==", $id);
  return $id;
}

// Decrypt
function decode($string='',$base='')
{ error_reporting(0);
  $CI = &get_instance();
  $id = str_replace("==11==", "/", $string);
  $id = str_replace("==22==", "+", $id);
  if ($base=='64') {
    $string = $id;
    $cryptKey = $CI->config->item('encryption_key');
    $id = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($cryptKey), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($cryptKey))), "\0");
  }else {
    $id = $CI->encrypt->decode($id);
  }
  return $id;
}
// ==========================================

function cekAjaxRequest()
{ $CI = &get_instance();
    if (!$CI->input->is_ajax_request()) {
        $CI->session->set_flashdata('failed', $CI->lang->line('access_denied'));
        redirect('/');
    }
}

function phone_country($phone='', $kode='')
{
  if (strpos($phone, '+')) {
    return $kode.substr($phone, 1);
  }else {
    if (substr($phone, 0,1)=='0') {
      return $kode.substr($phone, 1);
    }else {
      return $phone;
    }
  }
}


function KonDecRomawi($angka=0)
{
    $hsl = "";
    if ($angka < 1 || $angka > 5000) {
        // Statement di atas buat nentuin angka ngga boleh dibawah 1 atau di atas 5000
        $hsl = "Batas Angka 1 s/d 5000";
    } else {
        while ($angka >= 1000) {
            // While itu termasuk kedalam statement perulangan
            // Jadi misal variable angka lebih dari sama dengan 1000
            // Kondisi ini akan di jalankan
            $hsl .= "M";
            // jadi pas di jalanin , kondisi ini akan menambahkan M ke dalam
            // Varible hsl
            $angka -= 1000;
            // Lalu setelah itu varible angka di kurangi 1000 ,
            // Kenapa di kurangi
            // Karena statment ini mengambil 1000 untuk di konversi menjadi M
        }
    }


    if ($angka >= 500) {
        // statement di atas akan bernilai true / benar
        // Jika var angka lebih dari sama dengan 500
        if ($angka > 500) {
            if ($angka >= 900) {
                $hsl .= "CM";
                $angka -= 900;
            } else {
                $hsl .= "D";
                $angka-=500;
            }
        }
    }
    while ($angka>=100) {
        if ($angka>=400) {
            $hsl .= "CD";
            $angka -= 400;
        } else {
            $angka -= 100;
        }
    }
    if ($angka>=50) {
        if ($angka>=90) {
            $hsl .= "XC";
            $angka -= 90;
        } else {
            $hsl .= "L";
            $angka-=50;
        }
    }
    while ($angka >= 10) {
        if ($angka >= 40) {
            $hsl .= "XL";
            $angka -= 40;
        } else {
            $hsl .= "X";
            $angka -= 10;
        }
    }
    if ($angka >= 5) {
        if ($angka == 9) {
            $hsl .= "IX";
            $angka-=9;
        } else {
            $hsl .= "V";
            $angka -= 5;
        }
    }
    while ($angka >= 1) {
        if ($angka == 4) {
            $hsl .= "IV";
            $angka -= 4;
        } else {
            $hsl .= "I";
            $angka -= 1;
        }
    }

    return ($hsl);
}

function konversi_satuan($kat='', $dari='', $ke='', $nilai=0, $find_field='kode')
{
  if ($kat==''){ return ''; }
  $CI = &get_instance();
  $CI->db->select('no_urut');
  $CI->db->limit(1);
  $get_1 = get('item_satuan', array('id_item_satuan_kategori'=>$kat, "$find_field"=>strtoupper($dari)))->row();
  if (empty($get_1)) { return ''; }
  $CI->db->select('no_urut');
  $CI->db->limit(1);
  $get_2 = get('item_satuan', array('id_item_satuan_kategori'=>$kat, "$find_field"=>strtoupper($ke)))->row();
  if (empty($get_2)) { return ''; }
  $total = $get_1->no_urut - $get_2->no_urut;
  $hasil = 0;
  $val   = 1;
  if ($total < 0) { //jika minus atau kurang dari 0 maka KALI 10
    for ($i=1; $i <=abs($total); $i++) {
      $val = $val * 10;
    }
    $hasil = $val;
  }elseif ($total >= 1 ) { //jika plus atau lebih sama dengan dari 1 maka BAGI 10
    for ($i=1; $i <=abs($total); $i++) {
      $val = $val / 10;
    }
    $hasil = $val;
  }else {
    $hasil = 1;
  }
  $hasil = $hasil*$nilai;
  return $hasil;
}

function konversi_satuan_harga($kat='', $kode_satuan='', $isian='', $harga=0)
{
  if ($kat==''){ return ''; }
  if ($kode_satuan=='G' AND $isian=='1') {
    return $harga*10;
  }elseif ($kode_satuan=='B' AND $isian=='55') {
    return $harga*2;
  }else {
    return $harga;
  }
}

function str_to_number($string){
  $string = strtolower($string);
  $no=1;
  for ($i='a'; $i <='z'; $i++) {
    if ($i==$string) {
      return $no; exit;
    } $no++;
  }
  return '';
}

?>
