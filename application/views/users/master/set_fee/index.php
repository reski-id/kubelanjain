<form id="sync_form" action="javascript:simpan('sync_form','<?= base_url(); ?>master/simpan/set_fee','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
  <label>Maksimal Value Fee &nbsp;</label>
  <input type="text" name="max_fee" id="max_fee" onkeyup="get_rp('max_fee');" class="text-center" value="<?= max_fee(); ?>" data-parsley-trigger="keyup" maxlength="5" data-parsley-validation-threshold="1" data-parsley-type="number" style="max-width:100px;">
  <br><br>
  <div class="table-responsive">
    <table class="table table-striped mb-0">
      <thead class="thead-dark">
        <tr>
          <th width="30%" class="text-center">IN</th>
          <th width="30%" class="text-center">OUT</th>
          <th width="20%" class="text-center">FEE IN</th>
          <th width="20%" class="text-center">FEE OUT</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $this->db->order_by('id_set_fee', 'ASC');
        $this->db->where('tipe_in!=0 AND tipe_out!=0');
        foreach (get('set_fee')->result() as $key => $value):
          $id = $value->id_set_fee; ?>
          <tr>
            <td style="color:<?= get_color_tipe_fee($value->tipe_in); ?>"><?= get_name_tipe_fee($value->tipe_in); ?></td>
            <td style="color:<?= get_color_tipe_fee($value->tipe_out); ?>"><?= get_name_tipe_fee($value->tipe_out); ?></td>
            <td> <input type="text" name="<?= $id; ?>_in" onkeyup="get_rp('<?= $id; ?>_in', '<?= $id; ?>', '<?= $value->value_in; ?>');" class="form-control text-center" value="<?= $value->value_in; ?>" data-parsley-trigger="keyup" maxlength="5" data-parsley-validation-threshold="1" data-parsley-type="number" style="min-width:70px;"> </td>
            <td> <input type="text" name="<?= $id; ?>_out" onkeyup="get_rp('<?= $id; ?>_out', '<?= $id; ?>', '<?= $value->value_out; ?>');" class="form-control text-center" value="<?= $value->value_out; ?>" data-parsley-trigger="keyup" maxlength="5" data-parsley-validation-threshold="1" data-parsley-type="number" style="min-width:70px;"> </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php if (check_permission('view', 'update', 'master/set_fee')) { ?>
  <button type="submit" class="btn btn-primary glow float-right mt-1 mb-1" name="simpan"> <span>SIMPAN</span> </button>
  <?php } ?>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
function get_rp(name='', id='', value=0)
{
  max_fee = parseInt($('[name="max_fee"]').val().replace(/[^0-9]/g, ''));
  if (name!='max_fee') {
    get_in  = parseInt($('[name="'+id+'_in"]').val().replace(/[^0-9]/g, ''));
    get_out = parseInt($('[name="'+id+'_out"]').val().replace(/[^0-9]/g, ''));
    total = get_in + get_out;
    if (total > max_fee) {
      $('[name="'+name+'"]').val(value);
      pesan = 'Fee tidak boleh melebihi '+get_formatRupiah($('[name="max_fee"]').val());
      swal({ html:true, type: "warning", title: "Fee!", text: pesan, allowEscapeKey: false });
      return true;
    }
  }
  formatRupiah(name);
}
</script>
