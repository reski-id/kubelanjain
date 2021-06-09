<style>
#fileData td {
  font-size: 12px !important;
}
</style>
<?php if (check_permission('view', 'create', 'update_harga')):
  $judul_web = "Update Harga"; ?>
<div class="row">
  <div class="col-md-8">
    <a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>', 'md');" class="btn btn-primary glow mb-1">+ Update Harga</a>
    <a href="javascript:aksi('import','','sync_form', '<?= $url_import; ?>/modal');" class="btn btn-success glow mb-1">Import Data</a>
    <a href="<?= $tbl; ?>/export.html" class="btn btn-secondary glow ml-1 mb-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a>
  </div>
  <div class="col-md-4">
    <select class="form-control" name="cari_pasar" onchange="RefreshTable();" required>
      <option value=""> - Pilih Pasar - </option>
      <?php $this->db->select('id_pasar, pasar');
      $this->db->order_by('pasar', 'ASC');
      foreach (get('pasar', array('status'=>1))->result() as $key => $value): ?>
        <option value="<?= $value->id_pasar; ?>" <?php if($value->id_pasar==16) {echo "selected";} ?>><?= $value->pasar; ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div hidden>
    <div class="position-relative has-icon-left ">
      <input type="text" name="cari_tgl" onchange="RefreshTable();" class="form-control" value="<?= date('Y-m-d'); ?>" placeholder="" data-parsley-trigger="keyup">
      <div class="form-control-position"><i class="bx bx-calendar"></i></div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="row" id="tabel_data">
    <div class="col-md-12">
      <div class="card widget-order-activity">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="card-title" id="judul_form_card"><?php echo $judul_web; ?></h4>
            <?php view("plugin/get/box_head_element"); ?>
        </div>
        <hr style="margin:0px;padding:0px;">
        <div class="card-content collapse show">
            <div class="card-body">
              <?php get_pesan('msg'); ?>
              <div class="table-responsive">
                <table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                    <tr>
                      <th width="1%" class="text-center">#</th>
                      <th>ID</th>
                      <!-- <th width="20%" class="text-center">Pasar</th> -->
                      <th width="20%" class="text-center">Kategori</th>
                      <th width="25%" class="text-center">Nama&nbsp;ITEM</th>
                      <th width="15%" class="text-center">Harga&nbsp;Pasar TOS&nbsp;3000</th>
                      <th width="15%" class="text-center">Harga&nbsp;Jual</th>
                      <th width="10%" class="text-center">Harga&nbsp;Tawar</th>
                      <th width="14%" class="text-center">Opsi</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>




<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md" id="lebar_modalnya">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal_judul"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
      </div>
      <div id="modal_datanya">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php view("users/$url/ajax"); ?>
<?php view("plugin/picker/date"); ?>
<script type="text/javascript">
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
