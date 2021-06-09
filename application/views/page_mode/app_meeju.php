<meta http-equiv="refresh" content="1;<?= base_url('apk/download'); ?>">
<!-- download -->
<section class="row flexbox-container">
    <div class="col-xl-6 col-md-7 col-12">
        <div class="card bg-transparent shadow-none">
            <div class="card-content">
                <div class="card-body text-center bg-transparent miscellaneous">
                    <img src="img/auth/meeju.png" class="img-fluid" width="300">
                    <h3 class="error-title text-white"></h3>
                    <p class="text-white" id="p_text">Mendownload . . . </p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- download end -->
<script type="text/javascript">
  run_time(1);
  function run_time(no=1)
  {
    setTimeout(function(){
      no++;
      if (no==3) {
        $('#p_text').html('Terimakasih!');
      }else if (no==5) {
        window.location.href = "<?= web('index_redirect'); ?>";
      }
      run_time(no);
    }, 1000);
  }
</script>
