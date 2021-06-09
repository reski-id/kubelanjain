<div class="modal fade" id="modal-login_aja" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" >
            <div class="modal-header pl-1 pr-1">
                <h5 class="modal-title" id="exampleModalScrollableTitle">LOGIN SEBAGAI <?= $sbg; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form id="sync_form" action="javascript:log_proses_reseller();" method="post" data-parsley-validate="true">
            <div class="modal-body mb-0">
              <?php
              $datanya[] = array('type'=>'password', 'name'=>'password', 'nama'=>'Password', 'placeholder'=>'Masukkan Password Akun Anda', 'validasi'=>true, 'icon'=>'lock', 'html'=>'minlength="5"');
              data_formnya($datanya);
              ?>
            </div>
            <div class="modal-footer p-0">
                <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 0px;">
                    <i class="bx bx-lock"></i>
                    <span> <b <?php if(view_mobile()){ echo ' style="font-size: 14px;"'; } ?>>LOGIN SEKARANG</b> </span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
  function login_aja()
  {
    $('#modal-login_aja').modal({'show':true, 'backdrop':'static', 'keyboard': false});
    setTimeout(function(){
      $('[name="password"]').focus();
    }, 1*1000);
  }

  function log_proses_reseller()
  {
    simpan('sync_form','users/proses/cek_login_mitra_reseller/<?= encode($id_nya); ?>','','swal','3','','1');
  }

  function run_function_check(stt='')
  {
    if (stt==1) {
      setTimeout(function(){
        window.location.reload();
      }, 2*1000);
    }else {
      setTimeout(function(){
        $('[name="password"]').focus();
      }, 1*1000);
    }
  }
</script>
