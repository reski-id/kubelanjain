<?php if (check_permission('view', 'create', "setup/api")): ?>
<a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>/set_<?= $tbl; ?>');" class="btn btn-primary glow">+ <?php echo strtoupper($judul_web); ?></a>
<a href="javascript:reload_tabel();" class="btn btn-warning glow float-right"><i class="bx bx-rotate-left"></i> <span>Refresh</span></a>
<hr>
<?php endif; ?>
<?php get_pesan('msg'); ?>
<style>
  th, td { font-size: 12px; }
</style>
<table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
  <thead>
    <tr>
      <th width="1%">#</th>
      <th>ID</th>
      <th width="20%">Nama</th>
      <th width="29%">API Key</th>
      <th width="30%">Keterangan</th>
      <th width="20%">Opsi</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md">
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
<?php $this->load->view("$url/view/$tbl/ajax"); ?>
