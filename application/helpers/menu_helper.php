<?php
// by Anwar Saputra
// https://anwarsptr.com

//GET ID MENU ACTIVE ===============
function id_menu_active($akun_menu=0, $get_url='')
{ $CI = &get_instance();
  $CI->db->select('master_menu');
  $CI->db->where('url', $get_url);
  $get = get('menu', array('akun_menu'=>$akun_menu))->row();
  if (empty($get)) { return ''; }else {
    return $get->master_menu;
  }
}
//GET ID MENU ACTIVE ===============

//GET===============
function get_menu($akun_menu=0, $master_menu=0, $level_menu=0, $status='')
{ $CI = &get_instance();
  $tbl_menu='menu'; $tanda_plus='';
  $CI->db->where('akun_menu', $akun_menu);
  $CI->db->where('master_menu', $master_menu);
  $CI->db->where('level_menu', $level_menu);
  if ($status!='') {
    $CI->db->where('status', $status);
  }
  $CI->db->order_by('urutan', 'ASC');
  $get = get($tbl_menu);
  if ($get->num_rows() > 0) {
    $tanda_plus = "menu-toggle waves-effect waves-block";
  }
  return array('result'=>$get->result(), 'jml'=>$get->num_rows(), 'plus'=>$tanda_plus);
}

function get_chil($element, $master_menu=0)
{
  $data_id = '';
  if (!empty($element['children'])) { //1
    foreach ($element['children'] as $key2 => $element2) {
        $data_id .= $master_menu." > ".$element2['id'].", ";
        if (!empty($element2['children'])) { //2
          $data_id .= get_chil($element2, $element2['id']);
        }
    }
    return $data_id;
  }
}

function get_URLnya($URL='')
{
  if (in_array($URL, array('javascript:void(0);','javascript:call_me();','#',''))) {
    return $URL;
    // return 'web/coming-soon?url=dashboard';
  }else {
    return "$URL.html";
  }
}

function get_ICON_MENU($master_menu='', $ICON='',$stt=0)
{
  $ICON_DEFAULT_1 = '<i class="bx bx-label"></i>';
  $ICON_DEFAULT_2 = '<i class="bx bx-right-arrow-alt"></i>';
  if ($master_menu==0 && $ICON==''){
    $ICON = $ICON_DEFAULT_1;
  }else{
    if ($ICON=='') { $ICON = $ICON_DEFAULT_2; }
  }
  if (!in_array($ICON,array($ICON_DEFAULT_1, $ICON_DEFAULT_2))) {
    if ($stt==1) {
      $ICON = '<i class="menu-livicon" data-icon="'.$ICON.'"></i>';
    }else {
      if ($ICON=='#') {
        $ICON = '';
      }else {
        $ICON = '<i class="bx bx-bookmark"></i>';
      }
    }
  }
  return $ICON;
}
//GET===============

// SETUP MENU ======================
function set_list_menu($akun_menu=0, $master_menu=0, $level_menu=0)
{ $CI = &get_instance();
  foreach (get_menu($akun_menu, $master_menu, $level_menu)['result'] as $key => $value):
    $ID_MENU = $value->id_menu;
    $NAMA    = $value->nama;
    $ICON    = $value->icon;
    $STATUS  = $value->status;
    // ==== SUB MENU ==== //
    $lanjut_level = $level_menu+1;
    $sub_menu = get_menu($akun_menu, $ID_MENU, $lanjut_level);
    $jml_menu = $sub_menu['jml'];
    // ==== SUB MENU ==== //
    $data = array(
      'ID_MENU'     => $ID_MENU,
      'master_menu' => $master_menu,
      'ICON'        => $ICON,
      'NAMA'        => $NAMA,
      'level_menu'  => $level_menu,
      'lanjut_level'=> $lanjut_level,
      'jml_menu'    => $jml_menu,
      'akun_menu'   => $akun_menu,
      'BATAS'       => $value->batas,
      'STATUS'      => $STATUS,
    );
    $CI->load->view('setup/config/set_list_menu', $data);
  endforeach;
}
// SETUP MENU ======================

function list_menu($akun_menu=0, $master_menu=0, $level_menu=0)
{ $CI = &get_instance();
  foreach (get_menu($akun_menu, $master_menu, $level_menu, 1)['result'] as $key => $value):
    $ID_MENU = $value->id_menu;
    $NAMA = $value->nama;
    $ICON = $value->icon;
    $URL  = $value->url;
    $STATUS  = $value->status;
    $URLnya = get_URLnya($URL);
    // ==== SUB MENU ==== //
    $lanjut_level = $level_menu+1;
    $sub_menu = get_menu($akun_menu, $ID_MENU, $lanjut_level, 1);
    $jml_menu = $sub_menu['jml'];
    $tanda_plus = $sub_menu['plus'];
    // ==== SUB MENU ==== //
    $data = array(
      'ID_MENU'     => $ID_MENU,
      'master_menu' => $master_menu,
      'ICON'        => $ICON,
      'NAMA'        => $NAMA,
      'level_menu'  => $level_menu,
      'lanjut_level'=> $lanjut_level,
      'jml_menu'    => $jml_menu,
      'akun_menu'   => $akun_menu,
      'tanda_plus'  => $tanda_plus,
      'URL'         => $URL,
      'URLnya'      => $URLnya,
      'BATAS'       => $value->batas,
      'STATUS'      => $STATUS,
    );
    $CI->load->view('setup/config/list_menu', $data);
  endforeach;
}


