<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
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
          $nama = '';
          $this->db->select('B.kecamatan as kec');
          $this->db->join('kecamatan AS B', 'B.id_kecamatan=A.id_kecamatan');
          foreach (get('pasar_kecamatan AS A', array('A.id_pasar'=>$query['id_pasar'], 'A.status'=>1))->result() as $key => $value) {
            $nama .= "$value->kec, ";
          }
          $nama = substr($nama, 0, -2);
        }elseif ($value=='status'){
          $stt = array('Tidak Aktif', 'Aktif');
          $nama = $stt[$query[$value]];
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
