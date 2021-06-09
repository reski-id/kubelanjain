<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <div class="row">
    <?php
    $data_status[] = array('id'=>1, 'nama'=>'Aktif');
    $data_status[] = array('id'=>0, 'nama'=>'Tidak Aktif');
    // $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-', 'html'=>'required onchange="show_kota();"', 'value'=>'', 'col'=>12);
    // $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-', 'html'=>'required', 'value'=>'', 'col'=>12);
    $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'value'=>$query['nama_lengkap'], 'nama'=>'Nama Lengkap', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup"', 'col'=>12);
    $data_gudang_kota[] = array('id'=>0, 'nama'=>'Tidak Ada Gudang');
    foreach (get_gudang_kota()->result() as $key => $value) {
      $data_gudang_kota[] = array('id'=>$value->id_gudang_kota, 'nama'=>$value->nama_gudang_kota);
    }

    $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'value'=>$query['no_hp'], 'nama'=>'No HP / WA', 'placeholder'=>'08xxxxx', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' min="6" step="100" minlength="6" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'col'=>12);

    $datanya[] = array('type'=>'text', 'name'=>'username', 'value'=>$query['username'], 'nama'=>'Username', 'validasi'=>true, 'icon'=>'user', 'html'=>'minlength="2" data-parsley-trigger="keyup"', 'col'=>12);
    $datanya[] = array('type'=>'text', 'name'=>'password', 'value'=>decode($query['password']), 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'col'=>12);
    $datanya[] = array('type'=>'select', 'name'=>'id_gudang_kota', 'nama'=>'Gudang / Kota','icon'=>'-', 'html'=>'required onchange="show_gudang();"', 'value'=>'', 'col'=>12, 'data_select'=>$data_gudang_kota);
    $data_jenis_akses[] = array('id'=>1, 'nama'=>'Gudang');
    $data_jenis_akses[] = array('id'=>2, 'nama'=>'Lainnya');
    $datanya[] = array('type'=>'select', 'name'=>'jenis_akses', 'nama'=>'Jenis Akses','icon'=>'-', 'html'=>'required onchange="show_jenis_akses()"', 'value'=>'', 'col'=>12, 'hidden'=>true, 'data_select'=>$data_jenis_akses);
    $datanya[] = array('type'=>'select', 'name'=>'id_gudang', 'nama'=>'Akses Gudang','icon'=>'-', 'html'=>'required', 'value'=>'', 'col'=>12, 'hidden'=>true);
    $data_management_akses=array();
    foreach (get_management_akses()->result() as $key => $value) {
      $data_management_akses[] = array('id'=>$value->id_management_akses, 'nama'=>$value->nama_akses);
    }
    $datanya[] = array('type'=>'select', 'name'=>'id_management_akses', 'nama'=>'Akses Lainnya','icon'=>'-', 'html'=>'required', 'value'=>'', 'col'=>12, 'hidden'=>true, 'data_select'=>$data_management_akses);
    $datanya[] = array('type'=>'select', 'name'=>'status', 'value'=>'', 'nama'=>'Status Akun', 'icon'=>'-', 'html'=>'required', 'col'=>6, 'data_select'=>$data_status);
    data_formnya($datanya);
    ?>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <?php if (check_permission('view', 'create', "user_management/data_user")){ ?>
    <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
  <?php } ?>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
$('#modal_judul').html("<?= ($id=='') ? 'Tambah' : 'Edit'; ?> User");
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

$('[name="username"]').on('keypress', function (event) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key)) {
       event.preventDefault();
       return false;
    }
});

// loading_show();
reset_select2nya("[name='status']", '1', 'val');
<?php if ($id=='') { ?>
  reset_select2nya("[name='id_gudang_kota']", '0', 'val');
<?php }else{ ?>
  reset_select2nya("[name='id_gudang_kota']", '<?= $query['id_gudang_kota']; ?>', 'val');
<?php } ?>