// === FORM MENU ====
function form_menu($akun_menu=0, $master_menu=0, $level_menu=0)
{ $get_url = uri('x');
  foreach (get_menu($akun_menu, $master_menu,$level_menu)['result'] as $key => $value):
    $ID_MENU = $value->id_menu;
    $NAMA = $value->nama;
    $ICON = $value->icon;
    $URL  = $value->url;
    $URLnya = get_URLnya($URL);
    // ==== SUB MENU ==== //
    $lanjut_level = $level_menu+1;
    $sub_menu = get_menu($akun_menu, $ID_MENU, $lanjut_level);
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
                  <?php form_menu($akun_menu, $ID_MENU, $lanjut_level); ?>
              </div>
          </div>
      </div>
    </div><?php
    // }
  }
  endforeach;
}

function list_function($menu='')
{ $CI = &get_instance();
  if ($menu!='') {
    $menu = strtolower(preg_replace('/[ ]/', '_', $menu));
    $CI->db->like('nama', $menu."_" ,'after');
    return get('set_function');
    // log_r($CI->db->last_query());
  }
}
// === FORM MENU ===



// MENU untuk USER MANAGEMENT
function opsi_akses_menu($i=0)
{
  $data = array('Create', 'Read', 'Update', 'Delete', 'Print', 'Approve');
  if ($i==='jml') {
    return count($data) - 1;
  }else {
    return $data[$i];
  }
}

function opsi_akses_menu_warna($i=0)
{
  $data = array('primary', 'info', 'success', 'danger', 'secondary', 'warning');
  return $data[$i];
}

function get_menu_list($akun_menu=0, $master_menu=0, $level_menu=0, $id_user='')
{ $CI = &get_instance();
  foreach (get_menu($akun_menu, $master_menu, $level_menu, 1)['result'] as $key => $value):
    $ID_MENU = $value->id_menu;
    $NAMA = $value->nama;
    $ICON = $value->icon;
    $URL  = $value->url;
    $STATUS  = $value->status;
    $URLnya = get_URLnya($URL);
    // ==== SUB MENU ==== //
    $lanjut_level = $level_menu+1;
    $sub_menu = get_menu($akun_menu, $ID_MENU, $lanjut_level, 1);
    $jml_menu = $sub_menu['jml'];
    $tanda_plus = $sub_menu['plus'];
    // ==== SUB MENU ==== //
    $data = array(
      'ID_MENU'     => $ID_MENU,
      'master_menu' => $master_menu,
      'ICON'        => $ICON,
      'NAMA'        => $NAMA,
      'level_menu'  => $level_menu,
      'lanjut_level'=> $lanjut_level,
      'jml_menu'    => $jml_menu,
      'akun_menu'   => $akun_menu,
      'tanda_plus'  => $tanda_plus,
      'URL'         => $URL,
      'URLnya'      => $URLnya,
      'BATAS'       => $value->batas,
      'STATUS'      => $STATUS,
      'id_user'     => $id_user
    );
    $CI->load->view('setup/view/user_management/menu/list', $data);
  endforeach;

}

// PERMISSION MENU
function check_usernya()
{ $CI = get_instance();
  $CI->db->select('id_user');
  $dt = get('user_biodata_management', array( 'id_user'=>get_session('id_user') ))->row();
  if (empty($dt)) {
    return true;
  }
}

function check_permission($aksi='view', $crud='', $url='', $id_menu='', $status = false)
{ $CI = get_instance();
    if (check_usernya()) { return true; }
    $id_user = get_session('id_user');
    $CI->db->select('b.id_menu,b.id_user,b.permission,a.url');
    $CI->db->join('menu_permission b', 'a.id_menu=b.id_menu');
    if ($url=='') {
      $CI->db->where('a.id_menu', $id_menu);
    }else {
      $CI->db->where('a.url', $url);
    }
    $dt = get('menu a', array( 'b.id_user'=>$id_user, 'a.akun_menu'=>0))->row();

    if (!empty($dt->permission)) {
      if (in_array($crud, unserialize($dt->permission))) {
          $status = true;
      }
    }
    if (strtolower($aksi)=='page') {
      if (!$status) { redirect('404'); }
    }
    return $status;
}

function check_permission_menu($id_menu='', $id_user='', $crud='')
{ $CI = get_instance();
  if($id_user==''){ $id_user=get_session('id_user'); }
  $CI->db->select('a.permission');
  $dt = get('menu_permission a', array( 'a.id_user'=>$id_user, 'a.id_menu'=>$id_menu ))->row();
  if (!empty($dt->permission)) {
    if (in_array($crud, unserialize($dt->permission))) {
      return true;
    }
  }
  return false;
}
