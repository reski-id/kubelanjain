<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="<?php echo web('meta_deskripsi'); ?>">
    <meta name="keywords" content="<?php echo web('meta_keyword'); ?>">
    <meta name="author" content="<?php echo web('meta_author'); ?>">
    <title><?php echo $judul_web; ?></title>
    <base href="<?php echo base_url(); ?>">
    <noscript><meta http-equiv="refresh" content="0;url=web/noscript.html"></noscript>
    <link rel="icon" href="<?php echo web('favicon'); ?>">
    <link href="assets/fonts/google/css.css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/extensions/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/extensions/sweetalert2.min.css">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="assets/css/colors.css">
    <link rel="stylesheet" type="text/css" href="assets/css/components.css">
    <link rel="stylesheet" type="text/css" href="assets/css/themes/dark-layout.css">
    <link rel="stylesheet" type="text/css" href="assets/css/themes/semi-dark-layout.css">
    <!-- END: Theme CSS-->

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- Bootstrap Select Css -->
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <?php if (uri(1)=='setup' && uri(2)=='menu'): ?>
      <link href="assets/plugin/nestable/jquery-nestable.css" rel="stylesheet" />
    <?php endif; ?>
    <?php if (uri(1)=='users' && uri(2)=='profile'): ?>
    <link rel="stylesheet" type="text/css" href="assets/css/pages/page-user-profile.css">
    <?php endif; ?>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
    <link href="assets/plugin/swal/sweetalert.css" rel="stylesheet">
    <script src="assets/plugin/swal/sweetalert.min.js"></script>
    <?php if (in_array(uri(1), array('master','update_harga'))): ?>
      <link rel="stylesheet" href="assets/plugin/lightbox/lightbox.min.css">
      <script src="assets/plugin/lightbox/lightbox-plus-jquery.min.js"></script>
    <?php endif; ?>
    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/plugin/custom/style.js"></script>
    <?php view('plugin/style/global'); ?>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern <?= mode(); ?> 2-columns  navbar-sticky footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="2-columns">
    <?php view('loading'); ?>
    <!-- BEGIN: Header-->
    <div class="header-navbar-shadow"></div>
    <?php view('users/main/header'); ?>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <?php
      view('users/menu/menu_'.lv());
    ?>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
      <div class="app-content content">
          <div class="content-overlay"></div>
          <div class="content-wrapper">
              <div class="content-header row"></div>
              <div class="content-body">
                <?php view($content); ?>
              </div>
          </div>
      </div>
    <!-- END: Content-->

    <!-- demo chat-->
    <?php //view('users/main/chat'); ?>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <?php view('users/main/footer'); ?>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <?php if (uri(1)=='setup' && uri(2)=='menu'): ?>
      <script src="assets/plugin/nestable/jquery.nestable.js"></script>
      <script src="assets/plugin/nestable/sortable-nestable.js"></script>
    <?php endif; ?>
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="assets/vendors/js/extensions/swiper.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="assets/js/scripts/configs/vertical-menu-light.js"></script>
    <script src="assets/js/core/app-menu.js"></script>
    <script src="assets/js/core/app.js"></script>
    <script src="assets/js/scripts/components.js"></script>
    <script src="assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <!-- <script src="assets/js/scripts/pages/dashboard-ecommerce.js"></script> -->
    <!-- Select Plugin Js -->
    <?php view('plugin/select2/custom'); ?>
    <?php view('plugin/parsley/custom'); ?>
    <!-- END: Page JS-->

    <script src="assets/plugin/custom/crud.js"></script>

    <?php
    // if (get_session('level')==1 && user('jenis_akun')==0) {
    //   if (uri(1)!='users' && uri(2)!='usaha') {
    //     if (get_banner('informasi','status')==1) {
    //       view('users/informasi/modal/dashboard');
    //     }
    //   }
    // }

    if (get_session('level')!=0) {
      if (in_array(get_field('user_biodata', array('id_user'=>get_session('id_user')))['jenis_kelamin'], array('',null))) {
        view('users/modal/biodata_wajib');
      }
    }

    if (in_array(get_session('level') , array(1,2))) {
      view('users/call_me');
    }
    ?>

    <?php if (get_session('level')==0 && get_session('id_kota')!=''): ?>
      <script type="text/javascript">
        if($('#left_tab').length!=0){ $('#left_tab').attr('class','col-12 col-md-12'); }
        if($('#right_tab').length!=0){ $('#right_tab').hide(); }
      </script>
    <?php endif; ?>

    <?php if (get_session('level')==0 AND uri(1)=='dashboard'): ?>
      <?php view('users/dashboard/admin/js'); ?>
    <?php endif; ?>
</body>
<!-- END: Body-->
</html>
