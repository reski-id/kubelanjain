<?php $level=get_session('level'); ?>
<?php //if ($level==0): ?>
<div class="modal-body p-0 m-1">
<?php //endif; ?>
  <ul class="nav nav-tabs nav-justified" role="tablist">
    <?php
      $data_tabnya[] = array('icon'=>'bxs-user-pin', 'name'=>'order', 'nama'=>'ORDER', 'html'=>'');
      $data_tabnya[] = array('icon'=>'bxs-basket', 'name'=>'item', 'nama'=>'Item', 'html'=>'');
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
        <?php view('users/order/detail_tab/'.$value['name']); ?>
      </div>
    <?php endforeach; ?>
  </div>

</div>
<hr>
<div class="pl-1 pr-1 pb-1">
  <label id="btn-cancel"></label>
  <button type="button" class="btn btn-danger glow float-right mb-1" data-dismiss="modal"> <span>TUTUP</span> </button>
</div>

<script type="text/javascript">
  $('#modal_judul').html('Detail Order');
</script>
