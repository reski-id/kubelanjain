<div class="modal-body pl-0 pr-0">
  <form action="javascript:import_data();" method="post" id="import_form" data-parsley-validate='true' enctype="multipart/form-data">
    <?php
    $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'required onchange="show_kota()"', 'value'=>'', 'col'=> 12);
    $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'required ', 'value'=>'', 'col'=> 12);
    $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'required ', 'value'=>'', 'col'=> 12);
    $datanya[] = array('type'=>'file', 'name'=>'file', 'nama'=>'File Excel', 'icon'=>'file', 'html'=>' required accept=".xls, .xlsx" ', 'col'=>'12');
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <small class="text-danger">*Jika <b>PLU SUB</b> tidak ada di database, maka <b>Data Import</b> tersebut tidak disimpan.</small>
    </div>
    <div id="v_data" style="max-height: 300px; overflow: auto;"></div>
    <hr>
    <div class="col-md-12">
      <!-- <a href="<?= $tbl; ?>/export.html" class="btn btn-secondary glow mr-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a> -->
      <button type="submit" class="btn btn-success glow float-right mb-1" id="import"> <i class="bx bxs-file"></i> <span id="btnname">Import</span> </button>
    </div>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
//Custom File Input
$('[name="file"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
});
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
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
            loading_close();
      }
  });
}

  function import_data()
  {
    $('#v_data').html('');
    form = 'import_form';
    form_disabled(form, true, 'all');
    var fd = new FormData();
      fd.append('id_provinsi', $('[name="id_provinsi"] :selected').val());
      fd.append('id_kota', $('[name="id_kota"] :selected').val());
      fd.append('id_pasar', $('[name="id_pasar"] :selected').val());
      fd.append('file', $('input[name="file"]')[0].files[0]);
      $.ajax({
        type: "POST",
        url : '<?= $tbl; ?>/aksi_import/<?= $tbl; ?>/view',
        data: fd,
        dataType: "json",
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        chace: false,
        beforeSend: function(){
          loading_show();
        },
        success: function( data ) {
          if (data.stt==1) {
            $('#modal-aksi').modal('hide');
            RefreshTable();
            $('#v_data').html(data.pesan);
            swal({ title : "Success!", text : "", type : "success", showConfirmButton: false, allowEscapeKey: false });
            set_timeout=2;
          }else {
            swal({ title : "Gagal", text : data.pesan, type : "warning", showConfirmButton: false, allowEscapeKey: false });
            set_timeout=5;
          }
          setTimeout(function(){ swal.close(); }, set_timeout*1000);
          form_disabled(form, false, 'all');
          loading_close();
        },
        error: function(){
          loading_close();
          swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
        }
      });
  }

  function run_function_check(stt='')
  {
    if (stt==1) {
      $('#modal-aksi').modal('hide');
      RefreshTable();
    }
    loading_close();
  }
</script>
