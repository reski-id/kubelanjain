<div class="modal-body pl-0 pr-0">
  <form action="javascript:import_data();" method="post" id="import_form" data-parsley-validate='true' enctype="multipart/form-data">
    <?php
    $datanya[] = array('type'=>'file', 'name'=>'file', 'nama'=>'File Excel', 'icon'=>'file', 'html'=>' required accept=".xls, .xlsx" ', 'col'=>'12');
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <small class="text-danger">*Jika <b>PLU, ISIAN dan KODE SATUAN</b> tidak ada di database, maka <b>Data Import</b> tersebut tidak disimpan.</small>
    </div>
    <div id="v_data" style="max-height: 300px; overflow: auto;"></div>
    <hr>
    <div class="col-md-12">
      <a href="assets/file/import/template/<?= $tbl; ?>.xlsx" class="btn btn-secondary glow mr-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a>
      <button type="submit" class="btn btn-success glow float-right" id="import"> <i class="bx bxs-file"></i> <span id="btnname">Import</span> </button>
    </div>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
//Custom File Input
$('[name="file"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
})

  function import_data()
  {
    $('#v_data').html('');
    form = 'import_form';
    form_disabled(form, true, 'all');
    var fd = new FormData();
      fd.append('file', $('input[name="file"]')[0].files[0]);
      $.ajax({
        type: "POST",
        url : 'master/aksi_import/<?= $tbl; ?>/view',
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
