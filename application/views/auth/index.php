<?php cek_redirect(); ?>
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
    <title><?php echo $judul_web. " ".web('title_web'); ?></title>
    <base href="<?php echo base_url(); ?>">
    <link rel="icon" href="<?php echo web('favicon'); ?>">
    <link href="assets/fonts/google/css.css?family=Rubik:300,400,500,600%7CIBM+Plex+Sans:300,400,500,600,700" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
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
    <link rel="stylesheet" type="text/css" href="assets/css/pages/authentication.css">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="assets/parsley/parsley.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
    <!-- <style>
    input:focus {
      border: 1px solid orange !important;
    }
    .select2-container--classic.select2-container--open .select2-selection--single, .select2-container--default.select2-container--open .select2-selection--single {
      border: 1px solid orange !important;
    }
    </style> -->
    <script src="assets/vendors/js/vendors.min.js"></script>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern 1-column  navbar-sticky footer-static blank-page blank-page" data-open="click" data-menu="vertical-menu-modern" data-col="1-column" style="background: #999;">
    <?php view('loading'); ?>
    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <!-- login page start -->
                <section id="auth-login" class="row flexbox-container">
                    <div class="col-xl-8 col-11">
                        <div class="card bg-authentication mb-0" style="background: #6a90c9;">
                            <div class="row m-0">
                                <!-- left section-login -->
                                <div class="col-md-6 col-12 px-0">
                                    <div class="card disable-rounded-right mb-0 p-<?php if(view_mobile()){ if(uri(1)=='login' or uri(2)=='login'){ echo "2"; }else{ echo "0"; } }else{ echo "2"; } ?> h-100 d-flex justify-content-center" style="background:#6a90c9">
                                        <?php view($content); ?>
                                    </div>
                                </div>
                                <!-- right section image -->
                                <div class="col-md-6 d-md-block d-none text-center align-self-center p-0">
                                    <div class="card-content">
                                      <a href="">
                                        <?php
                                        // if(in_array(uri(1), array('mitra','reseller'))){
                                          $get_img = 'img/auth/pasar.png';
                                        // }else {
                                        //   $get_img = "assets/images/pages/".uri(2).".png";
                                        // } ?>
                                        <img class="img-fluid" src="<?php echo preg_replace('/[_]/','-',$get_img); ?>" alt="branding logo">
                                      </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- login page ends -->

            </div>
        </div>
    </div>
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.tools.js"></script>
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.defaults.js"></script>
    <script src="assets/fonts/LivIconsEvo/js/LivIconsEvo.min.js"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="assets/js/scripts/configs/vertical-menu-light.js"></script>
    <script src="assets/js/core/app-menu.js"></script>
    <script src="assets/js/core/app.js"></script>
    <script src="assets/js/scripts/components.js"></script>
    <script src="assets/js/scripts/footer.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="assets/parsley/parsley.min.js"></script>
    <script src="assets/parsley/id.js"></script>
    <!-- END: Page JS-->

    <script src="assets/plugin/custom/style.js"></script>
    <script src="assets/plugin/custom/auth.js"></script>

</body>
<!-- END: Body-->

</html>
