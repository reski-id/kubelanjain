<div id="pesannya"></div>
<form id="sync_form" action="javascript:simpan('sync_form','setup/proses/web','1');" method="post" data-parsley-validate="true" enctype="multipart/form-data">
    <div class="form-body">
        <div class="row">
          <?php
          $datanya[] = array('type'=>'text',       'name'=>'nama_perusahaan',  'nama'=>'Nama Perusahaan',       'icon'=>' bxs-buildings',     'html'=>'required autofocus', 'col'=>'12', 'value'=>web('nama_perusahaan'));
          $datanya[] = array('type'=>'text',       'name'=>'nama_web',       'nama'=>'Nama Web',       'icon'=>'globe',     'html'=>'required', 'col'=>'12', 'value'=>web('nama_web'));
          $datanya[] = array('type'=>'text',       'name'=>'title_web',      'nama'=>'Title Web',      'icon'=>' bxs-label','html'=>'required', 'col'=>'12', 'value'=>web('title_web'));
          $datanya[] = array('type'=>'email',      'name'=>'email',          'nama'=>'Email',          'icon'=>'mail-send', 'html'=>'required', 'col'=>'12', 'value'=>web('email'));
          $datanya[] = array('type'=>'text',       'name'=>'website',        'nama'=>'Website',        'icon'=>'world',     'html'=>'required', 'col'=>'12', 'value'=>web('website'));
          $datanya[] = array('type'=>'text',       'name'=>'index_redirect', 'nama'=>'Index Redirect', 'icon'=>'world',     'html'=>'required', 'col'=>'12', 'value'=>web('index_redirect'));
          $datanya[] = array('type'=>'text',       'name'=>'alamat',         'nama'=>'Alamat',         'icon'=>' bxs-map',   'html'=>'required', 'col'=>'12', 'value'=>web('alamat'));
          $datanya[] = array('type'=>'textarea',   'name'=>'map',            'nama'=>'Map',            'icon'=>' bxs-map-alt',     'html'=>'required', 'col'=>'12', 'value'=>web('map'));
          $datanya[] = array('type'=>'text',       'name'=>'no_hp',          'nama'=>'No. HP',         'icon'=>'mobile',    'html'=>'required', 'col'=>'12', 'value'=>web('no_hp'));
          $datanya[] = array('type'=>'text',       'name'=>'bot_tele',       'nama'=>'Bot Tele',       'icon'=>' bxl-telegram',  'html'=>'required', 'col'=>'12', 'value'=>web('bot_tele'));
          $datanya[] = array('type'=>'file',       'name'=>'favicon',        'nama'=>'Favicon',        'icon'=>'image',     'html'=>'',         'col'=>'12', 'value'=>web('favicon'));
          $datanya[] = array('type'=>'file',       'name'=>'logo',           'nama'=>'Logo',           'icon'=>'image',     'html'=>'',         'col'=>'12', 'value'=>web('logo'));
          $datanya[] = array('type'=>'textarea',   'name'=>'meta_deskripsi', 'nama'=>'Meta Deskripsi', 'icon'=>'label',     'html'=>'required', 'col'=>'12', 'value'=>web('meta_deskripsi'));
          $datanya[] = array('type'=>'textarea',   'name'=>'meta_keyword',   'nama'=>'Meta Keyword',   'icon'=>'label',     'html'=>'required', 'col'=>'12', 'value'=>web('meta_keyword'));
          $datanya[] = array('type'=>'text',       'name'=>'meta_author',    'nama'=>'Meta Author',    'icon'=>' bxs-user-pin',     'html'=>'required', 'col'=>'12', 'value'=>web('meta_author'));
          $datanya[] = array('type'=>'textarea',   'name'=>'footer',         'nama'=>'Footer',         'icon'=>' bxs-droplet',     'html'=>'required', 'col'=>'12', 'value'=>web('footer'));
          data_formnya($datanya);
          ?>
            <div class="col-12">
              <hr>
              <button type="reset" class="btn btn-light-secondary float-left">Reset</button>
              <?php if (check_permission('view', 'update', "setup/web")): ?>
                <button type="submit" class="btn btn-primary float-right">Simpan</button>
              <?php endif; ?>
            </div>
        </div>
    </div>
</form>
