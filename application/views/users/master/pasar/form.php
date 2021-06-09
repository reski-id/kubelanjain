<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php if($id==''){ $status=1; }else{ $status=''; }
  $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'required onchange="show_kota()"', 'value'=>'');
  $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'required disabled onchange="show_kecamatan()"', 'value'=>'');
  $datanya[] = array('type'=>'select', 'name'=>'id_kecamatan', 'nama'=>'Kecamatan','icon'=>'-','html'=>'multiple="multiple" required disabled', 'value'=>'', 'class'=>'select_multi');
  $datanya[] = array('type'=>'text','name'=>$tbl,'nama'=>ucwords($tbl),'icon'=>'label','html'=>'required style="text-transform: uppercase;"', 'value'=>$query[$tbl]);
  $datanya[] = array('type'=>'text','name'=>'persentase','nama'=>'persentase','icon'=>'label','html'=>'required style="text-transform: uppercase;" onkeypress="return hanyaAngka(this)" maxlength="2"' , 'value'=>$query['persentase']);

  $data_stt = array('Tidak Aktif', 'Aktif');
  foreach ($data_stt as $key => $value) {
    $data_status[] = array('id'=>$key, 'nama'=>$value);
  }
  $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','html'=>'required', 'data_select'=>$data_status);
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
            loading_close();
          <?php } ?>
      }
  });
}

<?php
$id_kecnya = "[";
$this->db->select('id_kecamatan as id');
foreach (get('pasar_kecamatan', array('id_pasar'=>$query['id_pasar'], 'status'=>1))->result() as $key => $value) {
  $id_kecnya .= "'$value->id', ";
}
$id_kecnya .= "]";
?>
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
      success: function(param) {
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
            kecamatan.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          kecamatan.val(<?= $id_kecnya; ?>);
          kecamatan.trigger('change.select2');
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
