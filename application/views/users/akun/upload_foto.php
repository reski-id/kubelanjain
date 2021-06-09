<?php
$level=get_session('level');
$urlnya = base_url("users/proses/simpan_foto");
?>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body" style="max-height: 420px; overflow-y: auto;">
  <div id="pesannya"></div>
  <div class="row">
  <?php
    $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>12);
    data_formnya($datanya);
  ?>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <button type="submit" class="btn btn-primary glow" name="simpan"> <span>Upload</span> </button>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>
<script type="text/javascript">
$('#modal_judul').html('Upload Foto');
//Custom File Input
$('[name="foto"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
})

function run_function_check(stt='')
{
  if (stt==1) {
    setTimeout(function(){ swal.close(); window.location.reload(); }, 2*1000);
  }
}
</script>
