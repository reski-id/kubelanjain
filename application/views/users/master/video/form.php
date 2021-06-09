<style>
/* .select2-container {
  min-width: 50px !important; */
}
</style>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_video"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'judul','nama'=>'Judul video','icon'=>'label','html'=>'required style="text-transform: uppercase;"', 'value'=>$query['judul'], 'col'=> 12);
  $datanya[] = array('type'=>'text','name'=>'video','nama'=>'Link video','icon'=>'label','html'=>'required', 'value'=>$query['video'], 'col'=> 12);
  
  $data_stt = array('Tidak Aktif', 'Aktif');
  foreach ($data_stt as $key => $value) {
    $data_status[] = array('id'=>$key, 'nama'=>$value);
  }
  $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','col'=> 12, 'html'=>'required', 'data_select'=>$data_status);

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
 $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
<?php }else{ ?>
  reset_select2nya("[name='status']", '1', 'val');
<?php } ?>

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
    stt = $('#id_provinsix :selected').val();
    if (stt!='') {
      RefreshTable();
    }
  }
}
</script>
