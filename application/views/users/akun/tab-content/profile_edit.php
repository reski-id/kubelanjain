<!-- vertical Wizard start-->
<section id="vertical-wizard">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">
              <a href="users/profile.html"><i class="bx bx-left-arrow-circle"></i></a>
              Edit Profil
            </h4>
            <hr>
        </div>
        <div class="card-content p-0">
            <div class="card-body pl-1 pr-1">
              <div id="pesannya"></div>
              <?php $id = get_session('id_user');
        			$data['query'] = get_field("v_user",array('id_user'=>"$id"));
        			$data['data_bank'] = select_datanya('bank', '');
        			$data['dt_bank'] 	 = get_field('user_bank', array('id_user'=>$id));
        			$data['tbl'] 		= 'user';
        			$data['stt']		= 1;
        			$data['urlnya'] = base_url("user_market/simpan");
              view('users/user_market/form', $data);
              ?>
            </div>
        </div>
    </div>
</section>
<!-- vertical Wizard end-->
