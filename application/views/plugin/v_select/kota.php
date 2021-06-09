<?php
$id_kota = get_session('id_kota');
if (!empty($id_kota)) {
  $id_kotane = true;
}else{
  $id_kotane = false;
}
?>
<select class="form-control" id="v_sel_kota" name="v_sel_kota" onchange="RefreshTable();" <?php if($id_kotane){ echo "hidden"; } ?>>
  <?php if (!$id_kotane): ?>
    <option value="">- Pilih Kota -</option>
  <?php endif; ?>
  <?php
  if (!empty($all)) {
    if (!$id_kotane) {?>
    <option value="0">SEMUA KOTA</option>
  <?php
    }
  }
  if ($id_kotane) {
    $this->db->where('id_kota', $id_kota);
  }
  $this->db->order_by('kota', 'ASC');
  foreach (get('kota', array('id_kota!='=>1, 'status'=>1))->result() as $key => $value): ?>
    <option value="<?= $value->id_kota; ?>" <?php if($id_kotane){ echo "selected"; } ?>><?= $value->kota; ?></option>
  <?php endforeach; ?>
</select>
