<?php if($BATAS==1){ $BGnya = 'style="background:#475f7b; color:#f4f4f4;"'; }else{ $BGnya=''; } ?>
<li class="dd-item dd3-item" data-id="<?php echo $ID_MENU; ?>">
    <div class="dd-handle dd3-handle" <?= $BGnya; ?>></div>
    <div class="dd3-content" <?= $BGnya; ?>>
      <?php
      $icon = get_ICON_MENU($master_menu, $ICON, $BATAS);
      echo $BATAS==1 ? "<i class='bx bx-book'></i>$NAMA <font color='pink'>[GROUP MENU / BATAS MENU]</font>" : $icon.$NAMA;
      ?>
      <?php if (check_permission('view', 'delete', "setup/menu/".uri(3))): ?>
        <a href="javascript:Q_hapus('<?php echo encode($ID_MENU); ?>');" class="btn btn-danger glow btn-sm float-right" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="bx bx-trash"></i></a>
      <?php endif; ?>
      <?php if (check_permission('view', 'update', "setup/menu/".uri(3))): ?>
        <a href="setup/menu/<?php echo $akun_menu; ?>/e/<?php echo "$level_menu/$ID_MENU"; ?>" class="btn btn-success glow btn-sm float-right" style="margin-right:5px;" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil"></i></a>
        <div class="custom-control custom-switch custom-switch-glow custom-switch-primary mr-2 mb-1 float-right">
            <input type="checkbox" class="custom-control-input" id="customSwitch_<?= $ID_MENU; ?>" <?php if($STATUS==1){echo "checked";} ?> onclick="status_menu('<?php echo $ID_MENU; ?>');">
            <label class="custom-control-label" for="customSwitch_<?= $ID_MENU; ?>">
                <span class="switch-icon-left" data-toggle="tooltip" data-placement="top" title="" data-original-title="Aktif"><i class="bx bx-check"></i></span>
                <span class="switch-icon-right" data-toggle="tooltip" data-placement="top" title="" data-original-title="Tidak Aktif"><i class="bx bx-x"></i></span>
            </label>
        </div>
      <?php endif; ?>
    </div>
    <?php if ($jml_menu > 0): ?>
    <ol class="dd-list">
      <?php set_list_menu($akun_menu, $ID_MENU, $lanjut_level); ?>
    </ol>
    <?php endif; ?>
</li>
