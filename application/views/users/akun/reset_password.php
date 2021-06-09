<div id="pesannya"></div>
<form id="sync_form" action="javascript:simpan('sync_form','users/proses/reset_password');" method="post" data-parsley-validate="true">
    <div class="form-body">
        <div class="row">
          <?php
          $datanya[] = array('type'=>'password', 'name'=>'password1', 'nama'=>'Password Lama', 'icon'=>'lock', 'html'=>'required data-parsley-minlength="5" autofocus', 'col'=>'12');
          $datanya[] = array('type'=>'password', 'name'=>'password2', 'nama'=>'Password Baru', 'icon'=>'key', 'html'=>'required minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'col'=>'12');
          $datanya[] = array('type'=>'password', 'name'=>'password3', 'nama'=>'Konfirmasi Password Baru', 'icon'=>'key', 'html'=>'required minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'col'=>'12');
          data_formnya($datanya);
          ?>
            <div class="col-12">
              <hr>
              <button type="reset" class="btn btn-light-secondary float-left">Reset</button>
              <button type="submit" class="btn btn-primary float-right">Simpan</button>
            </div>
        </div>
    </div>
</form>
