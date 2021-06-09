<?php if(check_permission('view', 'create', "users/bc_info")){ ?>
<div class="row">
  <div class="col-12">
    <a href="javascript:aksi('tambah','','sync_form', '<?= $url_modal; ?>');" class="btn btn-primary glow mb-1">+ <?php echo $judul_web; ?></a>
  </div>
  <div class="col-12">
    <hr>
  </div>
</div>
<?php } ?>
<?php get_pesan('msg'); ?>
<div class="table-responsive">
  <table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
    <thead>
      <tr>
        <th width="1%">#</th>
        <th>ID</th>
        <th width="80%">Nama&nbsp;Group</th>
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

<?php view("users/$url/$tbl/ajax"); ?>
