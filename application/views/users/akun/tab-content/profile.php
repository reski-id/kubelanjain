<?php $level = get_session('level'); ?>
<!-- user profile nav tabs profile start -->
<div class="card">
    <div class="card-content">
        <div class="card-body">
            <h5 class="card-title">
              Detail
            <?php if ($level!=0):
              if ($level==1) {
                $akses = 'user_mitra';
              }else {
                $akses = 'user_reseller';
              }?>
              <a href="javascript:aksi('edit','<?= encode(get_session('id_user')); ?>','sync_form','<?= base_url().$akses; ?>/view_data')" class="btn btn-sm float-right btn-light-primary mb-2">
                <i class="cursor-pointer bx bx-edit font-small-3 mr-50"></i><span>Edit</span>
              </a>
            <?php endif; ?>
            </h5>
            <ul class="list-unstyled">
                <!-- <li><i data-toggle="tooltip" data-placement="top" title="" data-original-title="Username" class="cursor-pointer bx bx-user mb-1 mr-50"></i><?= user('username'); ?></li> -->
                <li><i data-toggle="tooltip" data-placement="top" title="" data-original-title="Alamat" class="cursor-pointer bx bx-map mb-1 mr-50"></i><?= user('alamat'); ?></li>
                <li><i data-toggle="tooltip" data-placement="top" title="" data-original-title="No. Handphone" class="cursor-pointer bx bx-phone-call mb-1 mr-50"></i><?= user('no_hp'); ?> </li>
                <?php if ($level==2): ?>
                  <?php if (user('no_hp2')!=''): ?>
                    <li><i data-toggle="tooltip" data-placement="top" title="" data-original-title="No. Handphone 2" class="cursor-pointer bx bx-phone-call mb-1 mr-50"></i><?= user('no_hp2'); ?> </li>
                  <?php endif; ?>
                <?php endif; ?>
                <li><i data-toggle="tooltip" data-placement="top" title="" data-original-title="Email" class="cursor-pointer bx bx-envelope mb-1 mr-50"></i><?= user('email'); ?></li>
            </ul>
            <div class="row">
                <!-- <div class="col-12">
                    <h6><small class="text-muted">Hobi</small></h6>
                    <p><?= user('hobi'); ?></p>
                </div> -->
                <div class="col-md-12">
                <?php
                  $id = get_session('id_user');
            			if ($level==1) { //mitra
                    $data['query'] = get_field("v_user",array('id_user'=>"$id"));
              			$data['data_bank'] = select_datanya('bank', '');
              			$data['dt_bank'] 	 = get_field('user_bank', array('id_user'=>$id));
              			$data['tbl'] 		= 'v_user';
              			$data['stt']		= 1;
                    view('users/user_mitra/detail', $data);
                  }elseif ($level==2) { //reseller
                    $data['query'] = get_field("v_user",array('id_user'=>"$id"));
              			$data['tbl'] 		= 'v_user';
              			$data['stt']		= 1;
                    view('users/user_reseller/detail', $data);
                  }
                ?>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- user profile nav tabs profile ends -->

<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md" id="lebar_modalnya">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal_judul"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
      </div>
      <div id="modal_datanya">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
