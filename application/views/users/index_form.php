<?php if (!empty($head_tambah)):
  if ($head_tambah=='v_sel_kota') {
    $v_order=0;
  }
endif;
?>
<div class="row" id="row_mobile">
  <?php if ($col <= 6): ?>
    <div class="col-md-<?php echo $col/2; ?>"></div>
  <?php endif; ?>
    <div class="col-md-<?= $col; ?>">
      <div class="card widget-order-activity">
        <div class="card-header <?php if($v_order==0){ ?>d-md-flex <?php } ?> justify-content-between align-items-center">
            <h4 class="card-title" id="judul_form_card"><?php echo $judul_web; ?></h4>
            <?php if($v_order==0){ ?><div class="header-right mt-md-0 mt-50"> <?php } ?>
              <?php if (!empty($head_refresh)): ?>
                <a href="<?php if($head_refresh==1){ echo "javascript:reload_tabel();"; }else{ echo $head_refresh; } ?>" class="btn btn-warning glow"><i class="bx bx-rotate-left"></i> <span>Refresh</span></a>
              <?php endif; ?>
              <?php if (!empty($head_tambah)):
                if ($head_tambah=='v_sel_kota') {
                  if (get_session('level')==0 && get_session('id_kota')==''): ?>
                    <div class="col-md-3">
                      <?php view('plugin/v_select/kota', array('all'=>1)); ?>
                    </div><?php
                  endif;
                }else {
                  echo $head_tambah;
                }
              endif; ?>
            <?php if($v_order==0){ ?></div> <?php } ?>
        </div>
        <?php if($v_order==0){ ?><hr style="margin:0px;padding:0px;padding-bottom:20px;"><?php } ?>
        <div class="card-content">
            <div class="card-body">
              <?php view($view); ?>
            </div>
        </div>
      </div>
    </div>
</div>
