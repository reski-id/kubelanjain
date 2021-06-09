<div class="modal-body">
  <form action="javascript:simpan('import_form','<?= $url_import; ?>','','swal','5','','1');" method="post" id="import_form" data-parsley-validate='true' enctype="multipart/form-data">
    <?php
    $datanya[] = array('type'=>'file', 'name'=>'file', 'nama'=>'File Excel', 'icon'=>'file', 'html'=>' required accept=".xls, .xlsx" ', 'col'=>'12');
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <small class="text-danger">*Jika nama <b>PROVINSI</b> tidak ada di database, maka <b>Data Import</b> dengan Nama <b>PROVINSI</b> tersebut tidak disimpan.</small>
    </div>
    <hr>
    <div class="col-md-12">
      <a href="assets/file/import/template/<?= $tbl; ?>.xlsx" class="btn btn-secondary glow mr-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a>
      <button type="submit" class="btn btn-success glow float-right" id="import"> <i class="bx bxs-file"></i> <span>Import</span> </button>
    </div>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
//Custom File Input
$('[name="file"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
})

  function run_function_check(stt='')
  {
    if (stt==1) {
      RefreshTable();
    }
  }
</script>
