<div class="modal-body row">
  <?php
  $in_view = array("plu","nama_item","id_item_satuan","status","tgl_input","input_by","tgl_update","update_by");
  $img = $query['foto_item'];
  if (!file_exists($img)) { $img='img/item/null.png'; }
  ?>
  <div class="col-md-3">
    <center>
      <a class="example-image-link" href="<?= $img ?>" data-lightbox="example-set2">
        <img src="<?= $img ?>" class="img-thumbnail" width="250">
      </a>
    </center>
  </div>
  <div class="col-md-9">
    <table id="fileData" class="table table-bordered table-hover table-striped" width="100%">
      <tbody>
        <?php foreach (list_fields($tbl) as $key => $value):
          if (in_array($value,$in_view)) {
            $val = $query[$value];
            if (in_array($value, array('tgl_input','tgl_update'))) {
              if (empty($val)) { continue; }
              $nama = tgl_id(tgl_format($val,'d-m-Y H:i:s'));
            }elseif (in_array($value, array('input_by','update_by'))) {
              if (empty($val)) { continue; }
              $nama = explode(' - ', $val)[1];
            }elseif ($value == 'id_item_satuan') {
              $nama  = $query['nilai_satuan'].' '.get_name_item_satuan($query[$value]);
              $value = 'Berat';
            }elseif ($value == 'status') {
              $sttnya = array('Tidak Aktif', 'Aktif');
              $nama  = $sttnya[$query[$value]];
            }else {
              $nama = $val;
            }
            $value = strtoupper($value);?>
          <tr>
            <td width="120" id="n_<?php echo $value; ?>"> <label><?php echo ucwords(preg_replace('/[_]/','&nbsp;',$value)); ?></label> </td>
            <td width="1"><label>:</label> </td>
            <td><?php echo $nama; ?></td>
          </tr>
        <?php } ?>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal-footer mt-0">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>

<script src="assets/plugin/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
<link href="assets/plugin/bootstrap-fileupload/bootstrap-fileupload.min.css" rel="stylesheet">
<script type="text/javascript">
  $('#modal_judul').html('Detail <?= ucwords(preg_replace('/[_]/', ' ', $tbl)); ?>');
</script>
