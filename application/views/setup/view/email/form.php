<div id="pesannya"></div>
<form id="sync_form" action="javascript:simpan('sync_form','setup/proses/set_email/simpan/<?php echo encode(1); ?>','','swal','3','1');" method="post" data-parsley-validate="true" enctype="multipart/form-data">
    <div class="form-body">
        <div class="row">
          <?php
          $datanya[] = array('type'=>'text',     'name'=>'web_default',    'nama'=>'Web Default',    'icon'=>'world',     'html'=>'required autofocus', 'col'=>'12', 'value'=>email('web_default'));
          $datanya[] = array('type'=>'text',     'name'=>'host',           'nama'=>'Host',           'icon'=>'desktop',   'html'=>'required', 'col'=>'12', 'value'=>email('host'));
          $datanya[] = array('type'=>'text',     'name'=>'port',           'nama'=>'Port',           'icon'=>'',          'html'=>'required', 'col'=>'6',  'value'=>email('port'));
          $datanya[] = array('type'=>'text',     'name'=>'smtpsecure',     'nama'=>'SMPTSecure',     'icon'=>'',          'html'=>'',         'col'=>'6',  'value'=>email('smtpsecure'));
          $datanya[] = array('type'=>'text',     'name'=>'username',       'nama'=>'Username',       'icon'=>'user',      'html'=>'required', 'col'=>'12', 'value'=>email('username'));
          $datanya[] = array('type'=>'password', 'name'=>'password',       'nama'=>'Password',       'icon'=>'key',       'html'=>'required', 'col'=>'12', 'value'=>email('password'));
          $datanya[] = array('type'=>'email',    'name'=>'email_pengirim', 'nama'=>'Email Pengirim', 'icon'=>'mail-send', 'html'=>'required', 'col'=>'12', 'value'=>email('email_pengirim'));
          $datanya[] = array('type'=>'text',     'name'=>'nama_pengirim',  'nama'=>'Nama Pengirim',  'icon'=>'user',      'html'=>'required', 'col'=>'12', 'value'=>email('nama_pengirim'));
          $datanya[] = array('type'=>'email',    'name'=>'email_penerima', 'nama'=>'Email Penerima (Test)', 'icon'=>'mail-send', 'html'=>'required', 'col'=>'12', 'value'=>email('email_penerima'));
          $datanya[] = array('type'=>'text',     'name'=>'nama_penerima',  'nama'=>'Nama Penerima (Test)',  'icon'=>'user',      'html'=>'required', 'col'=>'12', 'value'=>email('nama_penerima'));
          data_formnya($datanya);
          ?>
            <div class="col-12">
              <hr>
              <button type="reset" class="btn btn-secondary round glow float-left">Reset</button>
              <?php if (check_permission('view', 'create', "setup/email")): ?>
                <a href="web/test-email.html" class="btn btn-warning round glow float-left ml-1" target="_blank">Test Kirim</a>
              <?php endif; ?>
              <?php if (check_permission('view', 'update', "setup/email")): ?>
                <button type="submit" class="btn btn-primary round glow float-right">Simpan</button>
              <?php endif; ?>
            </div>
        </div>
    </div>
</form>
