<?php get_pesan('msg'); ?>
<?php $level = get_session('level'); ?>
  <style>
  #judul_form_card{ display: block; }
  #tmb_trans{ float:right; }
  /* jika lebar layar dibawah 600px */
  @media only screen and (max-width: 600px) {
    #rangetglnya { padding: 10px !important; padding-bottom: 0px !important; }
  }
  <?php if (view_mobile()){ ?>
    .app-content .content-wrapper{
      padding: 0px !important;
      padding-top: 10px !important;
    }
    .nav-item { font-size: 10px !important; font-weight: bold; }
    .app-content .content-wrapper .card-body{
      padding: 0px !important;
      margin-top: -20px;
    }
    .card .card-header .card-title {
        margin-bottom: -10px;
        margin-top: 10px;
    }
  <?php }else{ ?>
    .app-content .content-wrapper .card-body{
      padding: 0px !important;
    }
  <?php } ?>
  </style>
<script type="text/javascript">
$(document).ready(function () {
  RefreshTable();
});
</script>
<style>
  td{ padding:5px !important; border-bottom: 1px solid #f1f1f1 !important; }
  .table tfoot td{ padding:5px !important; padding-top: 10px !important; padding-bottom: 10px !important; border: 1px solid #f1f1f1; background: #40566f; color: #fff; }
  .picker__table td {
    margin: 0;
    padding: 0 !important;
  }
  .tgl_dari.form-control[readonly], .tgl_sampai.form-control[readonly] { background:#fff !important; }
</style>
<div class="row" id="rangetglnya">
  <div class="col-md-3"></div>
  <div class="col-6 col-md-3">
    <label>Dari Tanggal</label>
    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" class="form-control tgl_dari" value="<?= tgl_format(tgl_now('tgl'),'d-m-Y','-3 Days'); ?>" readonly onchange="RefreshTable()">
        <div class="form-control-position">
            <i class='bx bx-calendar'></i>
        </div>
    </fieldset>
  </div>
  <div class="col-6 col-md-3">
    <label>Sampai Tanggal</label>
    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" class="form-control tgl_sampai" value="<?= tgl_format(tgl_now('tgl'),'d-m-Y',tgl_now('tgl')); ?>" readonly onchange="RefreshTable()">
        <div class="form-control-position">
            <i class='bx bx-calendar'></i>
        </div>
    </fieldset>
  </div>
</div>
<div class="table-responsive">
  <table class="table table-fixed table-bordered table-striped table-hover" width="100%">
    <thead class="thead-dark">
      <tr>
    <?php if (view_mobile()){
      $id_user = get_session('id_user');
      if (!empty($_GET['p'])) {
        if ($level==0) {
          $id_user = decode($_GET['p']);
        }
      }
      ?>
        <th colspan="2" style="text-transform: none !important;">TOTAL BENEFIT : Rp. <span id="t_benefit"></span> </th>
    <?php }else{ ?>
        <th width="10%" class="text-center">Nomor</th>
        <th width="5%" class="text-center">Time</th>
        <th width="40%" class="text-center">Detail&nbsp;Keterangan</th>
        <th width="15%" class="text-center">Debit</th>
        <th width="15%" class="text-center">Kredit</th>
        <th width="15%" class="text-center">Saldo</th>
    <?php } ?>
      </tr>
    </thead>
    <tbody id="dataSaldo"></tbody>
    <tfoot id="totalSaldo"></tfoot>
  </table>
</div>

<?php view("plugin/picker/date"); ?>
<?php view("plugin/picker/get_tgl"); ?>
<?php view("users/$url/ajax"); ?>
