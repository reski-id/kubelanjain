<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $view = array("nama_lengkap","no_hp","username","password","status", "jenis_akses","id_gudang_kota","id_gudang","tgl_input"); ?>
    <?php foreach ($view as $key => $value):
        if ($value=='tgl_input') {
          $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
        }else {
          $nama = $query[$value];
        }
        if ($value=='password') {
          $nama = decode($query[$value]);
        }
        if ($value=='status') {
          if ($query[$value]=='1') {
            $nama = 'Aktif';
          }else {
            $nama = 'Tidak Aktif';
          }
        }
        if ($value=='jenis_akses') {
          $ja_arr = array('Admin', 'Gudang', 'Lainnya');
          $nama = $ja_arr[$query[$value]];
        }
        if ($value=='id_gudang_kota') {
          if ($query[$value]==0) {
            continue;
          }
          $nama = get_name_gudang_kota($query[$value]);
          $value = "Gudang/Kota";
        }
        if ($value=='id_gudang') {
          if ($query['jenis_akses'] == 1){
            $nama = get_name_gudang($query[$value]);
          }elseif ($query['jenis_akses'] == 2){
            $nama = get_name_management_akses($query['id_management_akses']);
          }else {
            continue;
          }
          $value = 'Akses';
        }
        ?>
      <tr>
        <th width="120" id="n_<?php echo $value; ?>"><?php echo ucwords(preg_replace('/[_]/','&nbsp;',$value)); ?></th>
        <th width="1">:</th>
        <td><?php echo $nama; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>

<script type="text/javascript">
$('#modal_judul').html("Detail User");
</script>
