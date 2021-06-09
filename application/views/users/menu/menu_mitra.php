<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
              <a class="navbar-brand" href="dashboard.html">
                    <div class="brand-logo">
                      <center>
                        <img class="logo" src="<?= web('logo'); ?>" style="height:40px;width:100px;padding-bottom:10px;"/>
                      </center>
                    </div>
                    <!-- <h2 class="brand-text mb-0"><?= web('nama_web'); ?></h2> -->
                </a>
            </li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i><i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary" data-ticon="bx-disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation" data-icon-style="lines">
            <li class="<?php if(in_array(uri(1), array('dashboard','users')) && uri(2)=='') {echo "active";} ?> nav-item">
              <a href="dashboard.html"><i class="menu-livicon" data-icon="desktop"></i><span class="menu-title" data-i18n="Dashboard">Dashboard</span></a>
            </li>
            <?php list_menu(1); ?>
        </ul>
    </div>
</div>
