<?php
$permission = check_permission('view', 'update', "user_management/data_user");
if($BATAS==1){
  $BGnya = 'style="background:#475f7b; color:#f4f4f4;"';
}else{
  $BGnya ='style="color:#596F88;"';
  if ($jml_menu > 0) {
    $BGnya .='data-toggle="collapse"';
  }
}
$jml_akses_menu = opsi_akses_menu('jml');
?>
<div class="card collapse-header">
    <div id="headingCollapse<?= $ID_MENU; ?>" class="card-header" <?= $BGnya; ?> role="button" data-target="#collapse<?= $ID_MENU; ?>" aria-expanded="false" aria-controls="collapse<?= $ID_MENU; ?>">
        <span class="collapse-title">
          <?php
          if ($BATAS==1) {
            echo "<span class='float-left mr-1'><span class='bx bx-book'></span> $NAMA <font color='pink'>[GROUP MENU / BATAS MENU]</font></span>";
            if ($permission) {
              $opsi = 'read';
              $checked = check_permission_menu($ID_MENU, $id_user, $opsi);
              if ($checked) { $checked='checked'; }else{ $checked=""; }
              ?>
              <div class="custom-control custom-switch custom-switch-glow custom-switch-info mr-1 float-left">
                  <input type="checkbox" class="custom-control-input" id="customSwitch_<?= $ID_MENU; ?>_read" <?= $checked; ?> onclick="permission_menu('<?php echo $ID_MENU; ?>', '<?= $opsi; ?>');">
                  <label class="custom-control-label" for="customSwitch_<?= $ID_MENU; ?>_<?= $opsi; ?>">
                      <span class="switch-icon-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php if($checked){ echo "Sembunyikan"; }else{ echo "Tampilkan"; }  ?>"><i class="bx bx-check"></i></span>
                      <span class="switch-icon-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sembunyikan"></span>
                  </label>
              </div>
              <?php
              for ($i=$jml_akses_menu; $i >=0; $i--) {
                echo "<small class='float-right pr-2'> &nbsp;".opsi_akses_menu($i)."&nbsp; </small>";
              }
            }
          }
          if ($BATAS==0):
            $icon = get_ICON_MENU($master_menu, $ICON, $BATAS);
            echo $icon.$NAMA;
            if ($permission){
              for ($i=$jml_akses_menu; $i >=0; $i--) {
                $opsi  = opsi_akses_menu($i);
                $warna = opsi_akses_menu_warna($i);
                $checked = check_permission_menu($ID_MENU, $id_user, strtolower($opsi));
                if ($checked) { $checked='checked'; }else{ $checked=""; }
                $hide_checked=''; $disabled_checked='';
                if ($jml_menu > 0 && $i!=1) { $hide_checked='style="opacity:0"'; $disabled_checked='disabled'; }
                ?>
                <div class="custom-control custom-switch custom-switch-glow custom-switch-<?= $warna; ?> mr-2 float-right" <?= $hide_checked; ?> style="margin-right: 1.7rem !important;">
                    <input type="checkbox" class="custom-control-input" id="customSwitch_<?= $ID_MENU; ?>_<?= strtolower($opsi); ?>" <?= $checked; ?> onclick="permission_menu('<?php echo $ID_MENU; ?>', '<?= strtolower($opsi); ?>');" <?= $disabled_checked; ?>>
                    <label class="custom-control-label" for="customSwitch_<?= $ID_MENU; ?>_<?= strtolower($opsi); ?>">
                        <span class="switch-icon-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php if($disabled_checked==''){ if($checked){ ?>Blokirkan <?php }else{ echo "Aktifkan"; } ?> <?= $opsi; ?><?php } ?>"><i class="bx bx-check"></i></span>
                        <span class="switch-icon-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php if($disabled_checked==''){ ?>Blokirkan <?= $opsi; ?><?php } ?>"></span>
                    </label>
                </div>
            <?php
              }
            }
          endif; ?>
        </span>
    </div>
    <?php if ($jml_menu > 0) { ?>
    <div id="collapse<?= $ID_MENU; ?>" role="tabpanel" aria-labelledby="headingCollapse<?= $ID_MENU; ?>" class="collapse">
        <div class="card-content pl-1">
          <!-- MENU -->
          <?php
            get_menu_list($akun_menu, $ID_MENU, $lanjut_level, $id_user);
          ?>
        </div>
    </div>
    <?php } ?>
</div>
