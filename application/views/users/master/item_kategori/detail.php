<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $val = $query[$value];
        if (in_array($value, array('input_date','update_date'))) {
          if (empty($val)) { continue; }
          $nama = tgl_id(tgl_format($val,'d-m-Y H:i:s'));
        }elseif (in_array($value, array('input_by','update_by'))) {
          if (empty($val)) { continue; }
          if ($val==1) {
            $this->db->select('username');
            $nama = get_field('user', array('id_user'=>$val))['username'];
          }else {
            $this->db->select('nama_lengkap');
            $nama = get_field('v_user', array('id_user'=>$val))['nama_lengkap'];
          }
        }elseif ($value == 'status') {
          $sttnya = array('Tidak Aktif', 'Aktif');
          $nama  = $sttnya[$query[$value]];
        }else {
          $nama = $val;
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

<script type="text/javascript">
  $('#modal_judul').html('Detail <?= ucwords(preg_replace('/[_]/', ' ', $tbl)); ?>');
</script>
