<?php
$key = decode(uri(3), '64');
$exp = explode(' | ', $key);
$id_user = $exp[0];
$waktu   = $exp[1];

if (get('user', array('id_user'=>$id_user))->num_rows()==0) { //jika usernya tidak ditemukan
  redirect('404-1');
}
if (strlen($waktu)!=19) { //jika jumlah tgl tidak sampai atau lebih dari 19
  redirect('404-2');
}
if (validateDate($waktu) == '') { // cek format waktu
  redirect('404-3');
}
?>
<div class="card-header pb-1 pt-5">
    <div class="card-title">
        <h4 class="text-center mb-2">Reset Password</h4>
    </div>
</div>
<div class="card-content pb-1">
    <div class="card-body">
      <?php if (tgl_now() >= $waktu){ ?>
          <?php redirect('404'); ?>
      <?php }else{ ?>
        <div id="pesannya"></div>
        <form id="sync_form" action="javascript:auth('reset_password','<?php echo $id_user; ?>','auth/login');" method="post" data-parsley-validate="true" class="mb-2">
          <?php
          $datanya[] = array('type'=>'password', 'name'=>'password1', 'nama'=>'Password Baru', 'validasi'=>true, 'html'=>'autofocus minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'mb'=>'mb-2');
          $datanya[] = array('type'=>'password', 'name'=>'password2', 'nama'=>'Konfirmasi Password Baru', 'validasi'=>true, 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup" onkeyup="validatePassword(\'password1\',\'password2\')"', 'mb'=>'mb-2');
          data_formnya($datanya);
          ?>
          <button type="submit" name="btn_reset_password" class="btn btn-primary glow position-relative w-100">Reset Password Saya<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
        </form>
      <?php } ?>
    </div>
</div>
