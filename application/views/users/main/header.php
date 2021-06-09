<nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu fixed-top ">
    <div class="navbar-wrapper">
        <div class="navbar-container content">
            <div class="navbar-collapse" id="navbar-mobile">
                <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                    <ul class="nav navbar-nav">
                        <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon bx bx-menu"></i></a></li>
                        <?php if (in_array(uri(1), array('order','transaksi'))): ?>
                          <li class="nav-item mobile-menu mr-auto">
                          	<a class="nav-link nav-menu-main hidden-xs" style="font-size:18px;"> <?= strtoupper($judul_web); ?> </a>
                          </li>
                        <?php endif; ?>
                    </ul>
                    <!-- <ul class="nav navbar-nav bookmark-icons">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-email.html" data-toggle="tooltip" data-placement="top" title="Email"><i class="ficon bx bx-envelope"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-chat.html" data-toggle="tooltip" data-placement="top" title="Chat"><i class="ficon bx bx-chat"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-todo.html" data-toggle="tooltip" data-placement="top" title="Todo"><i class="ficon bx bx-check-circle"></i></a></li>
                        <li class="nav-item d-none d-lg-block"><a class="nav-link" href="app-calendar.html" data-toggle="tooltip" data-placement="top" title="Calendar"><i class="ficon bx bx-calendar-alt"></i></a></li>
                    </ul>
                    <ul class="nav navbar-nav">
                        <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"><i class="ficon bx bx-star warning"></i></a>
                            <div class="bookmark-input search-input">
                                <div class="bookmark-input-icon"><i class="bx bx-search primary"></i></div>
                                <input class="form-control input" type="text" placeholder="Explore Frest..." tabindex="0" data-search="template-search">
                                <ul class="search-list"></ul>
                            </div>
                        </li>
                    </ul> -->
                </div>
                <ul class="nav navbar-nav float-right">
                    <!-- <li class="dropdown dropdown-language nav-item pt-2"><a onclick="window.location.reload();"><i class="bx bx-rotate-left"></i></a></li> -->
                    <li class="dropdown dropdown-language nav-item">
                      <a href="javascript:mode();">
                        <div class="livicon-evo" data-options="
                          name: bulb.svg;
                          style: original;
                          size: 35px;
                          strokeStyle: original;
                          strokeWidth: original;
                          tryToSharpen: true;
                          rotate: none;
                          flipHorizontal: false;
                          flipVertical: false;
                          strokeColor: #22A7F0;
                          strokeColorAction: #b3421b;
                          strokeColorAlt: #F9B32F;
                          strokeColorAltAction: #ab69c6;
                          fillColor: #91e9ff;
                          fillColorAction: #ff926b;
                          solidColor: #6C7A89;
                          solidColorAction: #4C5A69;
                          solidColorBgAction: #ffffff;
                          solidColorBg: #ffffff;
                          colorsOnHover: <?php echo mode('icon'); ?>;
                          colorsHoverTime: 0.3;
                          colorsWhenMorph: none;
                          brightness: 0.1;
                          saturation: 0.07;
                          morphState: start;
                          morphImage: none;
                          allowMorphImageTransform: false;
                          strokeWidthFactorOnHover: none;
                          strokeWidthOnHoverTime: 0.3;
                          keepStrokeWidthOnResize: false;
                          animated: true;
                          eventType: hover;
                          eventOn: self;
                          autoPlay: false;
                          delay: 0;
                          duration: default;
                          repeat: default;
                          repeatDelay: default;
                          drawOnViewport: false;
                          viewportShift: oneHalf;
                          drawDelay: 0;
                          drawTime: 1;
                          drawStagger: 0.1;
                          drawStartPoint: middle;
                          drawColor: same;
                          drawColorTime: 1;
                          drawReversed: false;
                          drawEase: Power1.easeOut;
                          eraseDelay: 0;
                          eraseTime: 1;
                          eraseStagger: 0.1;
                          eraseStartPoint: middle;
                          eraseReversed: true;
                          eraseEase: Power1.easeOut;
                          touchEvents: false">
                        </div>
                      </a>
                    </li>
                    <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a></li>
                    <?php view('users/main/notif'); ?>
                    <li class="dropdown dropdown-user nav-item">
                      <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                        <div class="user-nav d-sm-flex d-none">
                            <?php $level = get_session('level');
                            if ($level==0){ //jika admin
                              if(get_session('level')==0 && get_session('tag_akun')=='Admin'){
                                $namanya = get_session('username');
                              }else {
                                $namanya = ucwords(user('username'));
                              }
                              $id_nya  = get_session('tag_akun');//jenis_akun();
                            }else{
                              $namanya = ucwords(user('nama_lengkap'));
                              if ($level==1) {
                                $lv = 'mitra';
                                $nama_ID = 'ID MITRA';
                              }elseif ($level==2) {
                                $lv = 'reseller';
                                $nama_ID = 'ID RESELLER';
                              }
                              $tbl = 'user_biodata_'.$lv;
                              $id_nya = "$nama_ID : ".user('id_mitra', get_session('id_user'), $tbl);
                            }
                            ?>
                              <span class="user-name"><?= $namanya; ?></span>
                              <?php if ($id_nya!=''): ?>
                                <span class="user-status text-muted">
                                  <small><?= $id_nya; ?></small>
                                </span>
                              <?php endif; ?>
                              <!-- <span class="user-status text-muted"><?= jenis_akun(); ?></span> -->
                        </div>
                        <span><img class="round" src="<?php echo cek_foto(user('foto'),'user-null.jpg'); ?>" alt="avatar" height="40" width="40"></span>
                      </a>
                        <div class="dropdown-menu dropdown-menu-right pb-0">
                          <a class="dropdown-item" href="users/profile.html">
                            <i class="bx bx-user mr-50"></i> Profile
                          </a>
                          <a class="dropdown-item" href="users/reset_password.html">
                            <i class="bx bx-lock mr-50"></i> Reset Password
                          </a>
                          <div class="dropdown-divider mb-0"></div><a class="dropdown-item" href="auth/logout.html"><i class="bx bx-power-off mr-50"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
