<?php $get_url = uri('x');
if ($STATUS==0) { $hidden='hidden'; }else{ $hidden=''; }
if (get_session('level')==0 && get_session('id_user')!=1) {
  // if (in_array($ID_MENU, array(18,19,11,5,1,31))) {
  //   $hidden='hidden';
  // }
  if ($hidden=='') {
    $anwarsptr = check_permission('view', 'read', '', $ID_MENU);
    if (!$anwarsptr) {
      $hidden='hidden';
    }
  }
}
if (get_session('level')==1 && get_session('type_id')!=1) {
  if (in_array($ID_MENU, array(75))) {
    $hidden='hidden';
  }
}
if ($BATAS==1) {
  echo '<li class=" navigation-header" '.$hidden.' id="menunya_'.$ID_MENU.'"><span>'.get_ICON_MENU($master_menu, $ICON).' '.$NAMA.'</span></li>';
}else{
  $index_active=0;
  if(id_menu_active($akun_menu,$get_url)==$ID_MENU || $get_url==$URL){
    $index_active=1;
  }
  ?>
  <li class="<?php if($index_active==1){echo 'active';} ?> nav-item <?php if(in_array(uri(1), array('order','order2')) && $URLnya=='order/menu.html') { echo "active"; }; ?>" id="menunya_<?= $ID_MENU; ?>" <?= $hidden; ?>>
      <a href="<?php echo $URLnya; ?>" class="<?php echo $tanda_plus; ?> <?php if($get_url==$URL){echo 'toggled';}?>">
        <?php echo get_ICON_MENU($master_menu, $ICON, 1); ?>
        <span><?php echo $NAMA; ?></span>
        <?php
        if ($URLnya=='kontak.html'):
            $this->db->select('id_kontak');
            $jml_kontak = get('kontak', array('dibaca'=>0))->num_rows();
            if ($jml_kontak!=0): ?>
            <span class="badge badge-light-danger badge-pill badge-round float-right" id="jml_kontak_belum_dibaca"><?= $jml_kontak; ?></span>
          <?php endif;
        endif; ?>
      </a>
      <?php if ($jml_menu > 0): ?>
        <ul class="menu-content">
        <?php list_menu($akun_menu, $ID_MENU, $lanjut_level); ?>
        </ul>
      <?php endif; ?>
  </li><?php
}
?>
