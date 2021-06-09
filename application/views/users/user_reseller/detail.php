<?php $level=get_session('level'); ?>
<?php if ($level==0): ?>
<div class="modal-body p-1">
<?php endif; ?>
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <?php
    $data_tabnya[] = array('icon'=>'bxs-user-pin', 'name'=>'biodata', 'nama'=>'BIODATA');
    // $data_tabnya[] = array('icon'=>'bxs-bank', 'name'=>'bank', 'nama'=>'BANK');
    $data_tabnya[] = array('icon'=>'bxs-comment-detail', 'name'=>'sosmed', 'nama'=>'SOSIAL MEDIA');
    ?>
    <?php foreach ($data_tabnya as $key => $value): ?>
      <li class="nav-item ml-1">
          <a class="nav-link <?php if($key==0){ echo "active"; } ?>" id="<?= $value['name']; ?>-tab" data-toggle="tab" href="#<?= $value['name']; ?>" aria-controls="<?= $value['name']; ?>" role="tab" aria-selected="<?php if($key==0){ echo "true"; }else{ echo "false"; } ?>">
              <i class="bx <?= $value['icon']; ?> align-middle"></i>
              <span class="align-middle"><?= $value['nama']; ?></span>
          </a>
      </li>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content">
    <?php foreach ($data_tabnya as $key => $value): ?>
      <div class="tab-pane <?php if($key==0){ echo "active"; } ?>" id="<?= $value['name']; ?>" aria-labelledby="<?= $value['name']; ?>-tab" role="tabpanel">
        <div class="table-responsive">
          <?php view('users/user_reseller/detail_tab/'.$value['name']); ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

<?php if ($level==0): ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>
<?php endif; ?>

<script type="text/javascript">
$('#lebar_modalnya').removeClass('modal-md');
$('#lebar_modalnya').addClass('modal-lg');
$('#modal_judul').html("Detail Reseller");
</script>