function show_gudang()
{
  gudangnya   = $('#Hfg_id_gudang');
  jenis_aksesnya = $('#Hfg_jenis_akses');

  gudang_kota = $('[name="id_gudang_kota"] :selected');
  gudang      = $('[name="id_gudang"]');
  gudang.empty();
  gudang.append('<option value=""> - Pilih Akses Gudang - </option>');
  if (gudang_kota.val() == 0) {
    gudang.removeAttr('required');
    jenis_aksesnya.attr('hidden', true);
    reset_select2nya("[name='jenis_akses']", '', 'val');
    reset_select2nya("[name='id_management_akses']", '', 'val');
  }else {
    gudang.attr('required', true);
    jenis_aksesnya.removeAttr('hidden');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>user_management/ajax_gudang",
        data: 'p='+gudang_kota.val(),
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(param){
            AmbilData = param.plus;
            $.each(AmbilData, function(index, loaddata) {
                gudang.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
            });
            reset_select2nya("[name='jenis_akses']", '<?= $query['jenis_akses']; ?>', 'val');
            loading_close();
            gudang.removeAttr('disabled');
        }
    });
  }
}

function show_jenis_akses()
{
  gudangnya   = $('#Hfg_id_gudang');
  gudang      = $('[name="id_gudang"]');

  jenis_aksesnya = $('#Hfg_jenis_akses');
  jenis_akses    = $('[name="jenis_akses"]');

  management_aksesnya = $('#Hfg_id_management_akses');
  management_akses    = $('[name="id_management_akses"]');

  gudangnya.attr('hidden', true);
  management_aksesnya.attr('hidden', true);
  gudang.removeAttr('required');
  jenis_akses.removeAttr('required');
  management_akses.removeAttr('required');

  reset_select2nya("[name='id_gudang']", '', 'val');
  reset_select2nya("[name='id_management_akses']", '', 'val');
  if ($('[name="id_gudang_kota"] :selected').val() != 0) {
    jenis_akses.attr('required', true);
    jenis_akses = $('[name="jenis_akses"] :selected');
    if (jenis_akses.val() == 1) {
      gudang.attr('required', true);
      gudangnya.removeAttr('hidden');
      management_aksesnya.attr('hidden', true);
    }else if (jenis_akses.val() == 2) {
      gudangnya.attr('hidden', true);
      management_akses.attr('required', true);
      management_aksesnya.removeAttr('hidden');
    }
  }
  <?php if ($id!=""){ ?>
    reset_select2nya("[name='id_gudang']", '<?= $query['id_gudang']; ?>', 'val');
    reset_select2nya("[name='id_management_akses']", '<?= $query['id_management_akses']; ?>', 'val');
  <?php } ?>
}

// $('[name="id_kota"]').attr('disabled', true);
// show_prov();
// function show_prov()
// {
//   $('[name="id_provinsi"]').empty();
//   $('[name="id_provinsi"]').append('<option value=""> - Pilih Kota - </option>');
//   $.ajax({
//       type: "POST",
//       url: "<?php echo base_url(); ?>web/ajax_prov",
//       data: 'p='+$('[name="id_provinsi"] :selected').val(),
//       cache: false,
//       dataType : 'json',
//       beforeSend: function() {
//         loading_show();
//       },
//       success: function(param){
//           AmbilData = param.plus;
//           $.each(AmbilData, function(index, loaddata) {
//               $('[name="id_provinsi"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
//           });
//           <?php if ($id!='') { ?>
//             form_disabled('sync_form', true, 'all');
//             setTimeout(function(){
//               form_disabled('sync_form', false, 'all');
//               reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
//               reset_select2nya("[name='id_bank']", '<?= $dt_bank['id_bank']; ?>', 'val');
//               <?php if($query['status']==''){ ?>
//                 reset_select2nya("[name='status']", '1', 'val');
//               <?php }else{ ?>
//                 reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
//               <?php } ?>
//             }, 1000);
//           <?php }else{ ?> loading_close(); <?php } ?>
//       }
//   });
// }
//
// function show_kota()
// {
//   $('[name="id_kota"]').empty();
//   $('[name="id_kota"]').append('<option value=""> - Pilih Kota - </option>');
//   $.ajax({
//       type: "POST",
//       url: "<?php echo base_url(); ?>web/ajax_kota",
//       data: 'p='+$('[name="id_provinsi"] :selected').val(),
//       cache: false,
//       dataType : 'json',
//       beforeSend: function() {
//         loading_show();
//       },
//       success: function(param){
//           AmbilData = param.plus;
//           $.each(AmbilData, function(index, loaddata) {
//               $('[name="id_kota"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
//           });
//           reset_select2nya("[name='id_kota']", '<?= $query['id_kota']; ?>', 'val');
//           loading_close();
//           $('[name="id_kota"]').removeAttr('disabled');
//       }
//   });
// }

function run_function_check(stt='')
{
  if (stt==1) {
    RefreshTable();
  }
}
</script>
