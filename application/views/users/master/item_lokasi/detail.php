<div class="modal-body">

<table class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $not_view = array("id_$tbl"); ?>
    <?php foreach (list_fields($tbl) as $key => $value):
      if (!in_array($value,$not_view)) {
        $val = $query[$value];
        $namanya = ucwords(preg_replace('/[_]/','&nbsp;',$value));
        if (in_array($value, array('tgl_input','tgl_update'))) {
          if (empty($query[$value])) { continue; }
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
          $nama = get_name_pasar($query[$value]);
        }elseif ($value=='id_item_kategori') {
          $namanya = 'Kategori';
          $nama = get_name_item_kategori($query[$value]);
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

<style>
#tabelnya tbody th, tbody td {
    padding: 5px !important;
}
</style>
<?php view('plugin/style/table_scroll'); ?>
<!-- <div class="col-md-12"> -->
  <?php
  $t_head = 'padding: 10px !important;';
  $t_body = 'padding: 10px !important;';
  ?>
  <!-- <label>ITEM :</label> -->
  <div class="row">
    <div class="col-md-12">
      <input type="search" class="form-control" id="cari_list_tabel" onkeyup="searchTable()" value="" placeholder="Cari PLU, NAMA ITEM . . ." style="border-radius:0px;border-top-left-radius: 10px;border-top-right-radius: 10px;"/>
    </div>
    <div class="col-md-12 <?php if(!view_mobile()){ echo "table-responsive"; } ?>">
      <table id="tabelnya" class="table table-fixed <?php if(!view_mobile()){ echo "table-bordered"; } ?> table-striped table-hover scroll" width="100%">
        <thead class="thead-dark">
          <tr>
            <th width="1%" class="text-center">#</th>
            <th width="15%" class="text-center" style="<?= $t_head; ?>">PLU</th>
            <th width="84%" class="text-center" style="<?= $t_head; ?>">Nama&nbsp;Item</th>
          </tr>
        </thead>
        <tbody id="v_list_detail">
          <?php $total=0;
          $this->db->select('plu, nama_item');
          $this->db->order_by('plu', 'ASC');
          foreach (get('item_lokasi_detail', array('id_item_lokasi'=>$query['id_item_lokasi']))->result() as $key => $value)
          { $total++; ?>
            <tr>
              <td width="1%" class="text-center"><label><?= format_angka($key+1); ?></label></td>
              <td width="15%" style="<?= $t_body; ?>"><label><?= $value->plu; ?></label></td>
              <td width="84%" style="<?= $t_body; ?>"><label><?= $value->nama_item; ?></label></td>
            </tr><?php
          } ?>
        </tbody>
      </table>
    </div>
    <div class="col-md-12">
      <label>Total Item : <span id="vtotal_check"><?= $total; ?></span></label>
    </div>
  </div>
<!-- </div> -->

</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>

<script type="text/javascript">
document.getElementById("cari_list_tabel").addEventListener("search", function(event) {
  searchTable();
});
function searchTable() {
    var input;
    var saring;
    var status;
    var tbody;
    var tr;
    var td;
    var i;
    var j;
    input = document.getElementById("cari_list_tabel");
    saring = input.value.toUpperCase();
    tbody = document.getElementById("v_list_detail");
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(saring) > -1) {
                status = true;
            }
        }
        if (status) {
            tr[i].style.display = "";
            status = false;
        } else {
            tr[i].style.display = "none";
        }
    }
}
</script>
