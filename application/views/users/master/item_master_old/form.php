<style>
  .select2-container{
    min-width: 50px !important;
  }
</style>
<form id="sync_form" action="javascript:aksi_simpan();" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div id="pesannya"></div>
  <div class="row">
    <?php if($id==''){ $status=1; }else{ $status=''; }
    $data_kat=array();
    $this->db->order_by('nama', 'ASC');
    foreach (get_item_kat('', $status)->result() as $key => $value) {
      $data_kat[] = array('id'=>$value->id_item_kategori, 'nama'=>$value->nama);
    }
    $data_item_satuan=array();
    foreach (get_item_satuan('', $status)->result() as $key => $value) {
      $data_item_satuan[] = array('id'=>$value->id_item_satuan, 'nama'=>$value->item_satuan);
    }
    if ($id=='') {
      $aksi='create';
    }else{
      $aksi='update';
    }
    $datanya[] = array('type'=>'select', 'name'=>'id_item_kategori', 'nama'=>'Item Kategori *', 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="get_kode()"', 'data_select'=>$data_kat, 'col'=>6, 'class'=>'select');
    $datanya[] = array('type'=>'text','name'=>'plu','nama'=>'PLU *','icon'=>'tag','html'=>'readonly', 'value'=>$query['plu'], 'col'=>6);
    $datanya[] = array('type'=>'text','name'=>'nama_item','nama'=>'Nama Item *','icon'=>'box','html'=>'minlength="1" maxlength="100" style="text-transform: uppercase;" required', 'value'=>$query['nama_item'], 'col'=>12, 'id'=>'nama_item');
    $datanya[] = array('type'=>'text', 'name'=>'nilai_satuan', 'nama'=>'Isian Angka *', 'icon'=>'label', 'html'=>'onkeypress="return hanyaAngka(event);" required', 'value'=>$query['nilai_satuan'], 'col'=>6);
    $datanya[] = array('type'=>'select', 'name'=>'id_item_satuan', 'nama'=>'Satuan *', 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" required', 'data_select'=>$data_item_satuan, 'col'=>6, 'class'=>'select');
    $datanya[] = array('type'=>'file','name'=>'foto','nama'=>'Foto Item','icon'=>'box','html'=>'', 'value'=>'', 'col'=>6);
    data_formnya($datanya);
    ?>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <?php if (check_permission('view', $aksi, 'master/item_master')): ?>
    <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
  <?php endif; ?>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
  $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
  if ($('.select').length!=0) {
    $('.select').select2({ width: '100%' });
  }

  // Custom File Input
  $('[name="foto"]').change(function (e) {
    $(this).next(".custom-file-label").html(e.target.files[0].name);
  });

  $('[name="nama_item"]').keypress(function(data) {
    var charCode = (event.which) ? event.which : event.keyCode
    //
    if((data.which>=48 && data.which<=57) || (charCode >= 65 && charCode <= 90)||(charCode >= 97 && charCode <= 122) || charCode==32)
    {
      return true;
    }else {
      return false;
    }
  });

  <?php if($id==''){ ?>
    reset_select2nya("[name='id_item_kategori']", '', 'val');
    reset_select2nya("[name='id_item_satuan']", '', 'val');
  <?php }else{ ?>
    $("[name='id_item_kategori']").attr('disabled', true);
    reset_select2nya("[name='id_item_kategori']", '<?= $query['id_item_kategori']; ?>', 'val');
    reset_select2nya("[name='id_item_satuan']", '<?= $query['id_item_satuan']; ?>', 'val');
  <?php } ?>
  $('[name="nilai_satuan"]').keyup(function() {
    formatRupiah('nilai_satuan');
  });

  function get_kode()
  {
    <?php if($id==''){ ?>
      item_kat  = $('[name="id_item_kategori"] :selected').val();
      if (item_kat!='') {
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>master/ajax_get_kode/<?= $tbl; ?>",
            data: 'id='+item_kat,
            cache: false,
            dataType : 'json',
            beforeSend: function() {
              loading_show();
            },
            success: function(data){
              if (data.stt==1) {
                $('[name="plu"]').val(data.pesan);
              }else {
                swal({ title : "Warning!", text : data.pesan, type : "warning" });
              }
              loading_close();
            }
        });
      }
    <?php } ?>
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
    if (wajib_isi('ITEM KATEGORI', 'id_item_kategori', ':selected')){ return false; }
    if (wajib_isi('PLU', 'plu')){ return false; }
    if (wajib_isi('NAMA ITEM', 'nama_item')){ return false; }
    if (wajib_isi('ANGKA SATUAN', 'nilai_satuan')){ return false; }
    if (wajib_isi('SATUAN', 'id_item_satuan', ':selected')){ return false; }
    if (stt_simpan) {
      simpan('sync_form','<?= base_url()."master/simpan/item_master/".encode($query["id_$tbl"]); ?>','','swal','5','1','1')
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
