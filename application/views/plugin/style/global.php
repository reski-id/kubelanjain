<?php
$color  = '#f5a300';
$color2 = '#b9ccf3';
?>
<style>
body.dark-layout #bg_listpoint_active {
  background-color: #20263a !important;
}
body.dark-layout #bg_listpoint {
  background-color: #272e48 !important;
}
body.dark-layout .table tbody td a i, body.dark-layout .table tbody th a i {
  color: #dfe6f5 !important;
}

.main-menu .navbar-header .navbar-brand .brand-logo {
    width: 170px;
}

.modal-backdrop {
  z-index: 10040 !important;
}
.modal{
  z-index: 10050 !important;
}
.select2-dropdown {
  z-index: 10060 !important;
}
.sweet-overlay {
  z-index: 10070 !important;
}

/* MENU */
.main-menu.menu-light .navigation > li.active:not(.sidebar-group-active) > a {
  background: rgba(255, 230, 181, 0.35) !important;
  color: <?= $color; ?> !important;
  border-radius: 0.267rem;
}
.main-menu.menu-light .navigation > li ul .active {
    background: rgba(255, 230, 181, 0.35) !important;
}
.main-menu.menu-light .navigation > li ul .active > a {
    color: <?= $color; ?> !important;
}
body.dark-layout p, body.dark-layout small, body.dark-layout span, body.dark-layout label {
  color: <?= $color2; ?> !important;
}
/* MENU */

<?php //if (uri(1)=='faqs'): ?>
/* FAQS */
.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active {
  border: 2px solid <?= $color; ?> !important;
}
.swiper-centered-slides.swiper-container .swiper-slide.swiper-slide-active i {
  color: <?= $color; ?> !important;
}
.swiper-centered-slides .swiper-button-next:after, .swiper-centered-slides .swiper-button-prev:after {
  background-color: <?= $color; ?> !important;
}
.bx.bxs-circle.font-small-1{
  color: <?= $color; ?> !important;
}
/* FAQS */

/* Button */
/* .btn-primary {
  background-color: <?= $color; ?> !important;
}

.btn-primary:hover {
  background-color: <?= $color2; ?> !important;
}

.btn-primary:focus, .btn-primary:active, .btn-primary.active {
    background-color:  <?= $color; ?> !important;
} */
/* Button */

/* .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
    background-color: <?= $color; ?> !important;
}

.dropdown-notification .dropdown-menu-header {
    background: <?= $color; ?> !important;
} */
.tfoot-dark{
  color: #FFFFFF;
  background-color: #475F7B;
  border: 0px !important;
}
.table-bordered .tfoot-dark td {
  border: 1px solid #475F7B;
}

<?php if(view_mobile()){ ?>
  html body.navbar-sticky .app-content .content-wrapper {
      padding-left: 0px !important;
      padding-right: 0px !important;
      margin-top: 3rem;
  }
<?php } ?>
</style>
