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
    <!-- BEGIN: Theme CSS-->

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

    <link rel="stylesheet" type="text/css" href="assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-extended.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <link href="assets/plugin/swal/sweetalert.css" rel="stylesheet">
    <script src="assets/plugin/swal/sweetalert.min.js"></script>



       <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/extensions/swiper.min.css">
    <link rel="stylesheet" type="text/css" href="assets/vendors/css/extensions/sweetalert2.min.css">
    <!-- END: Vendor CSS-->


    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/core/menu/menu-types/vertical-menu.css">
    <!-- Bootstrap Select Css -->
    <!-- END: Page CSS-->
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <!-- END: Custom CSS-->
    <link href="assets/plugin/swal/sweetalert.css" rel="stylesheet">
    <script src="assets/plugin/swal/sweetalert.min.js"></script>
    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/plugin/custom/style.js"></script>
    <script src="assets/plugin/nestable/jquery.nestable.js"></script>

      <?php view('plugin/select2/custom'); ?>
    <!-- END: Page JS-->

    <script src="assets/plugin/custom/crud.js"></script>


    <style>
    .scroll_{
      overflow: auto;
      max-height: <?= (view_mobile()) ? '260' : '360'; ?>px;
    }
    .pagination .page-item.active .page-link, .pagination .page-item.active .page-link:hover {
        background-color: #39DA8A !important;
    }

    .pagination a {
      color: #39DA8A !important;
    }

      @font-face {
        src: url(assets/fonts/Chicken_Pie/CHICKEN_Pie.ttf);
      }

      #header {
        position: fixed;
        z-index: 999;
        top: 0px;
        width: 100%;
        padding-top: 15px;
        padding-bottom: 10px;
        background:#7b9a51;height:60px;border-bottom:1px solid #ddd;
        height: 69px;
      }

      #header img {
        margin-top: -15px;
      }

      .logo {
        font-size: 30px;
      }
      #whatsapp_logo {
        font-size: 30px;
      }
      #text_copyright {
        line-height: 1.5rem;
        color: whitesmoke;
        text-align: center;
      }

      /* @media (min-width: 576px) {
        #header {
          height: 69px;
      } */

       }

    </style>
</head>
<!-- END: Head-->
<body>

    <div class="container-fluid">
      <div class="row" id="header">
        <div class="col-8 col-md-2 col-sm-5">
          <img src="<?= web('logo'); ?>" alt="" width="30">&nbsp;
          <a href="https://kubelanjain.com/"><b clas="text-success logo" style="font-size: 25px; color:whitesmoke !important ">kubelanjain</b></a>
        </div>

        <div class="col-4 col-md-10 " id="btn_order">
          <a class="btn btn-success glow float-right text-white" style="<?php if (view_mobile()){ echo "font-size:8px;"; } ?>box-shadow: 1px 2px 1px #888888;" href="https://api.whatsapp.com/send?phone=+6281276453398&text=&source=&data=" target="_blank">
            <b><i class="bx bx-cart-alt"></i> Order</b>
          </a>
        </div>
      </div>

      <?php view($content); ?>

      <?php if (!empty($halaman) || !empty($footer)) { ?>
      <div class="row p-2" style="background:#fdfeff;">
        <!-- Footer Location-->
        <div class="col-md-6 mb-2 col-sm-6">
            <h6 class="mb-1 text-center" style="color:#a3b6da"><b>
            <img src="<?= web('logo'); ?>" alt="" width="15%">&nbsp;
            &nbsp;
          <a href="https://kubelanjain.com/"><b clas="text-success logo" style="font-size: 25px; color:#7b9a51 !important ">kubelanjain.com</b></a>
            </b></h6>
        </div>
        <!-- Footer Social Icons-->
        <div class="col-md-6 mb-3 col-sm-6 text-center">
            <h6 class="mb-1 text-center" style="color:#a3b6da"><b>
            <a href="https://api.whatsapp.com/send?phone=+6281276453398&text=&source=&data=" class="text-success" target="_blank" id="whatsapp_logo">
              <i class="bx bxl-whatsapp" style="font-size: 4.28rem;"></i>
              <p style="font-size:16px">Telpon Saya</p>
            </a>
            </b></h6>
        </div>
      <?php } ?>

    </div>
    <?php if (!empty($halaman) || !empty($footer)) { ?>
      <div class="row p-2" style="background:#7b9a51; box-shadow: 5px 0px 18px 5px #888888;">

        <div class="col-md-12 mb-12 col-sm-12">
            <p id="text_copyright">Copyright(c) 2021 kubelanjain All rights reserved.</p>
        </div>

      <?php } ?>
    <script src="assets/vendors/js/vendors.min.js"></script>
    <script src="assets/js/scripts/components.js"></script>
    <!-- <script src="assets/plugin/lightbox/lightbox-plus-jquery.min.js"></script> -->

    <?php view('plugin/select2/custom'); ?>
    <?php view('plugin/parsley/custom'); ?>
    <script>
    if ($('select').length!=0) {
      $('select').select2({ width: '100%' });
    }
    </script>

</body>
</html>
