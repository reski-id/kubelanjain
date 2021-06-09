<?php $level = get_session('level');
$max_fee = max_fee(); $btn_disabled = false;
$id_master = find_nomor_get_id_user($query['id_referal']);
$this->db->select('id_user_fee, fee_master, fee_child, tgl_edit');
$this->db->where('id_master', $id_master);
$this->db->where('id_child', $id);
$value = get('user_fee')->row();
if (empty($value)) {
  echo '<div class="p-1 text-center"> Data tidak ditemukan! </div>';
  exit;
}
$id_fee     = $value->id_user_fee;
$fee_master = $value->fee_master;
$fee_child  = $value->fee_child;
if (in_array($value->tgl_edit, array('',null))) {
  $btn_disabled = true;
}
if ($level==0) { $btn_disabled=true; }
?>
<div class="modal-body <?php if ($level==0){ echo "p-1"; }else{ echo "p-0"; } ?>">
  <label>Maksimal Fee : <?= format_angka($max_fee); ?></label>
  <form id="sync_form" action="javascript:simpan('sync_form','<?= base_url(); ?>users/proses/edit_fee/<?= encode($id_fee); ?>','','swal','3','1','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead class="thead-dark">
          <tr>
            <th width="50%" class="text-center">MITRA I</th>
            <th width="50%" class="text-center">MITRA II</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <?php if (!$btn_disabled){ ?>
              <td class="text-center"><?= format_angka($fee_master); ?></td>
              <td class="text-center"><?= format_angka($fee_child); ?></td>
            <?php }else{ ?>
              <td> <input type="text" name="<?= $id_fee; ?>_in" onkeyup="get_rp('<?= $id_fee; ?>_in', '<?= $id_fee; ?>', '<?= $fee_master; ?>');" class="form-control text-center" value="<?= $fee_master; ?>" data-parsley-trigger="keyup" maxlength="5" data-parsley-validation-threshold="1" data-parsley-type="number" style="min-width:70px;"> </td>
              <td> <input type="text" name="<?= $id_fee; ?>_out" onkeyup="get_rp('<?= $id_fee; ?>_out', '<?= $id_fee; ?>', '<?= $fee_child; ?>');" class="form-control text-center" value="<?= $fee_child; ?>" data-parsley-trigger="keyup" maxlength="5" data-parsley-validation-threshold="1" data-parsley-type="number" style="min-width:70px;"> </td>
            <?php } ?>
          </tr>
        </tbody>
      </table>
    </div>
    <?php if ($btn_disabled) { ?>
      <button type="submit" class="btn btn-primary glow float-right mt-1 mb-1" name="simpan"> <span>SIMPAN</span> </button>
    <?php } ?>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
<?php if ($btn_disabled) { ?>
  $('#modal_judul').html("Edit Fee");
function get_rp(name='', id='', value=0)
{
  get_in  = parseInt($('[name="'+id+'_in"]').val().replace(/[^0-9]/g, ''));
  get_out = parseInt($('[name="'+id+'_out"]').val().replace(/[^0-9]/g, ''));
  total = get_in + get_out;
  if (total > <?= $max_fee; ?>) {
    $('[name="'+name+'"]').val(value);
    pesan = 'Fee tidak boleh melebihi <?= format_angka($max_fee); ?>';
    swal({ html:true, type: "warning", title: "Fee!", text: pesan, allowEscapeKey: false });
    return true;
  }
  formatRupiah(name);
}

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
  }
}
<?php }else{ ?>
  $('#modal_judul').html("Detail Fee");
<?php } ?>
</script>
