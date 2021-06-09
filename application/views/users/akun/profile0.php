<div id="pesannya"></div>
<form id="sync_form" action="javascript:simpan('sync_form','users/proses/profile','1');" method="post" data-parsley-validate="true" enctype="multipart/form-data">
    <div class="form-body">
        <div class="row">
          <?php $level = get_session('level');
          if ($level=0) {
            $datanya[] = array('type'=>'text', 'name'=>'username', 'nama'=>'Username', 'icon'=>'user', 'html'=>'required autofocus', 'col'=>'12', 'value'=>user('username'));
          }else {
            $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'icon'=>'user', 'html'=>'required autofocus', 'col'=>'12', 'value'=>user('nama_lengkap'));
            $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>'required', 'col'=>'12', 'value'=>user('email'));
            $datanya[] = array('type'=>'number', 'name'=>'username', 'nama'=>'No. HP', 'icon'=>'phone', 'html'=>'required', 'col'=>'12', 'value'=>user('no_hp'));
            if ($level==2) {
              $datanya[] = array('type'=>'textarea', 'name'=>'alamat', 'nama'=>'Alamat', 'icon'=>'map', 'html'=>'required', 'col'=>'12', 'value'=>user('alamat'));
            }
          }
          $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>'12');
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
