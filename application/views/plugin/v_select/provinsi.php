<?php
// $id_provinsi = get_session('id_provinsi');
// if (!empty($id_provinsi)) {
//   $id_provinsine = true;
// }else{
  $id_provinsine = false;
// }
?>
<select class="form-control" id="v_sel_provinsi" name="v_sel_provinsi" onchange="RefreshTable();" <?php if($id_provinsine){ echo "hidden"; } ?>>
  <?php if (!$id_provinsine): ?>
    <option value="">- Pilih Provinsi -</option>
  <?php endif; ?>
  <?php
  //if (!empty($all)) {
    //if (!$id_provinsine) {
    ?>
    <option value="0">SEMUA PROVINSI</option>
  <?php
    //}
  //}
  if ($id_provinsine) {
    $this->db->where('id_provinsi', $id_provinsi);
  }
  $this->db->order_by('provinsi', 'ASC');
  foreach (get('provinsi', array('status'=>1))->result() as $key => $value): ?>
    <option value="<?= $value->id_provinsi; ?>" <?php if($id_provinsine){ echo "selected"; } ?>><?= $value->provinsi; ?></option>
  <?php endforeach; ?>
</select>
