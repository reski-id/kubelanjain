<?php $level = get_session('level'); ?>
<style>
@media only screen and (max-width: 767px){
  .page-user-profile .user-profile-text {
    left: 0 !important;
    right: 0 !important;
    margin-left: 0px !important;
  }
}
</style>
<!-- page user profile start -->
<section class="page-user-profile">
    <div class="row">
        <div class="col-12">
            <!-- user profile heading section start -->
            <div class="card">
                <div class="card-content">
                    <div class="user-profile-images">
                        <!-- user timeline image -->
                        <img src="img/user/profile-banner.jpg" class="img-fluid rounded-top user-timeline-image" alt="user timeline image" style="width: 1980px;">
                        <!-- user profile image -->
                        <a href="javascript:aksi('edit','<?= encode(get_session('id_user')); ?>','sync_form','<?= base_url(); ?>users/profile_upload')">
                          <img src="<?= cek_foto(user('foto'),'user-null.jpg'); ?>" class="user-profile-image rounded" alt="user profile image" height="140" width="140">
                        </a>
                    </div>
                    <div class="user-profile-text">
                        <h4 class="mb-0 text-bold-500 profile-text-color"><?php if($level==0){ echo user('username'); }else{ echo user('nama_lengkap'); } ?></h4>
                        <small><?= jenis_akun(); ?></small>
                    </div>
                    <!-- user profile nav tabs start -->
                    <div class="card-body px-0">
                        <ul class="nav user-profile-nav justify-content-center justify-content-md-start nav-tabs border-bottom-0 mb-0" role="tablist">
                            <li class="nav-item pb-0">
                              <a class="nav-link d-flex px-1 active" id="profile-tab" data-toggle="tab" href="#profile" aria-controls="profile" role="tab" aria-selected="false"><i class="bx bx-copy-alt"></i><span class="d-none d-md-block">Profil</span></a>
                            </li>
                          <?php if ($level!=0): ?>
                            <!-- <li class="nav-item pb-0">
                              <a class="nav-link d-flex px-1" id="friends-tab" data-toggle="tab" href="#friends" aria-controls="friends" role="tab" aria-selected="false"><i class="bx bx-message-alt"></i><span class="d-none d-md-block">Teman</span></a>
                            </li> -->
                          <?php endif; ?>
                            <li class="nav-item pb-0 mr-0">
                              <a class="nav-link d-flex px-1 <?php if(uri(3)=='reset_password'){ echo "active"; } ?>" id="reset_password-tab" data-toggle="tab" href="#reset_password" aria-controls="reset_password" role="tab" aria-selected="false"><i class="bx bx-lock-alt"></i><span class="d-none d-md-block">Reset Password</span></a>
                            </li>
                        </ul>
                    </div>
                    <!-- user profile nav tabs ends -->
                </div>
            </div>
            <!-- user profile heading section ends -->


            <!-- user profile content section start -->
            <div class="row">
                <!-- user profile nav tabs content start -->
                <div class="col-lg-12">
                    <div class="tab-content">
                        <?php
                        if ($level==0) {
                          $data_tab = array('profile','reset_password');
                        }else {
                          $data_tab = array('profile','friends','reset_password');
                        }
                        ?>
                        <?php foreach ($data_tab as $key => $value): ?>
                          <div class="tab-pane <?php if($key==0 && uri(3)!='reset_password'){ echo "active"; } ?> <?php if(uri(3)==$value){ echo "active"; } ?>" id="<?php echo $value; ?>" aria-labelledby="<?php echo $value; ?>-tab" role="tabpanel">
                            <?php
                            if (uri(3)=='edit' && $value=='profile') {
                              if ($level==0) {
                                $edit = '_edit_admin';
                              }else {
                                $edit = '_edit';
                              }
                            }else {
                              $edit = '';
                            }
                            view("users/akun/tab-content/$value$edit");
                            ?>
                          </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- user profile nav tabs content ends -->
            </div>
            <!-- user profile content section start -->
        </div>
    </div>
</section>
<!-- page user profile ends -->
