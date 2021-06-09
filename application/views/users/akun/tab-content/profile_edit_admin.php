<!-- vertical Wizard start-->
  <div class="card">
      <div class="card-header">
          <h4 class="card-title"> Edit Profil </h4>
          <hr>
      </div>
      <div class="card-content">
          <div class="card-body">
            <div id="pesannya"></div>
            <form id="sync_form" action="javascript:simpan('sync_form','users/proses/profile','1');" method="post" data-parsley-validate="true" enctype="multipart/form-data">
                <div class="form-body">
                    <div class="row">
                      <div class="col-md-3"></div>
                      <div class="col-12 col-md-6">
                        <?php
                        if (get_session('level')==0) {
                          $datanya[] = array('type'=>'text', 'name'=>'username', 'nama'=>'Username', 'icon'=>'user', 'html'=>'required autofocus', 'col'=>'12', 'value'=>user('username'));
                        }else {
                          $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'icon'=>'user', 'html'=>'required autofocus', 'col'=>'12', 'value'=>user('nama_lengkap'));
                          $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>'required', 'col'=>'12', 'value'=>user('email'));
                          $datanya[] = array('type'=>'number', 'name'=>'no_hp', 'nama'=>'No. HP', 'icon'=>'phone', 'html'=>'required', 'col'=>'12', 'value'=>user('no_hp'));
                        }
                        $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>'12');
                        data_formnya($datanya);
                        ?>
                          <div class="col-12">
                            <hr>
                            <button type="reset" class="btn btn-light-secondary glow float-left">Reset</button>
                            <button type="submit" class="btn btn-primary glow float-right">Simpan</button>
                          </div>
                      </div>
                    </div>
                </div>
            </form>

          </div>
      </div>
</div>
