<?php if (check_permission('view', 'create', "user_management/data_user")){ ?>
<a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>/<?= uri(3); ?>');" class="btn btn-primary glow">+ Approval</a>
<a href="javascript:reload_tabel();" class="btn btn-warning glow float-right"><i class="bx bx-rotate-left"></i> <span>Refresh</span></a>
<hr>
<?php } ?>
<?php get_pesan('msg'); ?>
<div class="table-responsive">
  <table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
    <thead>
      <tr>
        <th width="1%">#</th>
        <th>ID</th>
        <th width="35%">Tipe</th>
        <th width="45%">Keterangan</th>
        <!-- <th width="30%">LIST&nbsp;Approval</th> -->
        <th width="19%">Opsi</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>


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

<?php view("setup/view/$url/$tbl/ajax"); ?>
