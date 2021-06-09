<style>
  label { color: #f1f1f1 !important; }
</style>
<div class="card-header pb-1">
    <div class="card-title">
        <center>
          <img src="<?php echo web('logo'); ?>" alt="" width="117" class="img-responsive">
        </center>
        <!-- <h5 class="text-center mb-2">Selamat datang <br> di <?= web('nama_web'); ?></h5> -->
    </div>
</div>
<div class="text-center">
  <!-- <p> <small> Selamat datang di <?= web('nama_web'); ?> </small> </p> -->
  <br>
</div>
<div class="card-content <?php if(uri(3)=='M_admin' || uri(1)=='backend'){ echo "pb-3";} ?>">
    <div class="card-body <?php if (view_mobile()){ echo "p-0"; } ?>">
        <!-- <div class="d-flex flex-md-row flex-column justify-content-around">
            <a href="#" class="btn btn-social btn-google btn-block font-small-3 mr-md-1 mb-md-0 mb-1">
                <i class="bx bxl-google font-medium-3"></i>
                <span class="pl-50 d-block text-center">Google</span>
            </a>
            <a href="#" class="btn btn-social btn-block mt-0 btn-facebook font-small-3">
                <i class="bx bxl-facebook-square font-medium-3"></i>
                <span class="pl-50 d-block text-center">Facebook</span>
            </a>
        </div>
        <div class="divider">
            <div class="divider-text text-uppercase text-muted">
              <small> atau login dengan email </small>
            </div>
        </div> -->
        <?php get_pesan('msg');
        $get_level=uri(3);
        if (uri(1)=='backend') { $get_level=uri(1); }
        ?>
        <div id="pesannya"></div>
        <form id="sync_form" action="javascript:auth('login','<?= $get_level; ?>');" method="post" data-parsley-validate="true">
          <?php
          if (uri(3)=='M_admin' || uri(1)=='backend') {
            $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'Username', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus maxlength="16"');
          }else {
            $datanya[] = array('type'=>'number', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' data-parsley-minlength="10" data-parsley-maxlength="14" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event); limit(this,14);" onkeyup="limit(this,14);" autofocus');
          }
          $datanya[] = array('type'=>'password', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'lock', 'html'=>'minlength="5"');
          data_formnya($datanya);
          ?>
            <div class="form-group d-flex flex-md-row flex-column justify-content-between align-items-center">
                <div class="text-left">
                    <div class="checkbox checkbox-sm">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="checkboxsmall" for="exampleCheck1"><small>Ingatkan saya?</small></label>
                    </div>
                </div>
                <?php if (uri(3)!='M_admin' && uri(1)!='backend'): ?>
                  <?php if (!view_mobile()): ?>
                    <div class="text-right"><a href="auth/forgot-password.html" class="card-link"><small>Lupa password?</small></a></div>
                  <?php endif; ?>
                <?php endif; ?>
            </div>
            <button type="submit" name="BtnLog" class="btn btn-warning glow w-100 position-relative">Login<i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
        </form>
        <?php if (uri(3)!='M_admin' && uri(1)!='backend'): ?>
          <?php if (view_mobile()): ?>
            <div class="text-center pt-1"><a href="auth/forgot-password.html" class="card-link"><small>Lupa password?</small></a></div>
          <?php endif; ?>
            <!-- <hr>
            <div class="text-center"><small class="mr-25">Belum punya akun?</small> <a href="reseller.html"><small>Daftar Sekarang</small></a></div> -->
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
function limit(element, max_chars)
{
    if(element.value.length > max_chars) {
        element.value = element.value.substr(0, max_chars);
    }
}
</script>
