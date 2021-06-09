<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'nama','nama'=>'Nama','icon'=>'label','html'=>'required', 'value'=>$query['nama']);
  $datanya[] = array('type'=>'text','name'=>'api_key','nama'=>'API Key','icon'=>'key','html'=>'required', 'value'=>$query['api_key']);
  $datanya[] = array('type'=>'text','name'=>'keterangan','nama'=>'Keterangan','icon'=>'spreadsheet','html'=>'required', 'value'=>$query['keterangan']);
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
function run_function_check(stt='')
{
  if (stt==1) {
    RefreshTable();
  }
}
</script>
