<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $this->db->order_by('nama_group', 'ASC');
  foreach (get('bc_info_group')->result() as $key => $value) {
    $data_group[] = array('id'=>$value->id_bc_info_group, 'nama'=>$value->nama_group);
  }
  $datanya[] = array('type'=>'select', 'name'=>'id_bc_info_group', 'nama'=>'Group','icon'=>'-','html'=>'required', 'value'=>'', 'data_select'=>$data_group);
  $datanya[] = array('type'=>'text','name'=>'nama','nama'=>'Nama','icon'=>'user','html'=>'required', 'value'=>$query['nama']);
  $datanya[] = array('type'=>'text','name'=>'no_hp','nama'=>'Nomor Handphone','icon'=>'mobile','html'=>'required data-parsley-minlength="10" data-parsley-maxlength="14" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event);"', 'value'=>$query['no_hp']);
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
<?php if ($id!='') { ?>
  loading_show();
  form_disabled('sync_form', true, 'all');
  setTimeout(function(){
    form_disabled('sync_form', false, 'all');
    reset_select2nya("[name='id_bc_info_group']", '<?= $query['id_bc_info_group']; ?>', 'val');
    loading_close();
  }, 1000);
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
