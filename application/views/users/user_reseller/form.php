<?php
$id_user=$query['id_user'];
$level=$query['level'];

$this->db->select('id_paket');
$dt_paket = get_field('order', array('id_user'=>$id_user));
?>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($id_user); ?>/<?= $level; ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body" style="max-height: 420px; overflow-y: auto;">
  <div id="pesannya"></div>
  <div class="row">
    <?php
    $this->db->order_by('provinsi', 'ASC');
    foreach (get('provinsi', array('status'=>1))->result() as $key => $value) {
      $data_prov[] = array('id'=>$value->id_provinsi, 'nama'=>$value->provinsi);
    }

    $data_jk[] = array('id'=>'Laki - Laki', 'nama'=>'Laki - Laki');
    $data_jk[] = array('id'=>'Perempuan', 'nama'=>'Perempuan');

    $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'placeholder'=>'Nama sesuai KTP', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup"', 'value'=>$query['nama_lengkap'], 'col'=>12);
    $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="show_kota();"', 'data_select'=>$data_prov, 'col'=>12);
    $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kabupaten / Kota', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" disabled', 'col'=>12);
    if (get_session('id_user')==1) {
      // $datanya[] = array('type'=>'text', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'value'=>decode($query['password']));
    }

    $datanya[] = array('type'=>'select', 'name'=>'jenis_kelamin', 'nama'=>'Jenis Kelamin', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_jk, 'col'=>12);
    $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"', 'value'=>$query['email'], 'col'=>12);
    $datanya[] = array('type'=>'textarea', 'name'=>'alamat', 'nama'=>'Alamat Tinggal', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['alamat'], 'col'=>12);
    $datanya[] = array('type'=>'text', 'name'=>'pekerjaan', 'nama'=>'Pekerjaan Saat ini', 'validasi'=>true, 'icon'=>'briefcase-alt-2', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['pekerjaan'], 'col'=>12);

    $datanya[] = array('type'=>'textarea', 'name'=>'alamat_pengantaran', 'nama'=>'Alamat Pengantaran', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['alamat_pengantaran'], 'col'=>12);
    $data_info = array();
    $this->db->order_by('id_informasi', 'ASC');
    foreach (get('informasi', array('status'=>1))->result() as $key => $value) {
      $data_info[] = array('id'=>$value->informasi, 'nama'=>$value->informasi);
    }
    $datanya[] = array('type'=>'select', 'name'=>'informasi_dari', 'nama'=>'Dapat Informasi Dari ?', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_info, 'col'=>12);
    // $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'');

    foreach (get_sosmed() as $key => $value) {
      $this->db->select('url');
      $sosmednya = get_field('user_sosmed', array('id_user'=>$id_user, 'sosmed'=>strtolower($value['nama'])))['url'];
      $datanya[] = array('type'=>'text', 'name'=>'sosmed'.$key, 'value'=>'', 'nama'=>$value['nama'], 'icon'=>' '.$value['icon'], 'html'=>' data-parsley-trigger="keyup"', 'value'=>$sosmednya, 'col'=>12);
    }
    // if (user('id_referal', $id_user, 'user_biodata_reseller') != '') {
    //   $jenis = 1;
    // }else {
    //   $jenis = 2;
    // }
    // $data_paket = array();
    // $this->db->where('jenis', $jenis);
    // $this->db->order_by('id_paketnya', 'ASC');
    // foreach (get('paketnya')->result() as $key => $value) {
    //   $data_paket[] = array('id'=>$value->id_paketnya, 'nama'=>$value->paketnya);
    // }
    // $datanya[] = array('type'=>'select', 'name'=>'paket', 'nama'=>'Paket', 'validasi'=>true, 'icon'=>'-', 'html'=>'onchange="hitung_harga_paket()" data-parsley-trigger="keyup"', 'data_select'=>$data_paket, 'col'=>12);
    // $this->db->select('jumlah');
    // $jumlah = get_field('order', array('id_user'=>$id_user))['jumlah'];
    // $datanya[] = array('type'=>'number', 'name'=>'jumlah', 'nama'=>'Jumlah Pesan', 'value'=>$jumlah, 'validasi'=>true, 'icon'=>' bxs-calculator', 'html'=>' onkeyup="hitung_harga_paket()" readonly maxlength="3" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'col'=>5);
    // $datanya[] = array('type'=>'text', 'name'=>'harga', 'nama'=>'Harga', 'value'=>'Rp. 0', 'validasi'=>true, 'icon'=>'money', 'html'=>' readonly', 'col'=>7);
    if (get_session('level')==0) {
      $datanya[] = array('type'=>'text', 'name'=>'id_referal', 'nama'=>'ID MITRA', 'icon'=>' bxs-user-voice', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['id_referal'], 'col'=>12);

      $data_pk[] = array('id'=>1, 'nama'=>'Ya');
      $data_pk[] = array('id'=>0, 'nama'=>'Tidak');
      $datanya[] = array('type'=>'select', 'name'=>'paket_khusus', 'nama'=>'PAKET KHUSUS', 'icon'=>'-', 'html'=>' data-parsley-trigger="keyup"', 'value'=>'', 'data_select'=>$data_pk, 'col'=>12);
    }

    data_formnya($datanya);
    ?>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>
<script type="text/javascript">
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

// function hitung_harga_paket()
// {
//   jumlah = $('[name="jumlah"]');
//   harganya = $('[name="harga"]');
//   $.ajax({
//       type: "POST",
//       url: "<?php echo base_url(); ?>web/ajax_hitung_harga_paket/1",
//       data: 'p='+$('[name="paket"] :selected').val()+'&jml='+jumlah.val(),
//       cache: false,
//       dataType : 'json',
//       beforeSend: function() {
//         harganya.val('Menghitung . . .');
//         jumlah.attr('readonly',true);
//       },
//       success: function(data){
//         // if (data.harga=='-') {
//         //   swal({ html:true, title : "Paket tidak valid", text : 'Silahkan di refresh & input ulang, Terimakasih!', type : "warning", confirmButtonText:'OKE Saya Mengerti', showConfirmButton: true, allowEscapeKey: false });
//         // }else {
//           jumlah.removeAttr('readonly');
//           harganya.val(data.harga);
//         // }
//       }
//   });
// }

reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
reset_select2nya("[name='jenis_kelamin']", '<?= $query['jenis_kelamin']; ?>', 'val');
reset_select2nya("[name='informasi_dari']", '<?= $query['informasi_dari']; ?>', 'val');

<?php
$this->db->select('id_user');
$get_spesial = get('user_harga_spesial', array('id_user'=>$id_user))->row();
$spesial=0;
if (!empty($get_spesial)) { $spesial=1; }
?>
reset_select2nya("[name='paket_khusus']", '<?= $spesial; ?>', 'val');
// reset_select2nya("[name='paket']", '<?= $dt_paket['id_paket']; ?>', 'val');

function show_kota()
{
  $('[name="id_kota"]').empty();
  $('[name="id_kota"]').append('<option value=""> Pilih Kota </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kota",
      data: 'p='+$('[name="id_provinsi"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_kota"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          $('[name="id_kota"]').removeAttr('disabled');
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kota']", '<?= $query['id_kota']; ?>', 'val');
            }, 1000);
          <?php } ?>
          loading_close();
      }
  });
}
</script>
