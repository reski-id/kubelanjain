<a onclick="reload_tabel();" class="btn btn-warning pull-right"><i class="material-icons" style="font-size:14px;">refresh</i> Refresh </a>
<br>
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
      <th width="30%">Level</th>
      <th width="64%">Keterangan</th>
      <th width="5%">Opsi</th>
    </tr>
  </thead>
  <tbody></tbody>
</table>

<?php $this->load->view("users/$urlnya/ajax"); ?>
