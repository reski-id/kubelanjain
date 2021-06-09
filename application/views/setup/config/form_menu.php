<?php
$get_url = uri('x');
foreach (get_menu($master_menu,$level_menu)['result'] as $key => $value):
  $ID_MENU = $value->id_menu;
  $NAMA = $value->nama;
  $ICON = $value->icon;
  $URL  = $value->url;
  $URLnya = get_URLnya($URL);
  // ==== SUB MENU ==== //
  $lanjut_level = $level_menu+1;
  $sub_menu = get_menu($ID_MENU, $lanjut_level);
  $jml_menu = $sub_menu['jml'];
  $tanda_plus = $sub_menu['plus'];
  // ==== SUB MENU ==== //
  if ((strtolower($NAMA)=="setup" || strtolower($NAMA)=="data master")) {
    return '';
  }
if ($NAMA!='<!-->') {
  $index_active=0; $icon_style='';
  // if (list_function($NAMA)->num_rows() != 0 ) {
  ?>
  <div class="panel-group" id="accordion_<?php echo $ID_MENU; ?>" role="tablist" aria-multiselectable="true">
    <div class="panel panel-col-teal">
        <div class="panel-heading" role="tab" id="headingOne_<?php echo $ID_MENU; ?>">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion_<?php echo $ID_MENU; ?>" href="#collapseOne_<?php echo $ID_MENU; ?>" aria-expanded="true" aria-controls="collapseOne_<?php echo $ID_MENU; ?>">
                  <?php if ($master_menu==0){ ?>
                    <?php if($ICON==''){ $ICON='label'; } ?>
                    <i class="material-icons" <?php echo $icon_style; ?>><?php echo $ICON; ?></i>
                  <?php }else{ ?>
                    <?php if($ICON!=''){ ?> <i class="material-icons" <?php echo $icon_style; ?>><?php echo $ICON; ?></i> <?php } ?>
                  <?php } ?>
                    <span><?php echo $NAMA; ?></span>
                </a>
            </h4>
        </div>
        <div id="collapseOne_<?php echo $ID_MENU; ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne_1">
            <div class="panel-body">
                <?php $check = strlen($NAMA)+1;
                foreach (list_function($NAMA)->result() as $key => $value) {
                  $id_func = $value->id_set_function;
                  $nama    = ucwords(preg_replace('/[_]/',' ',substr($value->nama,$check)));
                  $where = array('id_set_function'=>$id_func, 'level'=>uri(4));
                  if (get('v_level_akses', $where)->num_rows() != 0) {
                    $checked = 'checked';
                  }else {
                    $checked = '';
                  }?>
                  <input type="hidden" name="menu[]" value="<?php echo $value->nama; ?>">
                  <input type="checkbox" id="<?php echo $id_func; ?>" name="get[<?php echo $id_func; ?>]" value="<?php echo $id_func; ?>" <?php echo $checked; ?>>
                  <label for="<?php echo $id_func; ?>" title="<?php echo $value->ket; ?>"><?php echo $nama; ?></label>; &nbsp;<br>
                <?php } ?>
                <?php form_menu($ID_MENU, $lanjut_level); ?>
            </div>
        </div>
    </div>
  </div><?php
  // }
}
endforeach;
?>
