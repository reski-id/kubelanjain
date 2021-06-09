<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <tr>
      <th width="120">ID RESELLER</th>
      <th width="1">:</th>
      <td> <span class="badge badge-secondary"><?= $query['id_mitra']; ?></span> </td>
    </tr>
    <?php
    $ref=false;
    if (!in_array($query['id_referal'], array('', null))){
      $ref=true;
    }
    $not_view = array("id_$tbl", "password", "foto", "mode", "level", "status", "id_user_biodata", "id_mitra", "type_id", "id_user", "id_provinsi", "id_kota", "id_referal"); ?>
    <?php foreach (list_fields("$tbl") as $key => $value):
      if (!in_array($value,$not_view)) {
        $namanya = $value;
        if (in_array($value, array('tgl_update','tgl_input'))) {
          if ($query[$value]=='') {
            $nama = '-';
          }else {
            $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
          }
          if ($value=='tgl_input') {
            $namanya = 'Tanggal Terdaftar';
          }
        }elseif (in_array($value, array('password'))) {
          $nama = decode($query[$value]);
          if (get_session('id_user')!=1) {
            continue;
          }
        }else {
          $nama = $query[$value];
        }
        $namanya = ucwords(preg_replace('/[_]/',' ', $namanya));
        ?>
      <tr>
        <th width="120" id="n_<?php echo $value; ?>"><?php echo preg_replace('/[ ]/','&nbsp;', $namanya); ?></th>
        <th width="1">:</th>
        <td><?= $nama; ?></td>
      </tr>
    <?php } ?>
    <?php endforeach; ?>
    <?php if ($ref): ?>
      <tr>
        <th>MITRA</th>
        <th>:</th>
        <td><?= "<b>".get_name_mitra($query['id_referal'])."</b>"; ?></td>
      </tr>
      <tr>
        <th>ID MITRA</th>
        <th>:</th>
        <td> <span class="badge badge-danger"><?= $query['id_referal']; ?></span> </td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>
