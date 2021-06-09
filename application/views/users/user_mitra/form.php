<?php
$id_user = $query['id_user'];
$level   = $query['level'];

$dt_bank  = get_field('user_bank', array('id_user'=>$id_user));
?>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($id_user); ?>/<?= $level; ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body" style="max-height: 420px; overflow-y: auto;">
  <div id="pesannya"></div>
    <?php
    $this->db->order_by('provinsi', 'ASC');
    foreach (get('provinsi', array('status'=>1))->result() as $key => $value) {
      $data_prov[] = array('id'=>$value->id_provinsi, 'nama'=>$value->provinsi);
    }
    $data_bank = array();
    $this->db->order_by('bank', 'ASC');
    foreach (get('bank', array('status'=>1))->result() as $key => $value) {
      $data_bank[] = array('id'=>$value->id_bank, 'nama'=>$value->bank);
    }

    $data_jk[] = array('id'=>'Laki - Laki', 'nama'=>'Laki - Laki');
    $data_jk[] = array('id'=>'Perempuan', 'nama'=>'Perempuan');

    $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'placeholder'=>'Nama sesuai KTP', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup"', 'value'=>$query['nama_lengkap']);
    $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="show_kota();"', 'data_select'=>$data_prov);
    $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kabupaten / Kota', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" disabled');
    if (get_session('id_user')==1) {
      // $datanya[] = array('type'=>'text', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'value'=>decode($query['password']));
    }

    $datanya[] = array('type'=>'select', 'name'=>'jenis_kelamin', 'nama'=>'Jenis Kelamin', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_jk);
    $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"', 'value'=>$query['email']);
    $datanya[] = array('type'=>'textarea', 'name'=>'alamat', 'nama'=>'Alamat Tinggal', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['alamat']);
    $datanya[] = array('type'=>'text', 'name'=>'pekerjaan', 'nama'=>'Pekerjaan Saat ini', 'validasi'=>true, 'icon'=>'briefcase-alt-2', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['pekerjaan']);

    $datanya[] = array('type'=>'select', 'name'=>'id_bank', 'value'=>'', 'nama'=>'Bank', 'validasi'=>true, 'icon'=>'-', 'html'=>'', 'data_select'=>$data_bank);
    $datanya[] = array('type'=>'text', 'name'=>'nama', 'value'=>'', 'nama'=>'Nama Pemilik Bank', 'validasi'=>true, 'icon'=>'user-pin', 'html'=>'', 'value'=>$dt_bank['nama']);
    $datanya[] = array('type'=>'text', 'name'=>'no_rek', 'value'=>'', 'nama'=>'Nomor Rekening Bank', 'validasi'=>true, 'icon'=>'paperclip', 'html'=>' minlength="1" maxlength="20" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'value'=>$dt_bank['no_rek']);
    if (get_session('level')==0 && $query['type_id']==2) {
      $datanya[] = array('type'=>'text', 'name'=>'id_referal', 'nama'=>'ID MITRA', 'icon'=>' bxs-user-voice', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$query['id_referal']);
    }
    data_formnya($datanya);
    ?>
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

reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
reset_select2nya("[name='jenis_kelamin']", '<?= $query['jenis_kelamin']; ?>', 'val');
reset_select2nya("[name='id_bank']", '<?= $dt_bank['id_bank']; ?>', 'val');

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
