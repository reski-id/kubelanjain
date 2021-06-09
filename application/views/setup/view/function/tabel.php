<a onclick="aksi('tambah');" class="btn btn-primary">+ <?php echo $judul_web; ?></a>
<a onclick="reload_tabel();" class="btn btn-warning pull-right"><i class="material-icons" style="font-size:14px;">refresh</i> Refresh </a>
<hr>
<?php get_pesan('msg'); ?>
<style>
  th, td { font-size: 12px; }
</style>
<table id="fileData" class="table table-bordered table-striped table-hover" width="100%">
  <thead>
    <tr>
      <th width="1%">#</th>
      <th>ID</th>
      <th width="30%">Nama</th>
      <th width="54%">Keterangan</th>
      <th width="15%">Opsi</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<?php $this->load->view("users/$urlnya/form"); ?>
<?php $this->load->view("users/$urlnya/detail"); ?>
<?php $this->load->view("users/$urlnya/ajax"); ?>
