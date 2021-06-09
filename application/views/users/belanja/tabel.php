<style>
#fileData td {
  font-size: 12px !important;
}
.select2-container {
    min-width: 100px !important;
}
</style>
<?php if (check_permission('view', 'create', 'belanja')):
  $judul_web = "Belanja"; ?>
<div class="row">
  <div class="col-md-9"></div>
  <!-- <div class="col-md-3">
    <select class="form-control" name="cari_pasar" required>
      <option value=""> - Pilih Pasar - </option>
      <?php $this->db->select('id_pasar, pasar');
      $this->db->order_by('pasar', 'ASC');
      foreach (get('pasar', array('status'=>1))->result() as $key => $value): ?>
        <option value="<?= $value->id_pasar; ?>"><?= $value->pasar; ?></option>
      <?php endforeach; ?>
    </select>
  </div> -->

</div>
<?php endif; ?>

<div class="row">
  <div class="col-12 col-md-3 <?= (!view_mobile()) ? 'pr-0' : ''; ?>">
    <div class="position-relative has-icon-left ">
      <input type="text" name="cari_tgl" class="form-control" onchange="show_data()" value="<?= date('Y-m-d'); ?>" placeholder="" data-parsley-trigger="keyup" style="border-radius:0px;border-top-left-radius: 10px;<?= (view_mobile()) ? 'border-top-right-radius: 10px;' : ''; ?>">
      <div class="form-control-position"><i class="bx bx-calendar"></i></div>
    </div>
  </div>
  <div class="col-8 col-md-7 pr-0 <?= (!view_mobile()) ? 'pl-0' : ''; ?>">
    <input type="text" class="form-control" onkeyup="show_data()" id="cari_ordernya" placeholder="Search . . ." style="border-radius:0px;">
  </div>
  <div class="col-4 col-md-2 pl-0" id="select_custom_X">
    <select class="form-control" onchange="show_data()" id="vlist_order" style="border-radius:0px;<?= (view_mobile()) ? '' : 'border-top-right-radius: 10px;'; ?>">
      <option value="10">10 LIST...</option>
      <option value="15">25 LIST...</option>
      <option value="" selected>SEMUA</option>
    </select>
  </div>
</div>
<div class="<?php if(!view_mobile()){ echo "table-responsive"; } ?>">
  <table id="tabelnya" class="table table-fixed <?php if(!view_mobile()){ echo "table-bordered"; } ?> table-striped table-hover" width="100%" style="background: white;">
    <thead id="dataHeadnya" class="thead-dark"></thead>
    <tbody id="dataBodynya" style="border-bottom: 1px solid #ddd;"></tbody>
    <tfoot id="dataFootnya"></tfoot>
  </table>
</div>

<?php view("users/$url/ajax"); ?>
<?php view("plugin/picker/date"); ?>
<script type="text/javascript">
$(document).ready(function () {
  $('#vlist_order').select2('destroy');
});
$('[name="cari_tgl"]').daterangepicker({
  startDate: new Date(),
  singleDatePicker: true,
  timePicker: false,
  timePicker24Hour: false,
  locale: {
      "format": "DD MMMM YYYY",
  },
  setDate: moment.locale('en')
});
</script>
