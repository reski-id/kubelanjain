<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        if ($value=='tgl_input') {
          $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
        }elseif ($value=='status'){
          $stt = array('Tidak Aktif', 'Aktif');
          $nama = $stt[$query[$value]];
        }else {
          $nama = $query[$value];
        }?>
      <tr>
        <th width="120" id="n_<?php echo $value; ?>"><?php echo ucwords(preg_replace('/[_]/','&nbsp;',$value)); ?></th>
        <th width="1">:</th>
        <td><?php echo $nama; ?></td>
      </tr>
    <?php } ?>
    <?php endforeach; ?>
  </tbody>
</table>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>
