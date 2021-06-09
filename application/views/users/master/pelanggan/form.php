<style>
.select2-container {
  min-width: 50px !important;
}
</style>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body row">
  <div id="pesannya"></div>
  <div class="col-md-12">
  <?php
  $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'required onchange="show_kota()"', 'value'=>'', 'col'=> 6);
  $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'required disabled onchange="show_kecamatan()"', 'value'=>'', 'col'=> 6);
  $datanya[] = array('type'=>'select', 'name'=>'id_kecamatan', 'nama'=>'Kecamatan','icon'=>'-','html'=>'required disabled onchange="show_pasar()"', 'value'=>'', 'col'=> 6);
  $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'required disabled', 'value'=>'', 'col'=> 6);
  ?>
</div>
<div class="col-md-12">
<?php
   $datanya[] = array('type'=>'select', 'name'=>'id_jekel', 'nama'=>'Jekel','icon'=>'-','html'=>'required', 'value'=>'', 'col'=> 6);
   $datanya[] = array('type'=>'text','name'=>'nohp_satu','nama'=>ucwords('nohp satu'),'icon'=>'label','html'=>'required style="text-transform: uppercase;" onkeypress="return hanyaAngka(this)" maxlength="14"', 'value'=>$query['nohp_satu'], 'col'=> 6);
   $datanya[] = array('type'=>'text','name'=>$tbl,'nama'=>'Nama Pelanggan','icon'=>'label','html'=>'required style="text-transform: uppercase;"', 'value'=>$query[$tbl], 'col'=> 6);
   $datanya[] = array('type'=>'text','name'=>'nohp_dua','nama'=>ucwords('nohp dua'),'icon'=>'label','html'=>'required style="text-transform: uppercase; " onkeypress="return hanyaAngka(this)" maxlength="16"', 'value'=>$query['nohp_dua'], 'col'=> 6);

?>
</div>
<div class="col-md-12">
<?php
    $datanya[] = array('type'=>'select', 'name'=>'id_type', 'nama'=>'Type','icon'=>'-','html'=>'required', 'value'=>'', 'col'=> 6);
?>
</div>
  <?php
  $datanya[] = array('type'=>'select', 'name'=>'id_sales', 'nama'=>'Sales','icon'=>'-','html'=>'required', 'value'=>'', 'col'=> 6);
  $data_stt = array('Tidak Aktif', 'Aktif');
  foreach ($data_stt as $key => $value) {
    $data_status[] = array('id'=>$key, 'nama'=>$value);
  }
  $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','col'=> 6, 'html'=>'required', 'data_select'=>$data_status);
  $datanya[] = array('type'=>'textarea','name'=>'alamat','nama'=>ucwords('alamat'),'icon'=>'label','html'=>'required style="text-transform: uppercase;"', 'value'=>$query['alamat'], 'col'=> 6);
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
 $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
<?php }else{ ?>
  reset_select2nya("[name='status']", '1', 'val');
<?php } ?>

show_type();
function show_type()
{
  // type from db type
  $('[name="id_type"]').empty();
  $('[name="id_type"]').append('<option value=""> - Pilih Type - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_type",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_type"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_type']", '<?= $query['id_type']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}


show_jekel();
function show_jekel()
{
  // type from db type
  $('[name="id_jekel"]').empty();
  $('[name="id_jekel"]').append('<option value=""> - Bapak/Ibu - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_jekel",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_jekel"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_jekel']", '<?= $query['id_jekel']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}



show_sales();
function show_sales()
{
  // sales
  $('[name="id_sales"]').empty();
  $('[name="id_sales"]').append('<option value=""> - Pilih Sales - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_sales",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_sales"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_sales']", '<?= $query['id_sales']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}



show_provinsi();
function show_provinsi()
{
  $('[name="id_provinsi"]').empty();
  $('[name="id_provinsi"]').append('<option value=""> - Pilih Provinsi - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_prov",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_provinsi"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            reset_select2nya("[name='id_provinsi']", '21', 'val');
            $('[name="id_provinsi"]').attr('disabled', true);
            loading_close();
          <?php } ?>
      }
  });
}

function show_kota()
{
  kota = $('[name="id_kota"]');
  kota.removeAttr('disabled');
  kota.empty();
  kota.append('<option value=""> - Pilih Kota - </option>');
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
              kota.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kota']", '<?= $query['id_kota']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            reset_select2nya("[name='id_kota']", '2171', 'val');
            $('[name="id_kota"]').attr('disabled', true);
            loading_close();
          <?php } ?>
      }
  });
}

function show_kecamatan()
{
  kecamatan = $('[name="id_kecamatan"]');
  kecamatan.removeAttr('disabled');
  kecamatan.empty();
  kecamatan.append('<option value=""> - Pilih Kecamatan - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kec",
      data: 'p='+$('[name="id_kota"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              kecamatan.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kecamatan']", '<?= $query['id_kecamatan']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}

function show_pasar()
{
  pasar = $('[name="id_pasar"]');
  pasar.removeAttr('disabled');
  pasar.empty();
  pasar.append('<option value=""> - Pilih Pasar - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_pasar",
      data: 'p='+$('[name="id_kota"] :selected').val()+'&p2='+$('[name="id_kecamatan"] :selected').val()+'&status=1',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              pasar.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_pasar']", '<?= $query['id_pasar']; ?>', 'val');
            }, 1000);
          <?php } ?>
            loading_close();
      }
  });
}

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
    stt = $('#id_provinsix :selected').val();
    if (stt!='') {
      RefreshTable();
    }
  }
}
</script>
