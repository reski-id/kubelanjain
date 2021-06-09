<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl", "id_toko", "id_item_master"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $val = $query[$value];
        $namanya = ucwords(preg_replace('/[_]/','&nbsp;',$value));
        if (in_array($value, array('tgl_input','tgl_update'))) {
          $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
        }elseif (in_array($value, array('input_by','update_by'))) {
          if (empty($val)) { continue; }
          $nama = explode(' - ', $val)[1];
        }else if ($value=='id_provinsi') {
          $namanya = 'Provinsi';
          $nama = get_name_provinsi($query[$value]);
        }else if ($value=='id_kota') {
          $namanya = 'Kota';
          $nama = get_name_kota($query[$value]);
        }else if ($value=='id_kecamatan') {
          $namanya = 'Kecamatan';
          $nama = get_name_kecamatan($query[$value]);
        }else if ($value=='id_pasar') {
          $namanya = 'Pasar';
        }elseif ($value=='status'){
          $stt = array('Tidak Aktif', 'Aktif');
          $nama = $stt[$query[$value]];
        }elseif (in_array($value, array('harga', 'harga_beli', 'gap'))){
          $nama = format_angka($query[$value], 'rp');
        }else {
          $nama = $query[$value];
        }?>
      <tr>
        <th width="120" id="n_<?php echo $value; ?>"><?php echo $namanya; ?></th>
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
