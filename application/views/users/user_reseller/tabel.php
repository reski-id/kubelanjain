<!-- <div class="col-12 col-md-8 pl-0"> -->
  <!-- <a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>');" class="btn btn-primary glow mb-1">+ <?php echo $judul_web; ?></a> -->
  <!-- <a href="javascript:aksi('import','','sync_form', '<?= $url_import; ?>');" class="btn btn-success glow mb-1">Import Data</a>
</div> -->
<style>
.select2-container {
  min-width: 100px !important;
}
</style>
<?php view('plugin/css/nav_tab_flat'); ?>
<div class="row" id="row_mobile">
    <div class="col-md-12">
      <div class="card widget-order-activity">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="card-title">Filter <?php echo $judul_web; ?></h4>
            <?php view("plugin/get/box_head_element"); ?>
        </div>
        <hr style="margin:0px;padding:0px;">
        <div class="card-content collapse show">
            <div class="card-body pt-1">
              <div class="row">
                <div class="col-12 col-md-1"></div>
                <?php view('plugin/v_select/provinsi_kota'); ?>
                <div class="col-12 col-md-2 pt-2">
                  <button onclick="RefreshTable()" class="btn btn-secondary btn-block">Cari</button>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>


<div class="row" id="tabel_data" hidden>
    <div class="col-md-12">
      <div class="card widget-order-activity">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="card-title" id="judul_form_card"><?php echo $judul_web; ?></h4>
            <?php view("plugin/get/box_head_element"); ?>
        </div>
        <hr style="margin:0px;padding:0px;">
        <div class="card-content collapse show">
            <ul class="nav nav-tabs nav-fill m-0" id="nav_stt" role="tablist">
              <?php
              $datanya = array('Pendaftaran','Aktif','Tidak Aktif');
              $datanya_icon = array('bx-detail','bx-check-circle','bx-block');
              ?>
              <?php foreach ($datanya as $key => $value): ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($key==0){ echo "active"; } ?>" id="status<?= $key; ?>-tab" data-toggle="tab" href="#status_<?= $key; ?>" aria-controls="<?= $key; ?>" role="tab" aria-selected="true" onclick="RefreshTable('<?= $key; ?>')">
                      <i class="bx <?= $datanya_icon[$key]; ?> align-middle"></i> <span class="align-middle"><?= $value; ?></span>
                    </a>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="card-body">
              <?php get_pesan('msg'); ?>
              <div class="table-responsive">
                <table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
                  <thead>
                    <tr>
                      <th width="1%">#</th>
                      <th>ID</th>
                      <th width="20%">Nama&nbsp;Lengkap</th>
                      <th width="10%">ID&nbsp;RESELLER</th>
                      <th width="10%">No.HP</th>
                      <th width="20%">Provinsi</th>
                      <th width="20%">Kab/Kota</th>
                      <th width="19%">Opsi</th>
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
