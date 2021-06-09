<style>
  .select2-container{
    min-width: 50px !important;
  }
</style>
<form id="sync_form" action="javascript:aksi_simpan();" method="post" enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <div class="row">
    <?php if($id==''){ $status=1; }else{ $status=''; }

    $data_item_satuan=array();
    foreach (get_item_satuan('', $status)->result() as $key => $value) {
      $data_item_satuan[] = array('id'=>$value->id_item_satuan, 'nama'=>$value->item_satuan);
    }
    if ($id=='') {
      $aksi='create';
    }else{
      $aksi='update';
    }

    $datanya[] = array('type'=>'select','name'=>'id_item_master','nama'=>'Item', 'icon'=>'-', 'id'=>'id_item_master', 'html'=>'onchange="show_hasil()"', 'value'=>'','col'=>12);
    $datanya[] = array('type'=>'text', 'name'=>'nilai_satuan', 'nama'=>'Isian Angka *', 'icon'=>'calculator', 'html'=>'onkeypress="return hanyaAngka(event);" onkeyup="set_plu()" readonly maxlength="3"', 'value'=>$query['nilai_satuan'], 'col'=>6);
    $datanya[] = array('type'=>'select', 'name'=>'id_item_satuan', 'nama'=>'Satuan *', 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" disabled onchange="show_hasil()"', 'data_select'=>$data_item_satuan, 'col'=>6, 'class'=>'select');
    $datanya[] = array('type'=>'text', 'name'=>'plu_sub', 'nama'=>'PLU SUB', 'icon'=>'tag', 'html'=>'readonly', 'value'=>'', 'col'=>6);
    $datanya[] = array('type'=>'file','name'=>'foto','nama'=>'Foto Item','icon'=>'box','html'=>'', 'value'=>'', 'col'=>12);
    data_formnya($datanya);
    ?>
    <input type="hidden" name="plu_x" value="">
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <?php if (check_permission('view', $aksi, 'master/item_master_sub')): ?>

    <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
  <?php endif; ?>

</div>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
  $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
  if ($('select').length!=0) {
    $('select').select2({ width: '100%' });
  }
  
  // Custom File Input
  $('[name="foto"]').change(function (e) {
    $(this).next(".custom-file-label").html(e.target.files[0].name);
  });

  show_item();
  function show_item()
  {
    $('[name="id_item_master"]').empty();
    $('[name="id_item_master"]').append('<option value=""> - Pilih Item - </option>');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>web/ajax_item_master",
        data: 'p=0',
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(param){
            AmbilData = param.plus;
            $.each(AmbilData, function(index, loaddata) {
                $('[name="id_item_master"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
            });
            <?php if ($id!='') { ?>
              form_disabled('sync_form', true, 'all');
              setTimeout(function(){
                form_disabled('sync_form', false, 'all');
                reset_select2nya("[name='id_item_master']", '<?= $query['id_item_master']; ?>', 'val');
                reset_select2nya("[name='id_item_satuan']", '<?= $query['id_item_satuan']; ?>', 'val');
              }, 1000);
            <?php }else{ ?>
              loading_close();
            <?php } ?>
        }
    });
  }

  function show_hasil()
  {
    $('[name="nilai_satuan"]').removeAttr('readonly');
    $('[name="id_item_satuan"]').removeAttr('disabled');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>web/ajax_plu_sub",
        data: 'p='+$('[name="id_item_master"] :selected').val()+'&p2='+$('[name="id_item_satuan"] :selected').val(),
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(data){
          loading_close();
          $('[name="plu_x"]').val(data.plu_sub);
          set_plu();
        }
    });
  }

  function set_plu()
  {
    $('[name="plu_sub"]').val($('[name="plu_x"]').val()+$('[name="nilai_satuan"]').val());
  }

  function wajib_isi(msg='', name='', selected='')
  {
    if (name!='') {
      if ($('[name="'+name+'"]').length!=0) {
        if ($('[name="'+name+'"] '+selected).val() == '') {
          return wajib_isi(msg);
        }
      }
    }else {
      swal({ title : "Warning!", text : msg+" Wajib diisi!", type : "warning" });
      return true;
    }
    return false;
  }

  function aksi_simpan()
  {
    stt_simpan = true;
    if (wajib_isi('ITEM', 'id_item_master', ':selected')){ return false; }
    if (wajib_isi('ANGKA SATUAN', 'nilai_satuan')){ return false; }
    if (wajib_isi('SATUAN', 'id_item_satuan', ':selected')){ return false; }
    if (stt_simpan) {
      simpan('sync_form','<?= base_url()."master/simpan/item_master_sub/".encode($query["id_$tbl"]); ?>','','swal','3','1','1')
    }
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
