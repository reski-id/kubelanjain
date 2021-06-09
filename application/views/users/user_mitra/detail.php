<?php $level=get_session('level'); ?>
<?php if ($level==0): ?>
<div class="modal-body p-1">
<?php endif; ?>
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <?php
    if ($referral=='') {
      $data_tabnya[] = array('icon'=>'bxs-user-pin', 'name'=>'biodata', 'nama'=>'BIODATA', 'html'=>'');
      $data_tabnya[] = array('icon'=>'bxs-bank', 'name'=>'bank', 'nama'=>'BANK', 'html'=>'');
      $data_tabnya[] = array('icon'=>'bx-link', 'name'=>'link_add', 'nama'=>'LINK ADD', 'html'=>'');
    }else {
      if ($query['type_id']==1) {
        $data_tabnya[] = array('icon'=>'bx-id-card', 'name'=>'referral', 'nama'=>'MITRA II', 'html'=>'onclick="RefreshTableX(0)"');
      }
      $data_tabnya[] = array('icon'=>'bx-id-card', 'name'=>'referral', 'nama'=>'RESELLER', 'html'=>'onclick="RefreshTableX(1)"');
    }
    ?>
    <?php foreach ($data_tabnya as $key => $value): ?>
      <li class="nav-item ml-1">
          <a class="nav-link <?php if($key==0){ echo "active"; } ?>" id="<?= $value['name']; ?>_<?= $key; ?>-tab" data-toggle="tab" href="#<?= $value['name']; ?>" aria-controls="<?= $key; ?>" <?= $value['html']; ?> role="tab" aria-selected="<?php if($key==0){ echo "true"; }else{ echo "false"; } ?>">
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
          <?php view('users/user_mitra/detail_tab/'.$value['name']); ?>
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
<?php if($referral==''){ ?>
  $('#lebar_modalnya').removeClass('modal-md');
  $('#lebar_modalnya').addClass('modal-lg');
  $('#modal_judul').html("Detail <?= $query['nama_lengkap'] ?>");
  <?php }else{ ?>
  $('#lebar_modalnya').removeClass('modal-lg');
  $('#lebar_modalnya').addClass('modal-xl');
  $('#modal_judul').html("<?= $query['nama_lengkap'] ?> - <?= $query['id_mitra'] ?>");
<?php } ?>
</script>
