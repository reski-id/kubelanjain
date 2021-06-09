<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'nama_group','nama'=>'Nama Group','icon'=>'user','html'=>'required', 'value'=>$query['nama_group']);
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
<?php if ($tbl=='bc_info_group') { ?>
  $('#modal_judul').html('<?php if($id==''){ echo "Tambah"; }else{ echo "Edit"; } ?> Group');
<?php } ?>
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
    RefreshTable();
  }
}
</script>
