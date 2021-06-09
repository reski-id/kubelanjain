<!-- maintenance start -->
<section class="row flexbox-container">
    <div class="col-xl-7 col-md-8 col-12">
        <div class="card bg-transparent shadow-none">
            <div class="card-content">
                <div class="card-body text-center bg-transparent miscellaneous">
                    <img src="assets/images/pages/maintenance-2.png" class="img-fluid" alt="under maintenance" width="400">
                    <h1 class="error-title my-1 text-white">Under Maintenance!</h1>
                    <p class="px-2 text-white">
                      Maaf atas ketidaknyamanan ini tetapi kami sedang melakukan beberapa pemeliharaan saat ini. Jika perlu, Anda selalu dapat <a href="https://api.whatsapp.com/send?phone=<?php echo convert_phone(web('no_hp'), 'ID'); ?>" target="_blank">menghubungi kami</a>, jika tidak, kami akan segera kembali online!
                    </p>
                    <a href="<?php if(!empty($_GET['url'])){ echo $_GET['url']; }else{ echo "index"; } ?>.html" class="btn btn-warning round glow mt-2">Kembali ke Halaman Utama</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- maintenance end -->
