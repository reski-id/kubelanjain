<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'kode','nama'=>'Kode Item Department','icon'=>'tag','html'=>'required minlength="1" maxlength="2" onkeypress="return hanyaAngka(event);"', 'value'=>$query['kode']);
  $datanya[] = array('type'=>'text','name'=>'nama','nama'=>'Nama Item Department','icon'=>'tag','html'=>'required minlength="1"', 'value'=>$query['nama']);
  data_formnya($datanya);
  if ($id=='') {
    $aksi='create';
  }else {
    $aksi='update';
  }
  ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <?php if (check_permission('view', $aksi, 'master/item_kategori')): ?>
    <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
  <?php endif; ?>
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
