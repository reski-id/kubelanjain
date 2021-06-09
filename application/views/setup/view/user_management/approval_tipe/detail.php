<style>
  #table th, #table td {
    font-size: 12px !important;
  }
</style>
<!-- <div class="modal-body"> -->

<table id="table" class="table table-bordered table-hover table-striped" width="100%">
  <tbody>
    <?php $view = array("tipe","ket"); ?>
    <?php foreach ($view as $key => $value):
        if (in_array($value, array('tgl_input','tgl_update'))) {
          if (!empty($query[$value])) {
            $nama = tgl_id(tgl_format($query[$value],'d-m-Y H:i:s'));
          }
        }else {
          $nama = $query[$value];
        }
        ?>
      <tr>
        <th width="50" id="n_<?php echo $value; ?>"><?php echo ucwords(preg_replace('/[_]/','&nbsp;',$value)); ?></th>
        <th width="1">:</th>
        <td><?php echo $nama; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php
$get = get_list_approval('form', $query['id_approval_tipe'], $id_user);
$get_list = $get['get_list'];
if (!empty($get_list)) {
 $jml_app = $get_list->num_rows(); ?>
<?php if ($jml_app!=0): ?>
  <!-- <label class="ml-1" style="font-size:20px;">LIST APPROVAL</label> -->
  <div class="row pl-1 pr-1">
    <?php $i=0;
    foreach ($get_list->result() as $key => $value):
      $i++;
      $nama_akses = get_name_akses_user_approval($value->jenis_akses, $value->nama_gudang, $value->nama_akses);
      $lebarnya = '';
      if ($jml_app == 1) {
        $lebarnya = 4;
      }elseif($jml_app == 2) {
        $lebarnya = 2;
      } ?>
      <?php if ($lebarnya!='' && $key==0): ?>
        <div class="col-6 col-md-<?= $lebarnya; ?>" style="padding:7px;"></div>
      <?php endif; ?>
      <div class="col-6 col-md-4" style="padding:7px;">
        <div class="card border mb-0">
          <div class="card-heading text-center bg-primary" style="padding:5px;">
            <label class="text-white">Approval <?= KonDecRomawi($i); ?></label>
          </div>
          <hr style="padding:0px;margin:0px;">
          <div class="card-content text-center" style="padding:5px;">
            <label><i class="bx bx-user pr-0 mr-0" style="font-size:12px;"></i>&nbsp;<?= $value->nama_lengkap; ?></label>
            <hr style="margin:5px;"/>
            <small><i class="bx bx-briefcase pr-0 mr-0" style="font-size:12px;"></i>&nbsp;<?= $nama_akses; ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <hr>
<?php endif;
} ?>

<!-- </div> -->
<!-- <div class="modal-footer"> -->
  <button type="button" class="btn btn-danger glow float-right mr-1 mb-1" data-dismiss="modal"> <span>TUTUP</span> </button>
<!-- </div> -->

<script type="text/javascript">
$('#modal_judul').html("Detail Approval");
</script>
