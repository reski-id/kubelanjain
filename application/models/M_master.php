<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_master extends CI_Model
{
  var $item_master        = 'item_master';
  var $item_master_sub    = 'item_master_sub';
  var $set_fee            = 'set_fee';
  var $item_satuan        = 'item_satuan';
  var $tabel_pasar        = 'pasar';
  var $pasar_kecamatan    = 'pasar_kecamatan';
  var $tabel_toko         = 'toko';
  var $tabel_toko_harga   = 'toko_harga';
  var $tabel_pelanggan    = 'pelanggan';
  var $tabel_sales        = 'sales';
  var $item_kategori      = 'item_kategori';
  var $item_lokasi        = 'item_lokasi';
  var $item_lokasi_detail = 'item_lokasi_detail';
  var $order              = 'order';
  var $tabel_jekel        = 'jekel';
  var $tabel_video        = 'video';
  var $tabel_slide        = 'slide';
  var $approval           = 'approval';
  var $approval_tipe      = 'approval_tipe';
  var $tabel_pesan        = 'tabel_pesan';

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

  public function add_set_fee($id='')
  {
    if (!check_permission('view', 'update', 'master/set_fee')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    $tbl = $this->set_fee; $pesan='';
    $max_fee = khususAngka(post('max_fee'));
    update_data($tbl, array('value_in'=>$max_fee, 'tgl_update'=>tgl_now()), array("tipe_in"=>0, "tipe_out"=>0));
    $this->db->where('tipe_in!=0 AND tipe_out!=0');
    $this->db->order_by('id_set_fee', 'ASC');
    foreach (get('set_fee')->result() as $key => $value)
    {
      $id = $value->id_set_fee;
      $post['value_in']   = khususAngka(post($id.'_in'));
      $post['value_out']  = khususAngka(post($id.'_out'));
      $post['tgl_update'] = tgl_now();
      $total = $post['value_in'] + $post['value_out'];
      if ($total > $max_fee) {
        $pesan = 'Fee tidak boleh melebihi '.format_angka($max_fee);
        break;
      }else {
        $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
        if (!$simpan) { break; }
      }
    }

    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='Data berhasil disimpan';
    }else {
      $this->db->trans_rollback();
      $stt=0; if ($pesan==''){ $pesan='Gagal Simpan, silahkan coba lagi!'; }
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }

  function add_approval_tipe($id='')
  {
    $id_userx = get_session('id_user');
    $nama_input = "$id_userx - ".user('nama_lengkap');
    $tgl_input = tgl_now();

    $tbl     = $this->approval_tipe;
    $tbl_app = $this->approval;
    $approval_tipe = post('nama_approval_tipe');
    $post['nama_'.$tbl] = $approval_tipe;
    $post['ket']    = post('ket');
    $post['status'] = 1;

    if (count(post('id_user', 1))==0)          { $this->ajax_failed('User Wajib diisi!'); }
    if (count(post('id_user_approval', 1))==0) { $this->ajax_failed('User Approval Wajib diisi!'); }
    $id_usernya          = array_unique(json_decode(html_entity_decode($this->input->post('id_user'))));
    $id_user_approvalnya = array_unique(json_decode(html_entity_decode($this->input->post('id_user_approvalnya'))));
    // log_r($id_usernya);
    if ($id!='') { //jika edit & dihapus item
      if (!empty($id_user_approvalnya)) {
        $this->db->where('id_approval_tipe', $id);
        $this->db->where_not_in('id_user', $id_usernya);
        // $this->db->where_not_in('id_user_approval', $id_user_approvalnya);
        $this->db->delete($tbl_app);
      }
    }

    if ($id=='') {
      if (!check_permission('view', 'create', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $this->db->select('nama_'.$tbl);
      $get = get($tbl, array('nama_'.$tbl=>$approval_tipe))->row();
      if (!empty($get)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Nama Approval Tipe '$approval_tipe' sudah ada!"));
        exit;
      }
      $post['tgl_input'] = tgl_now();
      $post['input_by']  = get_session('id_user');
      $simpan = add_data($tbl, $post);
      $id_approval_tipe = $this->db->insert_id();
    }else{
      if (!check_permission('view', 'update', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $this->db->select('nama_'.$tbl);
      $get_old = get_field($tbl, array('id_'.$tbl=>$id))['nama_'.$tbl];
      $this->db->select('nama_'.$tbl);
      $get = get($tbl, array('nama_'.$tbl=>$approval_tipe, "nama_$tbl !="=>$get_old))->row();
      if (!empty($get)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Nama Approval Tipe '$approval_tipe' sudah ada!"));
        exit;
      }
      $post['tgl_update'] = tgl_now();
      $post['update_by']  = get_session('id_user');
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
      $id_approval_tipe = $id;
    }
    if ($simpan) {
      $post1=array(); $post1_1=array();
      $post1_save=false; $i=0;
      foreach ($id_usernya as $k => $id_user) {
        foreach ($id_user_approvalnya as $key => $value) {
          $id_user_approval = $value;
          if ($id=='') {
            $post1[$i]['id_approval_tipe'] = $id_approval_tipe;
            $post1[$i]['id_user']          = $id_user;
            $post1[$i]['id_user_approval'] = $id_user_approval;
            $post1[$i]['tgl_input']  = $tgl_input;
            $post1[$i]['input_by']   = $nama_input;
            $post1_save=true;
          }else {
            $this->db->select('id_approval');
            $cek_app = get_field($tbl_app, array('id_approval_tipe'=>$id_approval_tipe, 'id_user'=>$id_user, 'id_user_approval'=>$id_user_approval));
            if (empty($cek_app)) { //jika tidak ada
              $post1[$i]['id_approval_tipe'] = $id_approval_tipe;
              $post1[$i]['id_user']          = $id_user;
              $post1[$i]['id_user_approval'] = $id_user_approval;
              $post1[$i]['tgl_input']  = $tgl_input;
              $post1[$i]['input_by']   = $nama_input;
              $post1_save=true;
            }else {
              $post1_1[$i]['id_approval']      = $cek_app['id_approval'];
              $post1_1[$i]['id_approval_tipe'] = $id_approval_tipe;
              $post1_1[$i]['id_user']          = $id_user;
              $post1_1[$i]['id_user_approval'] = $id_user_approval;
              $post1_1[$i]['tgl_update']  = $tgl_input;
              $post1_1[$i]['update_by']   = $nama_input;
              $post1_save=true;
            }
          }
          $i++;
        }
      }
      $simpan=false;
      if ($post1_save) {
        if ($id=='') {
          if (!empty($post1)) { $simpan = add_batch($tbl_app, $post1); }
        }else{
          if (!empty($post1)) { $simpan = add_batch($tbl_app, $post1); }else{ $simpan = true; }
          if ($simpan) {
            if (!empty($post1_1)) {
              $simpan = update_batch($tbl_app, $post1_1, "id_approval");
            }else {
              $simpan = true;
            }
          }
        }
      }
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

  function add_approval($id_user='', $id='')
  {
    $id_userx = get_session('id_user');
    $nama_input = "$id_userx - ".user('nama_lengkap');
    // log_r($nama_input);
    $tgl_input = tgl_now();
    $tbl = 'approval';

    if ($id=='') {
      $this->ajax_permission('create', 'user_management/data_user');
      $id_approval_tipe = post('id_approval_tipe');
    }else {
      $this->ajax_permission('update', 'user_management/data_user');
      $id_approval_tipe = $id;
    }

    if ($id_approval_tipe=='')    { $this->ajax_failed('Tipe Approval Wajib diisi!'); }
    if (count(post('id_user_approval', 1))==0) { $this->ajax_failed('User Approval Wajib diisi!'); }

    $id_user_approvalnya = array_unique(json_decode(html_entity_decode($this->input->post('id_user_approvalnya'))));
    if ($id!='') { //jika edit & dihapus item
      if (!empty($id_user_approvalnya)) {
        $this->db->where('id_approval_tipe', $id_approval_tipe);
        $this->db->where('id_user', $id_user);
        $this->db->where_not_in('id_user_approval', $id_user_approvalnya);
        $this->db->delete($tbl);
      }
    }

    $post=array(); $post_1=array();
    $post_save=false;
    foreach ($id_user_approvalnya as $key => $value) {
      $id_user_approval = $value;
      if ($id=='') {
        $post[$key]['id_approval_tipe'] = $id_approval_tipe;
        $post[$key]['id_user']          = $id_user;
        $post[$key]['id_user_approval'] = $id_user_approval;
        $post[$key]['tgl_input']  = $tgl_input;
        $post[$key]['input_by']   = $nama_input;
        $post_save=true;
      }else {
        $this->db->select('id_approval');
        $cek_app = get_field($tbl, array('id_approval_tipe'=>$id_approval_tipe, 'id_user'=>$id_user, 'id_user_approval'=>$id_user_approval));
        if (empty($cek_app)) { //jika tidak ada
          $post[$key]['id_approval_tipe'] = $id_approval_tipe;
          $post[$key]['id_user']          = $id_user;
          $post[$key]['id_user_approval'] = $id_user_approval;
          $post[$key]['tgl_input']  = $tgl_input;
          $post[$key]['input_by']   = $nama_input;
          $post_save=true;
        }else {
          $post_1[$key]['id_approval']      = $cek_app['id_approval'];
          $post_1[$key]['id_approval_tipe'] = $id_approval_tipe;
          $post_1[$key]['id_user']          = $id_user;
          $post_1[$key]['id_user_approval'] = $id_user_approval;
          $post_1[$key]['tgl_update']  = $tgl_input;
          $post_1[$key]['update_by']   = $nama_input;
          $post_save=true;
        }
      }
    }
    $simpan=false;
    if ($post_save) {
      if ($id=='') {
        if (!empty($post)) { $simpan = add_batch($tbl, $post); }
      }else{
        if (!empty($post)) { $simpan = add_batch($tbl, $post); }else{ $simpan = true; }
        if ($simpan) {
          if (!empty($post_1)) {
            $simpan = update_batch($tbl, $post_1, "id_approval");
          }else {
            $simpan = true;
          }
        }
      }
    }
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else{
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }

  function approval_hapus($id_user='')
  {
    $this->ajax_permission('delete', 'user_management/data_user');
    $tbl = 'approval';
    $this->db->trans_begin();
    $hapus = delete_data($tbl, array('id_approval_tipe'=>decode(post('id')), 'id_user'=>decode($id_user)));
    if ($hapus) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else{
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }

  public function item_satuan($id='')
  {
    if (!check_permission('view', 'create', 'master/item_satuan')) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
      exit;
    }
    $tbl = $this->item_satuan;
    $post['kode']        = post('kode');
    $post['item_satuan'] = post('item_satuan');
    if ($id!='') {
      $this->db->select('kode');
      $kode_old = get_field($tbl, array('id_item_satuan'=>$id))['kode'];
      $this->db->select('kode');
      $this->db->where('kode!=', $kode_old);
    }
    $cek = get($tbl, array('kode'=>$post['kode']))->row();
    if (!empty($cek)) {
      $this->ajax_failed('Kode <b>'.$post['kode'].'</b> sudah Ada');
    }
    if ($id=='') {
      $post['input_date'] = tgl_now();
      $post['status']   = 1;
      $post['input_by'] = get_session('id_user');
      $simpan = add_data($tbl,$post);
    }else{
      $post['status']    = post('status');
      $post['update_date'] = tgl_now();
      $post['update_by'] = get_session('id_user');
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

// MASTER PLU - ITEM
  function item_kategori($id='')
  {
    $tbl = $this->item_kategori;
    $post = post_all('simpan');
    $kode = post('kode');
    if ($id!='') {
      $this->db->select('kode');
      $data_lama = get_field($tbl, array("id_$tbl"=>$id));
      $this->db->where('kode!=', $data_lama['kode']);
    }
    $get = get($tbl, array('kode'=>$kode))->row();
    if (!empty($get)) {
      echo json_encode(array("stt"=>0, 'pesan'=>"Kode '$kode' sudah ada!"));
      exit;
    }
    if ($id=='') {
      $post['status'] = 1;
      $post['input_date'] = tgl_now();
      $post['input_by'] = get_session('id_user');
      if (!check_permission('view', 'create', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $simpan = add_data($tbl,$post);
    }else{
      $post['update_date'] = tgl_now();
      $post['update_by'] = get_session('id_user');
      if (!check_permission('view', 'update', 'master/'.$tbl)) {
        echo json_encode(array("stt"=>0, 'pesan'=>"Permission Denied!"));
        exit;
      }
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

// ITEM MASTER
  function item_master($id='')
  {
    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tbl = $this->item_master;

    if ($id=='') {
      $this->ajax_permission('create', 'master/item_master');
    }else {
      $this->ajax_permission('update', 'master/item_master');
    }
    $tgl_input = tgl_now();

    // CEK PLU
    $plu = post('plu');
    $kode_dept = substr($plu,0,2);
    $this->db->select('plu, foto_item');
    $get = get($tbl, array('plu'=>$plu))->row();
    if ($id=='') {
      if (!empty($get)) { $this->ajax_failed('PLU sudah ada!'); }
    }else{
      if (empty($get)) { $this->ajax_failed('PLU tidak ditemukan!'); }
    }

    $id_item_kategori = post('id_item_kategori');
    $nama_item        = strtoupper(post('nama_item'));
    $nilai_satuan     = post('nilai_satuan');
    $id_item_satuan   = post('id_item_satuan');


    // VALIDASI
    if ($id_item_kategori=='') { $this->ajax_failed('ITEM KATEGORI Wajib diisi!'); }
    if ($plu=='')              { $this->ajax_failed('PLU Wajib diisi!'); }
    if ($nama_item=='')        { $this->ajax_failed('NAMA ITEM Wajib diisi!'); }
    if(!preg_match("/^[a-zA-Z0-9 ]*$/", $nama_item)) {
        $this->ajax_failed("Input NAMA ITEM hanya boleh huruf dan angka....!");
    }


    if ($id=='') {
      $post['id_item_kategori']  = $id_item_kategori;
      $post['plu']               = $plu;
    }
    $post['nama_item']      = $nama_item;

    // FOTO ITEM
    $path= "img/item/$id_item_kategori";
    createPath($path, 0777);
    upload_config($path,'5','jpeg|jpg|png|gif|bmp');
    $foto_lama='';
    if ($id!='') { $foto_lama=$get->foto_item; }
    $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
    if (!empty($foto['pesan'])) {
      $this->ajax_failed($foto['pesan']);
    }else {
      $stt=1; $post['foto_item'] = $foto;
      $cek_resize = resizeImage($foto,$path);
      if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
    }

    if ($id=='') {
      $post['status']     = 1;
      $post['tgl_input']  = $tgl_input;
      $post['input_by']   = $nama_input;
    }else {
      $post['tgl_update'] = $tgl_input;
      $post['update_by']  = $nama_input;
    }
    if ($id=='') {
      $simpan = add_data($tbl, $post);
    }else{
      $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
    }

    if ($simpan) {
      $this->db->trans_commit();
      if ($foto != $foto_lama) {
        if (file_exists($foto_lama)) { unlink($foto_lama); }
      }
      echo json_encode(array('stt'=>1, 'pesan'=>'Item Master Berhasil disimpan!'));
      exit;
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }


  function item_master_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/item_master');
    $tbl = $this->item_master;

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
// END ITEM MASTER

// SUB ITEM MASTER
  function item_master_sub($id='')
  {
    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tbl = $this->item_master_sub;

    if ($id=='') {
      $this->ajax_permission('create', 'master/item_master_sub');
    }else {
      $this->ajax_permission('update', 'master/item_master_sub');

      $this->db->select('plu_sub, foto_item_sub');
      $get_old = get_field($tbl, array("id_$tbl"=>$id));
      if (empty($get_old)){ $this->ajax_failed(); }

      $this->db->where('plu_sub!=', $get_old['plu_sub']);
    }
    $this->db->select('plu_sub');
    $cek_data = get_field($tbl, array('plu_sub'=>post('plu_sub')));
    if (!empty($cek_data)){ $this->ajax_failed('Item, Nilai Satuan & Satuan sudah Ada!'); }

    $tgl_input = tgl_now();
    // item master
    $this->db->select("A.id_item_master, A.plu, A.nama_item, A.id_item_kategori");
    $get_item = get_field('item_master AS A', array('A.status'=>1, 'id_item_master'=>post('id_item_master')));
    if (empty($get_item)){ $this->ajax_failed('Item tidak ditemukan!'); }
    $plu       = $get_item['plu'];
    $nama_item = $get_item['nama_item'];
    // item satuan
    $this->db->select('kode, item_satuan');
    $get_satuan = get_field('item_satuan', array('id_item_satuan'=>post('id_item_satuan')));
    if (empty($get_satuan)){ $this->ajax_failed('Satuan tidak ditemukan!'); }
    $plu_sub = $plu.$get_satuan['kode'].post('nilai_satuan');

    $post['id_item_master']   = $get_item['id_item_master'];
    $post['id_item_kategori'] = $get_item['id_item_kategori'];
    $post['item_kategori']    = get_name_item_kategori($get_item['id_item_kategori']);
    $post['plu']              = $plu;
    $post['plu_sub']          = $plu_sub;
    $post['nilai_satuan']     = post('nilai_satuan');
    $post['id_item_satuan']   = post('id_item_satuan');
    $post['kode_satuan']      = $get_satuan['kode'];
    $post['nama_item']        = "$nama_item ".$post['nilai_satuan']." ".$get_satuan['item_satuan'];

    // FOTO ITEM
    $path= "img/item_sub/".$get_item['id_item_master'];
    createPath($path, 0777);
    upload_config($path,'5','jpeg|jpg|png|gif|bmp');
    $foto_lama='';
    if ($id!='') { $foto_lama=$get_old->foto_item_sub; }
    $foto = upload_file('foto',$path,'ajax',$foto_lama, 1);
    if (!empty($foto['pesan'])) {
      $this->ajax_failed($foto['pesan']);
    }else {
      $stt=1; $post['foto_item_sub'] = $foto;
      $cek_resize = resizeImage($foto,$path);
      if ($cek_resize!=1) { $stt=0; $pesan = 'Maaf, Upload Foto Gagal!'; }
    }

    if ($id=='') {
      $post['status']     = 1;
      $post['tgl_input']  = $tgl_input;
      $post['input_by']   = $nama_input;
    }else{
      $post['tgl_update'] = $tgl_input;
      $post['update_by']  = $nama_input;
    }
    if ($id=='') {
      $simpan = add_data($tbl, $post);
    }else{
      $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
    }

    if ($simpan) {
      $this->db->trans_commit();
      $stt=1; $pesan='Data berhasil disimpan';
    }else{
      $this->db->trans_rollback();
      $stt=0; $pesan='Gagal Simpan, silahkan coba lagi!';
    }
    echo json_encode(array('stt'=>$stt, 'pesan'=>$pesan));
    exit;
  }


  function item_master_sub_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/item_master_sub');
    $tbl = $this->item_master_sub;

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
// END SUB MASTER


//START Pasar
  public function pasar_simpan($id='')
  {
    $this->ajax_permission('create', 'master/pasar');
    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tgl_now = tgl_now();
    $tbl  = $this->tabel_pasar;
    $tbl2 = $tbl.'_kecamatan';
    $post['id_provinsi']  = post('id_provinsi');
    $post['id_kota']      = post('id_kota');
    $post['persentase']      = post('persentase');
    $post['id_kecamatan'] = 0;//post('id_kecamatan');
    $post['pasar']        = strtoupper(post('pasar'));
    $kec_arr = array_unique(json_decode(html_entity_decode($this->input->post('id_kecamatan'))));
    if (empty($kec_arr)) {
      $this->ajax_failed('Kecamatan Wajib diisi!');
    }

    if ($id!='') { //jika edit
      $this->db->select("id_provinsi, id_kota, pasar");
      $data_lama = get_field($tbl, array("id_$tbl"=>$id));
    }

    $this->db->select("A.id_$tbl, B.id_kecamatan, C.kecamatan");
    $this->db->join("$tbl2 AS B", "A.id_pasar=B.id_pasar");
    $this->db->join("kecamatan AS C", "C.id_kecamatan=B.id_kecamatan");
    if ($id!='') {
      $this->db->where("(A.id_provinsi!='$data_lama[id_provinsi]' AND A.id_provinsi='$post[id_provinsi]')");
      $this->db->or_where("(A.id_kota!='$data_lama[id_kota]' AND A.id_kota='$post[id_kota]')");
      $this->db->or_where("(A.pasar!='$data_lama[pasar]' AND A.pasar='$post[pasar]')");
    }else {
      $this->db->where("A.id_provinsi", $post['id_provinsi']);
      $this->db->where("A.id_kota", $post['id_kota']);
      $this->db->where("A.pasar", $post['pasar']);
    }
    $this->db->where_in('B.id_kecamatan', $kec_arr);
    $this->db->group_by("B.id_kecamatan");
    $cek = get("$tbl AS A")->result();
    if (!empty($cek)) {
      $kec='';
      foreach ($cek as $key => $value) {
        if (in_array($value->id_kecamatan, $kec_arr)) {
          $kec .= $value->kecamatan.', ';
        }
      }
      $kec = substr($kec, 0, -2);
      $this->ajax_failed("Nama Pasar '$post[pasar]' di kecamatan '$kec' sudah ada!");
    }
    // log_r($this->db->last_query());

    $id_new=$id;
    if ($id=='') {
      $post['tgl_input'] = $tgl_now;
      $post['status']   = 1;
      $post['input_by'] = $nama_input;
      $simpan = add_data($tbl,$post);
      if ($simpan) {
        $id_new = $this->db->insert_id();
      }else {
        $this->db->trans_rollback();
        $this->ajax_failed('Gagal Simpan, silahkan coba lagi!!');
      }
    }else{
      $post['status']    = post('status');
      $post['tgl_update'] = $tgl_now;
      $post['update_by'] = $nama_input;
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
    }

    // Start Proses Multi Kecamatan
    if ($id!='') { //jika edit & dihapus
      if (!empty($kec_arr)) {
        $this->db->where('id_pasar', $id);
        $this->db->where('status', 1);
        $this->db->where_not_in('id_kecamatan', $kec_arr);
        $this->db->update($tbl2, array('status'=>0, 'tgl_update'=>$tgl_now, 'update_by'=>$nama_input));
      }
    }
    $post_kec=array(); $post_kec_1=array(); $post_kec_save=false;
    foreach ($kec_arr as $key => $id_kecamatan) {
      if ($id=='') { //jika tambah
        $post_kec[$key]['id_provinsi']  = $post['id_provinsi'];
        $post_kec[$key]['id_kota']      = $post['id_kota'];
        $post_kec[$key]['id_kecamatan'] = $id_kecamatan;
        $post_kec[$key]['id_pasar']     = $id_new;
        $post_kec[$key]['status']       = 1;
        $post_kec[$key]['tgl_update']   = $tgl_now;
        $post_kec[$key]['update_by']    = $nama_input;
      }else { //jika edit
        $this->db->select("id_pasar_kecamatan as id");
        $get_kec = get_field($tbl2, array('id_pasar'=>$id_new, 'id_kecamatan'=>$id_kecamatan));
        if (empty($get_kec)) { //jika tidak ada
          $post_kec[$key]['id_provinsi']  = $post['id_provinsi'];
          $post_kec[$key]['id_kota']      = $post['id_kota'];
          $post_kec[$key]['id_kecamatan'] = $id_kecamatan;
          $post_kec[$key]['id_pasar']     = $id_new;
          $post_kec[$key]['status']       = 1;
          $post_kec[$key]['tgl_update']   = $tgl_now;
          $post_kec[$key]['update_by']    = $nama_input;
        }else {
          $post_kec_1[$key]['id_provinsi']  = $post['id_provinsi'];
          $post_kec_1[$key]['id_kota']      = $post['id_kota'];
          $post_kec_1[$key]['id_kecamatan'] = $id_kecamatan;
          $post_kec_1[$key]['id_'.$tbl2]  = $get_kec['id'];
          $post_kec_1[$key]['id_pasar']   = $id_new;
          $post_kec_1[$key]['status']     = 1;
          $post_kec_1[$key]['tgl_update'] = $tgl_now;
          $post_kec_1[$key]['update_by']  = $nama_input;
        }
      }
    }
    if (!empty($post_kec)) {
      $simpan = add_batch($tbl2, $post_kec);
    }else {
      $simpan = true;
    }
    if ($simpan) {
      if (!empty($post_kec_1)) {
        $simpan = update_batch($tbl2, $post_kec_1, "id_$tbl2");
      }else {
        $simpan = true;
      }
    }
    // end Proses Multi Kecamatan

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

  function pasar_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/pasar');
    $tbl = $this->tabel_pasar;

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
  //END PASAR

  //START TOKO
public function toko_simpan($id='')
{
  $this->ajax_permission('create', 'master/toko');
  $id_user = get_session('id_user');
  $nama_input = "$id_user - ".user('nama_lengkap');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_toko;
  $post['id_provinsi']  = post('id_provinsi');
  $post['id_kota']      = post('id_kota');
  $post['id_kecamatan'] = post('id_kecamatan');
  $post['id_pasar']     = post('id_pasar');
  $post['toko']         = strtoupper(post('toko'));
  $post['nama_pemilik'] = strtoupper(post('nama_pemilik'));
  $post['nohp_satu']    = strtoupper(post('nohp_satu'));
  $post['nohp_dua']     = strtoupper(post('nohp_dua'));
  $post['note']         = strtoupper(post('note'));

  if ($id!='') { //jika edit
    $this->db->select("id_provinsi, id_kota, id_kecamatan, toko");
    $data_lama = get_field($tbl, array("id_$tbl"=>$id));
  }

  $this->db->select("id_$tbl");
  if ($id!='') {
    $this->db->where_not_in("toko", array('id_provinsi'=>$data_lama['id_provinsi'], 'id_kota'=>$data_lama['id_kota'], 'id_kecamatan'=>$data_lama['id_kecamatan'], 'toko'=>$data_lama['toko']));
  }
  $cek = get($tbl, $post)->row();
  if (!empty($cek)) {
    $this->ajax_failed('Nama toko diwilayah ini sudah ada!');
  }
  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $post['input_by'] = $nama_input;
    $simpan = add_data($tbl,$post);
  }else{
    $post['status']    = post('status');
    $post['tgl_update'] = $tgl_now;
    $post['update_by'] = $nama_input;
    $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

function toko_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/toko');
    $tbl = $this->tabel_toko;

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
  //END TOKO

  //START PELANGGAN
  public function pelanggan_simpan($id='')
  {
    $this->ajax_permission('create', 'master/pelanggan');
    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tgl_now = tgl_now();
    $tbl = $this->tabel_pelanggan;
    $post['id_provinsi']  = post('id_provinsi');
    $post['id_kota']      = post('id_kota');
    $post['id_kecamatan'] = post('id_kecamatan');
    $post['id_type']     = post('id_type');
    $post['id_pasar']     = post('id_pasar');
    $post['id_sales']     = post('id_sales');
    $post['id_jekel']     = post('id_jekel');
    $post['pelanggan']    = strtoupper(post('pelanggan'));
    $post['nohp_satu']    = strtoupper(post('nohp_satu'));
    $post['nohp_dua']     = strtoupper(post('nohp_dua'));
    $post['alamat']         = strtoupper(post('alamat'));

    if ($id!='') { //jika edit
      $this->db->select("id_provinsi, id_kota, id_kecamatan, pelanggan");
      $data_lama = get_field($tbl, array("id_$tbl"=>$id));
    }

    $this->db->select("id_$tbl");
    if ($id!='') {
      $this->db->where_not_in("pelanggan", array('id_provinsi'=>$data_lama['id_provinsi'], 'id_kota'=>$data_lama['id_kota'], 'id_kecamatan'=>$data_lama['id_kecamatan'], 'pelanggan'=>$data_lama['toko']));
    }
    $cek = get($tbl, $post)->row();
    if (!empty($cek)) {
      $this->ajax_failed('Nama toko diwilayah ini sudah ada!');
    }
    if ($id=='') {
      $post['tgl_input'] = $tgl_now;
      $post['status']   = 1;
      $post['input_by'] = $nama_input;
      $simpan = add_data($tbl,$post);
    }else{
      $post['status']    = post('status');
      $post['tgl_update'] = $tgl_now;
      $post['update_by'] = $nama_input;
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

  function pelanggan_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/pelanggan');
    $tbl = $this->tabel_pelanggan;

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

  //START HARGA
  public function harga_simpan($id='')
  {
    if ($id=='') {
      $this->ajax_permission('create', 'master/toko_harga');
    }else {
      $this->ajax_permission('update', 'master/toko_harga');
    }
    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');
    $tgl_now = tgl_now();
    $tbl = $this->tabel_toko_harga;
    $post['id_provinsi']  = post('id_provinsi');
    $post['id_kota']      = post('id_kota');
    $post['id_kecamatan'] = post('id_kecamatan');
    $post['id_pasar']     = post('id_pasar');
    $post['id_toko']      = post('id_toko');
    $post['id_item_master'] = post('id_item_master');
    $this->db->select('A.plu, A.nama_item, A.nilai_satuan, B.item_satuan');
    $this->db->join('item_satuan AS B', 'A.id_item_satuan=B.id_item_satuan');
    $get_item = get_field("item_master AS A", array("id_item_master"=>$post['id_item_master']));
    $nama_itemnya = $get_item['nama_item']. ' '.$get_item['nilai_satuan']. ' '.$get_item['item_satuan'];
    $post['nama_item']    = $nama_itemnya;
    $post['harga']        = preg_replace('/[Rp. ]/', '', post('harga'));
    $post['harga_beli']   = preg_replace('/[Rp. ]/', '', post('harga_beli'));
    $post['gap']          = preg_replace('/[Rp. ]/', '', post('gap'));

    // log_r($post);
    if ($id=='') {
      $post['tgl_input'] = $tgl_now;
      $post['status']    = 1;
      $post['input_by']  = $nama_input;
      $simpan = add_data($tbl,$post);
    }else{
      $post['status']     = post('status');
      $post['tgl_update'] = $tgl_now;
      $post['update_by']  = $nama_input;
      $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

  function harga_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/toko_harga');
    $tbl = $this->tabel_toko_harga;

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
  //END HARGA

// START ITEM LOKASI
  public function get_item_lokasi()
  {
    $tbl='item_master';
    $cari = post('cari');
    $checked_arr = array_unique(json_decode(html_entity_decode($this->input->post('selectnya'))));

    $this->db->select("A.id_item_master as idnya, A.plu, CONCAT(A.nama_item, ' ', A.nilai_satuan, ' ', B.item_satuan) AS nama_item");
    $this->db->join('item_satuan AS B', 'A.id_item_satuan=B.id_item_satuan');
    if (!empty($cari)) {
      $this->db->where("(A.plu LIKE '%". $cari."%' OR A.nama_item LIKE '%".$cari."%' OR A.nilai_satuan LIKE '%".$cari."%' OR B.item_satuan LIKE '%".$cari."%' OR CONCAT(A.nama_item, ' ', A.nilai_satuan, ' ', B.item_satuan) LIKE '%".$cari."%')",null,false);
    }
    $this->db->where('A.id_item_kategori', post('id_kat'));
    $this->db->order_by('A.nama_item', 'ASC');
    $get = get("$tbl AS A");
    $arr = array();

    foreach ($get->result_array() as $key => $value) {
      if (in_array($value['idnya'], $checked_arr)) {
        $value['checked'] = 1;
      }else {
        $value['checked'] = 0;
      }
      $arr[] = $value;
    }

    echo '{"detailnya":' . json_encode($arr).'}';



  }

  function item_lokasi($id='')
  {
    $stt = post('id');
    if ($id=='') {
      $this->ajax_permission('create', 'master/item_lokasi');
    }else {
      $this->ajax_permission('update', 'master/item_lokasi');
    }
    $tbl  = $this->item_lokasi;
    $tbl1 = $this->item_lokasi_detail;
    $tbl2 = $this->item_master;
    $tbl3 = $this->pasar_kecamatan;

    $post['id_provinsi']      = post('id_provinsi');
    $post['id_kota']          = post('id_kota');
    $post['id_kecamatan']     = post('id_kecamatan');
    $post['id_pasar']         = post('id_pasar');
    $post['id_item_kategori'] = post('id_item_kategori');
    $checked_arr = array_unique(json_decode(html_entity_decode($this->input->post('checked_box'))));

    if ($post['id_provinsi']=='')      { $this->ajax_failed('PROVINSI Wajib diisi!'); }
    if ($post['id_kota']=='')          { $this->ajax_failed('KOTA Wajib diisi!'); }
    if ($post['id_kecamatan']=='')     { $this->ajax_failed('KECAMATAN Wajib diisi!'); }
    if ($post['id_pasar']=='')         { $this->ajax_failed('Pasar Wajib diisi!'); }
    if ($post['id_item_kategori']=='') { $this->ajax_failed('ITEM KATEGORI Wajib diisi!'); }
    if (empty($checked_arr))           { $this->ajax_failed('ITEM Belum dipilih!'); }

    $id_user = get_session('id_user');
    $tgl_input  = tgl_now();
    $nama_input = "$id_user - ".user('nama_lengkap');

    $data_arr = array();
    $this->db->select('id_kecamatan, id_pasar');
    if ($post['id_kecamatan']!=0) {
      $this->db->where('id_kecamatan', $post['id_kecamatan']);
    }
    if ($post['id_pasar']!=0) {
      $this->db->where('id_pasar', $post['id_pasar']);
    }
    foreach (get($tbl3, array('status'=>1, 'id_provinsi'=>$post['id_provinsi'], 'id_kota'=>$post['id_kota']))->result_array() as $key => $value) {
      $data_arr[] = $value;
    }
    if ($id!='') { //jika edit & dihapus
      if (!empty($checked_arr)) {
        $this->db->where("id_$tbl", $id);
        $this->db->where('status', 1);
        $this->db->where_not_in('id_item_master', $checked_arr);
        $this->db->update($tbl1, array('status'=>0, 'tgl_update'=>$tgl_input, 'update_by'=>$nama_input));
      }
    }
    $simpan=false; $i1=0; $i2=0; $post_1=array(); $post_2=array();
    foreach ($data_arr as $key => $value) {
      $id_new='';
      if ($id=='') {
        $post['id_kecamatan'] = $value['id_kecamatan'];
        $post['id_pasar']     = $value['id_pasar'];
        $post['status']       = 1;
        $post['tgl_input']    = $tgl_input;
        $post['input_by']     = $nama_input;
        $simpan = add_data($tbl, $post);
        if ($simpan) {
          $id_new = $this->db->insert_id();
        }
      }else {
        $post['id_kecamatan'] = $value['id_kecamatan'];
        $post['id_pasar']     = $value['id_pasar'];
        $post['status']       = 1;
        $post['tgl_update']   = $tgl_input;
        $post['update_by']    = $nama_input;
        $simpan = update_data($tbl, $post, array("id_$tbl"=>$id));
        $id_new = $id;
      }

      if ($simpan) {
        foreach ($checked_arr as $key => $value) {
          $id_item_master = $value;
          $this->db->select('A.plu, A.nama_item, A.nilai_satuan, B.item_satuan');
          $this->db->join('item_satuan AS B', 'A.id_item_satuan=B.id_item_satuan');
          $get_item = get_field("$tbl2 AS A", array("id_$tbl2"=>$id_item_master));
          $nama_itemnya = $get_item['nama_item']. ' '.$get_item['nilai_satuan']. ' '.$get_item['item_satuan'];
          if ($id=='') { //jika tambah
            $post_1[$i1]['id_item_lokasi'] = $id_new;
            $post_1[$i1]['id_item_master'] = $id_item_master;
            $post_1[$i1]['plu']            = $get_item['plu'];
            $post_1[$i1]['nama_item']      = $nama_itemnya;

            $post_1[$i1]['status']         = 1;
            $post_1[$i1]['tgl_input']      = $tgl_input;
            $post_1[$i1]['input_by']       = $nama_input;
            $i1++;
          }else { //jika edit
            $this->db->select("id_$tbl1 as id");
            $get_lokasi = get_field($tbl1, array('id_item_lokasi'=>$id_new, 'id_item_master'=>$id_item_master));
            if (empty($get_lokasi)) { //jika tidak ada
              $post_1[$i1]['id_item_lokasi'] = $id_new;
              $post_1[$i1]['id_item_master'] = $id_item_master;
              $post_1[$i1]['plu']            = $get_item['plu'];
              $post_1[$i1]['nama_item']      = $nama_itemnya;
              $post_1[$i1]['status']         = 1;
              $post_1[$i1]['tgl_input']      = $tgl_input;
              $post_1[$i1]['input_by']       = $nama_input;
              $i1++;
            }else { //jika ada
              $post_2[$i2]['nama_item']   = $nama_itemnya;
              $post_2[$i2]["id_$tbl1"]    = $get_lokasi['id'];
              $post_2[$i2]['status']      = 1;
              $post_2[$i2]['tgl_update']  = $tgl_input;
              $post_2[$i2]['update_by']   = $nama_input;
              $i2++;
            }
          }
        }
      }else {
        break;
      }
    }

    if (!empty($data_arr)) {
      if (!empty($post_1)) {
        $simpan = add_batch($tbl1, $post_1);
      }else {
        $simpan = true;
      }
      if ($simpan) {
        if (!empty($post_2)) {
          $simpan = update_batch($tbl1, $post_2, "id_$tbl1");
        }else {
          $simpan = true;
        }
      }
    }

    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }

  function item_lokasi_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/item_lokasi');
    $tbl  = $this->item_lokasi;
    $tbl1 = $this->item_lokasi_detail;

    $this->db->select("id_$tbl");
    $get_data = get_field($tbl, array("id_$tbl"=>$id));
    if (empty($get_data)) { $this->ajax_failed(); }

    $id_user = get_session('id_user');
    $nama_input = "$id_user - ".user('nama_lengkap');

    $where = array("id_$tbl"=>$id);
    $post  = array('status'=>$stt, 'tgl_update'=>tgl_now(), 'update_by'=>$nama_input);
    $simpan = update_data($tbl, $post, $where);
    if ($simpan) {
      $simpan = update_data($tbl1, $post, $where);
    }
    if ($simpan) {
      $this->db->trans_commit();
      echo json_encode(array('stt'=>1, 'pesan'=>''));
      exit;
    }else {
      $this->db->trans_rollback();
      $this->ajax_failed('Gagal, Silahkan dicoba lagi!');
    }
  }

  function cek_item_lokasi()
  {
    $this->db->select('id_kecamatan, id_pasar');
    if (post('id_kecamatan')!=0) {
      $this->db->where('id_kecamatan', post('id_kecamatan'));
    }
    if (post('id_pasar')!=0) {
      $this->db->where('id_pasar', post('id_pasar'));
    }
    if (post('id_item_kategori')!=0) {
      $this->db->where('id_item_kategori', post('id_item_kategori'));
    }
    $stt = get('item_lokasi', array('id_provinsi'=>post('id_provinsi'), 'id_kota'=>post('id_kota')))->num_rows();
    echo json_encode(array('stt'=>$stt));
    exit;
  }
// END ITEM LOKASI

 //START ORDER
 public function order_simpan($id='')
 {
   $this->ajax_permission('create', 'master/order');
   $id_user = get_session('id_user');
   $nama_input = "$id_user - ".user('nama_lengkap');
   $tgl_now = tgl_now();
   $tbl = $this->tabel_order;
   $post['id_order']       = post('id_order');
   $post['tanggal']        = strtoupper(post('tanggal'));
   $post['id_provinsi']    = post('id_provinsi');
   $post['id_kota']        = post('id_kota');
   $post['id_pelanggan']   = strtoupper(post('id_pelanggan'));


   if ($id!='') { //jika edit
     $this->db->select("id_provinsi, id_kota, id_kecamatan, order");
     $data_lama = get_field($tbl, array("id_$tbl"=>$id));
   }

   $this->db->select("id_$tbl");
   if ($id!='') {
     $this->db->where_not_in("order", array('id_provinsi'=>$data_lama['id_provinsi'], 'id_kota'=>$data_lama['id_kota'], 'id_kecamatan'=>$data_lama['id_kecamatan'], 'order'=>$data_lama['toko']));
   }
   $cek = get($tbl, $post)->row();
   if (!empty($cek)) {
     $this->ajax_failed('Nama toko diwilayah ini sudah ada!');
   }
   if ($id=='') {
     $post['tgl_input'] = $tgl_now;
     $post['status']   = 1;
     $post['input_by'] = $nama_input;
     $simpan = add_data($tbl,$post);
   }else{
     $post['status']    = post('status');
     $post['tgl_update'] = $tgl_now;
     $post['update_by'] = $nama_input;
     $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

 function order_up_status($id='')
 {
   $stt = post('id');
   if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
   $this->ajax_permission('update', 'master/order');
   $tbl = $this->tabel_order;

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
 //END OF ORDER

 //START SALES
 public function sales_simpan($id='')
 {
   $this->ajax_permission('create', 'master/sales');
   $id_user = get_session('id_user');
   $nama_input = "$id_user - ".user('nama_lengkap');
   $tgl_now = tgl_now();
   $tbl = $this->tabel_sales;
   $post['id_sales']        = strtoupper(post('id_sales'));
   $post['sales']           = strtoupper(post('sales'));
   $post['no_hp']           = strtoupper(post('no_hp'));

   if ($id!='') { //jika edit
     $this->db->select("id_sales, sales, no_hp");
     $data_lama = get_field($tbl, array("id_$tbl"=>$id));
   }

   if ($id=='') {
     $post['tgl_input'] = $tgl_now;
     $post['status']   = 1;
     $post['input_by'] = $nama_input;
     $simpan = add_data($tbl,$post);
   }else{
     $post['status']    = post('status');
     $post['tgl_update'] = $tgl_now;
     $post['update_by'] = $nama_input;
     $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

 function sales_up_status($id='')
 {
   $stt = post('id');
   if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
   $this->ajax_permission('update', 'master/sales');
   $tbl = $this->tabel_sales;

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

   //START VIDEO
public function video_simpan($id='')
{
  $this->ajax_permission('create', 'master/video');
  $id_user = get_session('id_user');
  $nama_input = "$id_user - ".user('nama_lengkap');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_video;
  $post['judul']  = post('judul');
  $post['video']  = post('video');

  if ($id!='') { //jika edit
    $this->db->select("video");
    $data_lama = get_field($tbl, array("id_$tbl"=>$id));
  }


  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $post['input_by'] = $nama_input;
    $simpan = add_data($tbl,$post);
  }else{
    $post['status']    = post('status');
    $post['tgl_update'] = $tgl_now;
    $post['update_by'] = $nama_input;
    $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

function video_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/video');
    $tbl = $this->tabel_video;

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
  //END VIDEO


     //START SLIDE
public function slide_simpan($id='')
{
  $this->ajax_permission('create', 'master/slide');
  $id_user = get_session('id_user');
  $nama_input = "$id_user - ".user('nama_lengkap');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_slide;
  $post['slide']  = post('slide');

  if ($id!='') { //jika edit
    $this->db->select("slide");
    $data_lama = get_field($tbl, array("id_$tbl"=>$id));
    $old_foto1=$data_lama['foto_slide'];
  }
  $tgl = tgl_now('tgl');
  $path_tgl = tgl_format($tgl,'Y').'/'.tgl_format($tgl,'m');
  $path  = get_path_img('custom', "slide/$path_tgl");
  createPath($path, 0777);
  upload_config($path,'5','jpeg|jpg|png|gif|bmp');

  $foto = upload_file('foto_slide',$path,'ajax', $old_foto1, 1);
  if (!empty($foto['pesan'])) {
    $this->ajax_failed($foto['pesan']);
  }else{
    $stt=1; $post['foto_slide'] = $foto;
    $cek_resize = resizeImage($foto,$path);
    if ($cek_resize!=1) { $this->ajax_failed('Maaf, Upload Foto Gagal!'); }
  }

  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $post['input_by'] = $nama_input;
    $simpan = add_data($tbl,$post);
  }else{
    $post['status']    = post('status');
    $post['tgl_update'] = $tgl_now;
    $post['update_by'] = $nama_input;
    $simpan = update_data($tbl,$post, array("id_$tbl"=>$id));
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

function slide_up_status($id='')
  {
    $stt = post('id');
    if (!in_array($stt, array(0,1))) { $this->ajax_failed(); }
    $this->ajax_permission('update', 'master/slide');
    $tbl = $this->tabel_slide;

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
  //END SLIDE

     //START PESAN
public function pesan_simpan($id='')
{
  // log_r('m_master/pesan');
  $tgl_now = tgl_now();
  $tbl = $this->tabel_pesan;
  $post['nama']  = post('nama');
  $post['no_hp']  = post('no_hp');
  $post['pesan']  = post('pesan');

 
  if ($id=='') {
    $post['tgl_input'] = $tgl_now;
    $post['status']   = 1;
    $simpan = add_data($tbl,$post);
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
}
?>
