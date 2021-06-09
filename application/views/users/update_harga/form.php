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
   $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'required onchange="show_kota()"', 'value'=>$query['id_provinsi'],  'col'=> 12, 'hidden'=>true);
   $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'required ', 'value'=>$query['id_kota'], 'col'=> 12, 'hidden'=>true);
   $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'required readonly', 'value'=>$query['id_pasar'], 'col'=> 12, 'hidden'=>true);
   $datanya[] = array('type'=>'select', 'name'=>'id_item_master_sub', 'nama'=>'Nama Item','icon'=>'-','html'=>'required ', 'value'=>$query['id_item_master_sub'], 'col'=> 12);
   $datanya[] = array('type'=>'text','name'=>'harga_dasar','nama'=>'Harga Pasar Tos 3000','icon'=>'money','html'=>'required onkeyup="format_angkanya(\'harga_dasar\')"', 'value'=>$query['harga_dasar'], 'col'=> 12);
   $datanya[] = array('type'=>'text','name'=>'harga','nama'=>'Harga Jual','icon'=>'money','html'=>'required onkeyup="format_angkanya(\'harga\')"', 'value'=>$query['harga'], 'col'=> 12);
   $datanya[] = array('type'=>'text','name'=>'harga_tawar','nama'=>'Harga Tawar','icon'=>'money','html'=>'required onkeyup="format_angkanya(\'harga_tawar\')"', 'value'=>$query['harga_tawar'], 'col'=> 12);
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
 $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
<?php }else{ ?>
  reset_select2nya("[name='status']", '1', 'val');
<?php } ?>

function format_angkanya(name='')
{
  name = $('[name="'+name+'"]');
  get = get_formatRupiah(name.val());
  name.val('Rp. '+get);
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
          reset_select2nya("[name='id_provinsi']", '21', 'val');
          loading_close();
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
          reset_select2nya("[name='id_kota']", '2171', 'val');
          show_pasar();
          loading_close();
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
      data: 'status=1&p='+$('[name="id_kota"] :selected').val()+'&status=1',
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
          <?php }else{ ?>
            reset_select2nya("[name='id_pasar']", '16', 'val');
          <?php } ?>
            show_item();
            pasar.attr('disabled', false);
            loading_close();
      }
  });
}

function show_item()
{
  $('[name="id_item_master_sub"]').empty();
  $('[name="id_item_master_sub"]').append('<option value=""> - Pilih Item - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_sub_item",
      data: 'status=1',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_item_master_sub"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_item_master_sub']", '<?= $query['id_item_master_sub']; ?>', 'val');
              $('[name="id_item_master_sub"]').attr('disabled', true);
              show_lokasi();
            }, 1000);
            <?php }else{
              if ($id_item_master_sub!=''){ ?>
                reset_select2nya("[name='id_item_master_sub']", '<?= $id_item_master_sub; ?>', 'val');
                $('[name="id_item_master_sub"]').attr('disabled', true);
            <?php } ?>
            loading_close();
          <?php } ?>
          
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
