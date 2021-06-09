<style>
.select2-container {
  min-width: 50px !important;
}
</style>
<?php
$id_toko = ($id=='') ? decode(uri(4)) : $query['id_toko'];
$this->db->select('id_toko, id_provinsi, id_kota, id_kecamatan, id_pasar');
$get_toko = get_field('toko', array('id_toko'=>$id_toko));
$id_provinsi = $get_toko['id_provinsi'];
$id_kota = $get_toko['id_kota'];
$id_kecamatan = $get_toko['id_kecamatan'];
$id_pasar = $get_toko['id_pasar'];
?>

<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body row">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'hidden', 'name'=>'id_toko', 'value'=>$id_toko, 'hidden'=>true);
  $datanya[] = array('type'=>'hidden', 'name'=>'id_provinsi', 'value'=>$id_provinsi, 'hidden'=>true);
  $datanya[] = array('type'=>'hidden', 'name'=>'id_kota', 'value'=>$id_kota, 'hidden'=>true);
  $datanya[] = array('type'=>'hidden', 'name'=>'id_kecamatan', 'value'=>$id_kecamatan, 'hidden'=>true);
  $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'required ', 'value'=>'', 'col'=> 12);

  $datanya[] = array('type'=>'select', 'name'=>'id_item_master', 'nama'=>'Nama Item','icon'=>'-','html'=>'required', 'value'=>'', 'col'=> 12);
  // $datanya[] = array('type'=>'text', 'name'=>'nama_item', 'nama'=>'Nama Item', 'icon'=>'label','html'=>'required', 'value'=>$query['nama_item'], 'col'=> 12);



  $datanya[] = array('type'=>'text','name'=>'harga','nama'=>'Harga Pasar','icon'=>'money','html'=>'required onkeyup="hitungharga()" onkeypress="return hanyaAngka(this)"', 'value'=>$query['harga'], 'col'=> 6);
  $datanya[] = array('type'=>'text','name'=>'harga_beli','nama'=>'Harga Beli','icon'=>'money','html'=>'required onkeyup="hitungharga(1)" onkeypress="return hanyaAngka(this)"', 'value'=>$query['harga_beli'], 'col'=> 6);
  $datanya[] = array('type'=>'text','name'=>'gap','nama'=>'Gap','icon'=>'money','html'=>'required onkeyup="hitungharga(2)" onkeypress="return hanyaAngka(this)" maxlength="14"', 'value'=>($id=='') ? 'Rp. 0' : $query['gap'], 'col'=> 6);

  $data_stt = array('Tidak Aktif', 'Aktif');
  foreach ($data_stt as $key => $value) {
    $data_status[] = array('id'=>$key, 'nama'=>$value);
  }
  $datanya[] = array('type'=>'select','name'=>'status','nama'=>'Status','icon'=>'-','col'=> 6, 'html'=>'required', 'data_select'=>$data_status);
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

$('[name="harga"]').keyup(function() {
  formatRupiah('harga', 'Rp. ');
});

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
  formatRupiah('harga', 'Rp. ');
  format_angkanya($('[name="harga_beli"]'), 'Rp. ');
  format_angkanya($('[name="gap"]'), 'Rp. ');
<?php }else{ ?>
  reset_select2nya("[name='status']", '1', 'val');
<?php } ?>


show_item();
function show_item()
{
  // cari nama item di db terus tampilin ke select option
  $('[name="id_item_master"]').empty();
  $('[name="id_item_master"]').append('<option value=""> - Pilih Item- </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_item_lokasi",
      data: 'p=<?= $id_pasar; ?>&status=1&id_provinsi=<?= $id_provinsi; ?>&id_kota=<?= $id_kota; ?>&id_kecamatan=<?= $id_kecamatan; ?>',
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
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}

show_pasar();
function show_pasar()
{
  pasar = $('[name="id_pasar"]');
  pasar.removeAttr('disabled');
  pasar.empty();
  pasar.append('<option value=""> - Pilih Pasar - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_pasar",
      data: 'p=<?= $id_kota; ?>&p2=<?= $id_kecamatan; ?>',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              pasar.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
            reset_select2nya("[name='id_pasar']", '<?= $id_pasar; ?>', 'val');
            pasar.attr('disabled', true);
            loading_close();
      }
  });
}

function format_angkanya(name='', rp='')
{
  min='';
  if (name.val() < 0) { min='- '; }
  get = get_formatRupiah(name.val());
  name.val(min+rp+get);
}

function hitungharga(aksi='') {
    harga      = parseInt($('[name="harga"]').val().replace(/[^0-9]/g, ''));
    harga_beli = parseInt($('[name="harga_beli"]').val().replace(/[^0-9]/g, ''));
    if (aksi==1) { //jika diinput harga beli maka ubah GAP
      total = harga - harga_beli;
      $('[name="gap"]').val(total);
    }else if (aksi==2) { //jika diinput GAP maka ubah harga beli
      gap   = parseInt($('[name="gap"]').val().replace(/[^0-9]/g, ''));
      total = harga - gap;
      $('[name="harga_beli"]').val(total);
    }else {
      persen = 10;
      gapnya = harga * (persen/100);
      $('[name="gap"]').val(gapnya);
      gap    = parseInt($('[name="gap"]').val().replace(/[^0-9]/g, ''));
      $('[name="gap"]').val(gap);
      total = harga - gap;
      $('[name="harga_beli"]').val(total);
    }
    format_angkanya($('[name="harga_beli"]'), 'Rp. ');
    format_angkanya($('[name="gap"]'), 'Rp. ');
}

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
