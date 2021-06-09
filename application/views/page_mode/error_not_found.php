<!-- error 404 -->
<section class="row flexbox-container">
    <div class="col-xl-6 col-md-7 col-12">
        <div class="card bg-transparent shadow-none">
            <div class="card-content">
                <div class="card-body text-center bg-transparent miscellaneous">
                    <img src="<?= web('logo'); ?>" class="img-fluid">
                    <h3 class="error-title text-white">Halaman tidak ditemukan :(</h3>
                    <p class="text-white">kami tidak dapat menemukan halaman yang Anda minta</p>
                    <a href="<?php if(!empty($_GET['url'])){ echo $_GET['url']; }else{ echo "index"; } ?>.html" class="btn btn-warning round glow mt-1">Kembali ke Halaman Utama</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- error 404 end -->
