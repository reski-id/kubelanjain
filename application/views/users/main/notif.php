<?php
if (get_session('level')==0){
  $jml_pesan = 0;
}else {
  $jml_pesan = 1;
} ?>
<li class="dropdown dropdown-notification nav-item">
  <a class="nav-link nav-link-label" href="#" data-toggle="dropdown">
    <i class="ficon bx bx-bell bx-tada bx-flip-horizontal"></i>
    <span class="badge badge-pill badge-danger badge-up"><?= $jml_pesan; ?></span>
  </a>
    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
        <li class="dropdown-menu-header">
            <div class="dropdown-header px-1 py-75 d-flex justify-content-between">
              <span class="notification-title"><?= $jml_pesan; ?> Pesan baru</span><span class="text-bold-400 cursor-pointer">Tandai semua telah dibaca</span>
            </div>
        </li>
        <li class="scrollable-container media-list">
            <?php if ($jml_pesan!=0): ?>
              <a class="d-flex justify-content-between" href="javascript:void(0)">
                  <div class="media d-flex align-items-center">
                      <!-- <div class="media-left pr-0">
                          <div class="avatar mr-1 m-0">
                            <img src="<?php echo cek_foto(user('foto',1),'user-null.jpg'); ?>" alt="avatar" height="39" width="39">
                          </div>
                      </div> -->
                      <div class="media-body">
                          <h6 class="media-heading">
                            <b>Berhasil Registrasi</b>
                            <hr style="margin:0px;padding:0px;padding-top:5px;padding-bottom:5px;">
                            Terima kasih, registrasi Anda sebagai reseller <b>Meeju</b> telah berhasil. Admin kami yang bernama <b>Ida</b> akan menghubungi Anda, maksimal 2x24 jam dari masa pendaftaran untuk menginformasikan total biaya atas paket yang Anda pesan (termasuk ongkir) dan alamat rekening pembayarannya.
                            <br>
                            Terima kasih.
                          </h6>
                          <small class="notification-text"><?php echo tgl_id(user('tgl_input'), 'd-m-Y H:i:s'); ?></small>
                      </div>
                  </div>
              </a>
            <?php endif; ?>
        </li>
        <li class="dropdown-menu-footer"><a class="dropdown-item p-50 text-primary justify-content-center" href="javascript:void(0)">Lihat Semua</a></li>
    </ul>
</li>
