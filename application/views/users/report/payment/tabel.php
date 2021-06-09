<?php get_pesan('msg'); ?>
<?php $level = get_session('level'); ?>
<div class="row" id="X_sel_kota">
  <!-- <div class="col-md-4 offset-1">
    <?php view('plugin/v_select/kota', array('all'=>1)); ?>
  </div> -->
  <div class="col-12 col-md-3"></div>
  <div class="col-md-3">
    <label>Dari Tanggal</label>
    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" class="form-control tgl_dari" id="tgl_dari" value="<?= tgl_format(tgl_now('tgl'),'d-m-Y','-7 Days'); ?>" onchange="RefreshTable()">
        <div class="form-control-position">
            <i class='bx bx-calendar'></i>
        </div>
    </fieldset>
  </div>
  <div class="col-md-3">
    <label>Sampai Tanggal</label>
    <fieldset class="form-group position-relative has-icon-left">
        <input type="text" class="form-control tgl_dari" id="tgl_sampai" value="<?= tgl_format(tgl_now('tgl'),'d-m-Y'); ?>" onchange="RefreshTable()">
        <div class="form-control-position">
            <i class='bx bx-calendar'></i>
        </div>
    </fieldset>
  </div>
</div>
<div class="table-responsive">
  <table id="tabelnya" class="table table-fixed table-bordered table-striped table-hover" width="100%">
    <thead id="dataHeadnya" class="thead-dark"></thead>
    <tbody id="dataBodynya"></tbody>
    <tfoot id="dataFootnya" class="tfoot-dark"></tfoot>
  </table>
</div>

<?php view("users/$url/payment/ajax"); ?>
<?php if (get_session('level')==0 && get_session('id_kota')!=''): ?>
<script type="text/javascript">
  if($('#X_sel_kota').length!=0){ $('#X_sel_kota').hide(); }
</script>
<?php endif; ?>
<?php view("plugin/picker/date"); ?>
<?php view("plugin/picker/get_tgl"); ?>
