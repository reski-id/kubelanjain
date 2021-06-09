<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'link','nama'=>'Link','icon'=>'label','html'=>'required minlength="1"', 'value'=>$query['link']);
  $datanya[] = array('type'=>'text','name'=>$tbl,'nama'=>ucwords($tbl),'icon'=>'label','html'=>'required minlength="1"', 'value'=>$query[$tbl]);
  $v_status = array('Tidak Aktif', 'Aktif');
  $data_status[] = array('id'=>'', 'nama'=>'Pilih Status');
  foreach ($v_status as $key => $value) {
    $data_status[] = array('id'=>$key, 'nama'=>$value);
  }
  $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','html'=>'required minlength="1"', 'value'=>'', 'data_select'=>$data_status);
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

reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');

function run_function_check(stt='')
{
  if (stt==1) {
    RefreshTable();
  }
}
</script>
