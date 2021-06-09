<div class="card-header pb-3 pt-5">
    <div class="card-title">
        <h4 class="text-center mb-1">Lupa Password?</h4>
    </div>
</div>
<div class="card-content pb-5">
    <div class="card-body pb-3">
        <div class="text-muted text-center mb-1"><small>Masukkan Nomor Handphone Anda untuk mereset password</small></div>
        <div id="pesannya"></div>
        <form id="sync_form" class="mb-2" action="javascript:void(0);" method="post" data-parsley-validate="true">
          <?php
          $datanya[] = array('type'=>'number', 'name'=>'no_hp', 'nama'=>'', 'placeholder'=>'08xxxx', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' data-parsley-minlength="10" data-parsley-maxlength="14" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event);" autofocus');
          data_formnya($datanya);
          ?>
          <!-- <button type="submit" name="btn_forgot_password" class="btn btn-primary glow position-relative w-100">MINTA PASSWORD BARU<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button> -->
          <center>
            <label class="pb-1">Kirim Reset Password via</label>
          </center>
          <div class="row">
            <div class="col-md-6">
              <button type="submit" name="BtnReg" onclick="javascript:send_Pass(1);" class="btn btn-success glow position-relative w-100 mb-1"><i class="bx bxl-whatsapp"></i> Whatsapp</button>
            </div>
            <div class="col-md-6">
              <button type="submit" name="BtnReg" onclick="javascript:send_Pass(2);" class="btn btn-warning glow position-relative w-100 mb-1"><i class="bx bxs-envelope"></i> SMS</button>
            </div>
            <div class="col-md-12">
              <button type="submit" name="BtnReg" onclick="javascript:send_Pass(3);" class="btn btn-primary glow position-relative w-100"><i class="bx bxl-telegram"></i> ADMIN</button>
            </div>
          </div>
        </form>
        <hr>
        <div class="text-center mb-2"><a href="auth/login.html"><small class="text-muted">Saya ingat password saya,</small> <small>Login</small> </a></div>
    </div>
</div>

<script type="text/javascript">
function send_Pass(stt=''){
  if ($('[name="no_hp"]').val()=='') {
    return false;
  }
  auth('forgot_password/'+stt,'<?= uri(3); ?>','auth/login','120');
}
</script>
