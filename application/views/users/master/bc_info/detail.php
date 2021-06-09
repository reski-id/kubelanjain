<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $namanya = ucwords(preg_replace('/[_]/','&nbsp;',$value));
        if ($value=='tgl_input') {
          $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
        }else if ($value=='id_provinsi') {
          $namanya = 'Provinsi';
          $nama = get_name_provinsi($query[$value]);
        }else {
          $nama = $query[$value];
        }
        if ($value=='id_bc_info_group') {
          $namanya = 'Group';
          $nama = '<b>'.get_name_bc_info_group($query[$value]).'</b>';
        }
        ?>
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
