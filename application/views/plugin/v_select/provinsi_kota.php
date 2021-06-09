<div class="col-12 col-md-4">
  <label>Provinsi</label>
  <select class="form-control" id="id_provinsix" name="id_provinsix" onchange="v_show_kota();">
    <option value="">- Pilih Provinsi -</option>
    <option value="0">SEMUA PROVINSI</option>
    <?php
    $this->db->order_by('provinsi', 'ASC');
    foreach (get('provinsi', array('status'=>1))->result() as $key => $value): ?>
      <option value="<?= $value->id_provinsi; ?>"><?= $value->provinsi; ?></option>
    <?php endforeach; ?>
  </select>
</div>
<div class="col-12 col-md-4">
  <label>Kabupaten / Kota</label>
  <select class="form-control" id="id_kotax" name="id_kotax" disabled>
    <option value="">- Pilih Kabupaten / Kota -</option>
  </select>
</div>

<script type="text/javascript">
function v_show_kota()
{
  $('[name="id_kotax"]').empty();
  id_prov = $('[name="id_provinsix"] :selected').val();
  if (id_prov!='') {
    $('[name="id_kotax"]').append('<option value="">- Pilih KAB & Kota -</option>');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>web/ajax_kota",
        data: 'p='+$('[name="id_provinsix"] :selected').val(),
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(param){
            $('[name="id_kotax"]').removeAttr('disabled');
            AmbilData = param.plus;
            $('[name="id_kotax"]').append('<option value="0"> SEMUA KAB & KOTA </option>');
            $.each(AmbilData, function(index, loaddata) {
                $('[name="id_kotax"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
            });
            $('[name="id_kotax"]').removeAttr('disabled');
            loading_close();
        }
    });
  }else {
    $('[name="id_kotax"]').attr('disabled', true);
    if (id_prov==='0') {
      $('[name="id_kotax"]').append('<option value="0" selected> SEMUA KAB & KOTA </option>');
    }else {
      $('[name="id_kotax"]').append('<option value="">- Pilih Kabupaten / Kota -</option>');
    }
    $('[name="id_kotax"]').removeAttr('disabled');
  }
}
</script>
