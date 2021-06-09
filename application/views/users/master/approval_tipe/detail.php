<div class="p-1">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $val = $query[$value];
        if (in_array($value, array('tgl_input','tgl_update'))) {
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
          // $sttnya = array('Tidak Aktif', 'Aktif');
          // $nama  = $sttnya[$query[$value]];
          continue;
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

<?php
$usernya=''; $id_user=array();
$get = get_list_approval('detail', $id);
if (!empty($get['get_list'])) {
echo $get['get_user'];
$get_list = $get['get_list'];
?>
<?php if ($get_list->num_rows()!=0): ?>
  <br>
  <label class="mt-1" style="font-size:20px;">LIST APPROVAL</label>
  <div class="row pl-1 pr-1">
    <?php $i=0;
    foreach ($get_list->result() as $key => $value):
      $i++;
      $nama_akses = get_name_akses_user_approval($value->jenis_akses, $value->nama_gudang, $value->nama_akses);
    ?>
      <div class="col-6 col-md-4" style="padding:7px;">
        <div class="card border mb-0">
          <div class="card-heading text-center bg-primary" style="padding:5px;">
            <label class="text-white">Approval <?= KonDecRomawi($i); ?></label>
          </div>
          <hr style="padding:0px;margin:0px;">
          <div class="card-content text-center" style="padding:5px;">
            <label><i class="bx bx-user pr-0 mr-0" style="font-size:12px;"></i>&nbsp;<?= $value->nama_lengkap; ?></label>
            <hr style="margin:5px;"/>
            <small><i class="bx bx-briefcase pr-0 mr-0" style="font-size:12px;"></i>&nbsp;<?= $nama_akses; ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif;
}?>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>
