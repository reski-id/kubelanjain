<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl", "id_pelanggan"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $val = $query[$value];
        $namanya = ucwords(preg_replace('/[_]/',' ',$value));
        if (in_array($value, array('tgl_pengantaran','tgl_cancel','tgl_payment','tgl_done'))) {
          if (empty($val)) { continue; }
          $nama = tgl_id(tgl_format($val,'d-m-Y H:i'));
        }elseif (in_array($value, array('tgl_input','tgl_update'))) {
          if (empty($val)) { continue; }
          $nama = tgl_id(tgl_format($val,'d-m-Y'));
        }elseif (in_array($value, array('input_by','update_by','cancel_by','payment_by','done_by'))) {
          if (empty($val)) { continue; }
          $nama = explode(' - ', $val)[1];
        }else if ($value=='id_provinsi') {
          $namanya = 'Provinsi';
          $nama = get_name_provinsi($val);
        }else if ($value=='id_kota') {
          $namanya = 'Kota';
          $nama = get_name_kota($val);
        }else if ($value=='id_kecamatan') {
          $namanya = 'Kecamatan';
          $nama = get_name_kecamatan($val);
        }elseif ($value=='id_pasar'){
          $namanya = 'Pasar';
          $nama = get_name_pasar($val);
        }elseif ($value=='status'){
          $stt = array('ORDER', 'PAYMENT', 'DONE', 'CANCEL');
          $nama = $stt[$val];
        }elseif (in_array($value, array('ongkir','diskon','sub_total','total_belanja','real_payment','gap_real','benefit'))) {
          $nama = 'Rp. '.format_angka($val);
        }elseif (in_array($value, array('type_pembayaran'))) {
          $nama = (empty($val)) ? '-' : get_type_pembayaran($val);
        }elseif (in_array($value, array('alasan_cancel'))) {
          if (empty($val)) { continue; }
        }else{
          $nama = $val;
        } ?>
      <tr>
        <th width="180"><?= $namanya; ?></th>
        <th width="1"><label><b>:</b></label></th>
        <td><?= $nama; ?></td>
      </tr>
    <?php } ?>
    <?php endforeach; ?>
  </tbody>
</table>

<script type="text/javascript">
  $('#modal_judul').html('Detail Order');
</script>
