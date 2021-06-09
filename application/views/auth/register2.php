<div class="card-header pb-0 pt-0">
    <div class="card-title">
        <center>
          <img src="<?php echo web('logo'); ?>" alt="" width="200" class="img-responsive">
        </center>
        <!-- <h5 class="text-center mb-2">Form Pendaftaran</h5> -->
    </div>
</div>
<div class="text-center">
    <p> <h4> <b>Register Partnership</b> </h4> </p>
</div>
<div class="card-content pb-0 pt-0">
    <div class="card-body pb-0 pt-0">
        <?php get_pesan('msg'); ?>
        <div id="pesannya"></div>
        <form id="sync_form" action="javascript:auth('register');" method="post" data-parsley-validate="true">
          <?php
          $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'placeholder'=>'Nama sesuai KTP', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup"');
          $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Alamat Email', 'validasi'=>true, 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"');
          $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'No HP / WA', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' min="10" step="100" minlength="10" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"');
          $datanya[] = array('type'=>'password', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"');
          $datanya[] = array('type'=>'password', 'name'=>'password2', 'nama'=>'Konfirmasi Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup" onkeyup="validatePassword(\'password\',\'password2\')"');
          data_formnya($datanya);
          ?>
          <button type="submit" name="BtnReg" class="btn btn-primary glow position-relative w-100">DAFTAR<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
        </form>
        <hr>
        <div class="text-center"><small class="mr-25">Sudah punya akun?</small> <a href="auth/login.html"><small>Login</small> </a></div>
    </div>
</div>
