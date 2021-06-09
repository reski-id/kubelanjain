<div class="alert alert-success alert-dismissible mb-2 col-md-12" id="show_first_jk" role="alert" hidden>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="d-flex align-items-center">
        <i class="bx bx-check-double"></i>
        <span>
            Selamat bergabung bersama MEEJU INDONESIA
        </span>
    </div>
</div>


<?php
$tbl = 'user_biodata_reseller';
$this->db->select('id_mitra, type_id, alamat, id_referal');
$getV = get_field($tbl, array('id_user'=>get_session('id_user')));
$type_id  = $getV['type_id'];
$id_mitra = $getV['id_referal'];
$id_reseller = $getV['id_mitra'];
// if ($type_id==3) {
  $this->db->select('id_mitra');
  $id_mitra = get_field('user_biodata_mitra', array('no_hp'=>get_session('username')))['id_mitra'];
// }
?>

<div class="row">
  <div class="col-12 col-md-6 timline-card">
    <div class="card ">
        <div class="card-header">
          <h4 class="card-title col-12 col-md-10 pt-1 pl-0">
              <b>ID RESELLER : <label class="badge badge-secondary"><?= $id_reseller; ?></label></b>
          </h4>
        </div>
    </div>
  </div>

  <div class="col-12 col-md-6 timline-card">
    <?php if (!in_array($id_mitra, array('', null))){
      $this->db->select('id_user');
      // $get_M = get_field('user_biodata_mitra', array('id_mitra'=>$id_mitra, 'no_hp'=>get_session('username')));
      $get_M = get_field('user_biodata_mitra', array('no_hp'=>get_session('username')));
      $id_nya['id_nya'] = $get_M['id_user'];
      $id_nya['sbg']    = 'MITRA';
      if (!in_array($get_M['id_user'], array('', null))) {
      ?>
      <div class="card">
          <div class="card-header">
            <h4 class="card-title col-12 col-md-10 pt-1 pl-0">
                <b>ID MITRA : <label class="badge badge-secondary"><?= $id_mitra; ?></label></b>
            </h4>
          </div>
          <div class="card-content">
              <div class="card-body">
                <button class="btn btn-warning glow btn-block" onclick="login_aja();">LOGIN SEBAGAI MITRA</button>
              </div>
          </div>
      </div>
      <?php view('users/dashboard/login', $id_nya); ?>
    <?php }
    }?>
  </div>

</div>
