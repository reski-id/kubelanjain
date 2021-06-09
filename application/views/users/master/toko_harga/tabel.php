<style>
#fileData td {
  font-size: 12px !important;
}
</style>
<?php if (check_permission('view', 'create', 'master/harga')): 
  $judul_web = 'Harga Produk';
  $id_toko   = decode(uri(3));
  $this->db->select('id_toko, id_kota, id_kecamatan, id_pasar');
  $get_toko = get_field('toko', array('id_toko'=>$id_toko));
  $id_kota = $get_toko['id_kota'];
  $id_kecamatan = $get_toko['id_kecamatan'];
  $id_pasar = $get_toko['id_pasar'];
?>

<div class="col-12">
  <a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>/<?= uri(3); ?>', 'md');" class="btn btn-primary glow mb-1">+ <?php echo $judul_web; ?></a>
</div>
<?php endif; ?>
<?php view('plugin/css/nav_tab_flat'); ?>
<div class="row" id="tabel_data">
    <div class="col-md-12">
      <div class="card widget-order-activity">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="card-title" id="judul_form_card"><?= get_name_toko($id_toko); ?> <small>(PASAR <?= get_name_pasar($id_pasar) ." - ". get_name_kota($id_kota) ." - KECAMATAN ". get_name_kecamatan($id_kecamatan) ; ?>)</small> </h4>
            <?php view("plugin/get/box_head_element"); ?>
        </div>
        <hr style="margin:0px;padding:0px;">
        <div class="card-content collapse show">
            <ul class="nav nav-tabs nav-fill m-0" id="nav_stt" role="tablist">
              <?php
              $datanya = array('Aktif', 'Tidak Aktif');
              $datanya_icon = array('bx-check-circle','bx-block');
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
                      <th width="20%">Nama Item</th>
                      <th width="20%">Harga Beli</th>
                      <th width="20%">Harga Pasar</th>
                      <th width="20%">Gap</th>
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

<?php view("users/$url/$tbl/ajax"); ?>
