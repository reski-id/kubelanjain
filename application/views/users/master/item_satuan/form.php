<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'kode','nama'=>'Kode','icon'=>'tag','html'=>'required minlength="1" maxlength="2"', 'value'=>$query['kode']);
  $datanya[] = array('type'=>'text','name'=>'item_satuan','nama'=>'Nama Satuan','icon'=>'tag','html'=>'required minlength="1"', 'value'=>$query['item_satuan']);
  if ($id!='') {
    $data_stt = array('Tidak Aktif', 'Aktif');
    foreach ($data_stt as $key => $value) {
      $data_status[] = array('id'=>$key, 'nama'=>$value);
    }
    $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','html'=>'required', 'data_select'=>$data_status);
  }
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

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
<?php } ?>

  function run_function_check(stt='')
  {
    if (stt==1) {
      RefreshTable();
    }
  }
</script>
