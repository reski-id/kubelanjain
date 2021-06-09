<?php $total = 0;
$this->db->query("CALL cek_benefit($id, @saldo)");
$cek_q = $this->db->query("SELECT @saldo AS saldo")->row();
if (!empty($cek_q)) {
  $total = $cek_q->saldo;
}
$catatan = 'Pembayaran Fee '.$query['nama_lengkap'];
del_nomor('ket', "PF$id");
$no_transaksi = get_nomor('P', "PF$id");
?>
<form id="sync_form" action="javascript:simpan('sync_form','<?= $urlnya."/".encode($id); ?>','','swal','3','','1');" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <b>ID MITRA <?= $query['type_id']; ?> : <?= $query['id_mitra']; ?></b> <br />
  <b>NAMA : </b> <?= $query['nama_lengkap']; ?><br />
  <b>No HP : </b> <?= $query['no_hp']; ?>
  <hr>
  <div id="pesannya"></div>
  <input type="hidden" name="no_transaksi" value="<?= $no_transaksi; ?>">
  <input type="hidden" name="tipe" value="<?= $query['type_id']; ?>">
  <?php
  $datanya[] = array('type'=>'text','name'=>'total_fee','nama'=>'Total Fee','icon'=>'money','html'=>'required readonly', 'value'=>'Rp. '.number_format($total,0,",","."));
  if ($total > 0) {
    $datanya[] = array('type'=>'text','name'=>'total_bayar','nama'=>'Total Bayar','icon'=>'money','html'=>'required onkeyup="set_rp()" data-parsley-validation-threshold="1" data-parsley-trigger="keyup"onkeypress="return hanyaAngka(event)"', 'value'=>$total);
    $datanya[] = array('type'=>'textarea','name'=>'catatan','nama'=>'Catatan','icon'=>'file','html'=>'required minlength="1"', 'value'=>$catatan);
  }
  data_formnya($datanya);
  ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
<?php if ($total > 0): ?>
  <?php if (check_permission('view', 'create', 'users/pembayaran_benefit')): ?>
    <button type="submit" class="btn btn-primary glow" name="simpan"> <span>Bayar Sekarang</span> </button>
  <?php endif; ?>
<?php endif; ?>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
$('#modal_judul').html('Detail Pembayaran Fee');
set_rp();
function set_rp()
{
  formatRupiah('total_bayar','Rp. ');
}

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
  }
}
</script>
