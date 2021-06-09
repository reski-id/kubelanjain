<style>
/* jika lebar layar dibawah 600px */
@media only screen and (max-width: 600px) {
  #form_UU{ padding: 0px; padding-top: 10px; }
  #row_mobile{
    margin-right: -30px !important;
    margin-left: -30px !important;
  }
}
</style>
<div id="pesannya"></div>
<!-- Form wizard with step validation section start -->
<section id="validation">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <!-- <div class="card-header pb-0">
                    <h4 class="card-title">Data Usaha</h4>
                </div> -->
                <div class="card-content">
                    <div class="card-body pt-2" id="form_UU">
                        <form action="#" class="wizard-validation" id="step_form">
                          <?php
                          $data_step[] = array('icon'=>'building',       'judul'=>'NAMA TOKO',   'nama_file'=>'data_usaha');
                          $data_step[] = array('icon'=>'location-alt',   'judul'=>'LOKASI',   'nama_file'=>'data_alamat');
                          $data_step[] = array('icon'=>'credit-card-in', 'judul'=>'BANK',    'nama_file'=>'data_bank');
                          $data_step[] = array('icon'=>'check-alt',      'judul'=>'SELESAI', 'nama_file'=>'selesai');
                          ?>
                          <?php foreach ($data_step as $key => $value): ?>
                            <!-- Step <?= $key; ?> -->
                            <h6>
                                <i class="step-icon"></i>
                                <span class="fonticon-wrap">
                                    <i class="livicon-evo" data-options="name:<?= $value['icon']; ?>.svg; size: 50px; style:lines; strokeColor:#adb5bd;"></i>
                                </span>
                                <span><?= $value['judul']; ?></span>
                            </h6>
                            <!-- Step <?= $key; ?> -->
                            <!-- body content of step <?= $key; ?> -->
                            <fieldset>
                              <?php view('users/akun/usaha/step/'.$value['nama_file']); ?>
                              <hr>
                            </fieldset>
                            <!-- body content of step <?= $key; ?> end -->
                          <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Form wizard with step validation section end -->


<link rel="stylesheet" type="text/css" href="assets/css/plugins/forms/wizard.css">
<!-- BEGIN: Page Vendor JS-->
<script src="assets/vendors/js/extensions/jquery.steps.min.js"></script>
<script src="assets/vendors/js/forms/validation/jquery.validate.min.js"></script>
<!-- END: Page Vendor JS-->
<script type="text/javascript">
// Show form
var stepsValidation = $(".wizard-validation");
var form = stepsValidation.show();

stepsValidation.steps({
headerTag: "h6",
bodyTag: "fieldset",
transitionEffect: "fade",
titleTemplate: '<span class="step">#index#</span> #title#',
labels: {
  finish: 'Selesai'
},
onStepChanging: function (event, currentIndex, newIndex) {
  // Allways allow previous action even if the current form is not valid!
  if (currentIndex > newIndex) {
    return true;
  }
  form.validate().settings.ignore = ":disabled,:hidden";
  return form.valid();
},
onFinishing: function (event, currentIndex) {
  form.validate().settings.ignore = ":disabled";
  return form.valid();
},
onFinished: function (event, currentIndex) {
  swal({ html:true, title: "", text: "APAKAH DATA ANDA SUDAH LENGKAP & BENAR ?", type: "warning",
        showCloseButton: true, showCancelButton: true,
        confirmButtonText:'Sudah', cancelButtonText:'Belum',
  },
  function(){
    $('.page-loader-wrapper').show();
    simpan('step_form', 'users/add_usaha', '', '', '0', '', '1');
  });
}
});

function run_function_check(stt='')
{
  $('.page-loader-wrapper').hide();
  if (stt==1) {
    pesanne = 'Terimakasih sudah melengkapi Data Anda.';
    pesan("success", "", pesanne);
    swal({ html:true, title : "Sukses", text : pesanne, type : "success", showConfirmButton: false, allowEscapeKey: false });
    setTimeout(function(){ window.location.href = "dashboard.html"; }, 5*1000);
  }else {
    $('.page-loader-wrapper').hide();
    pesanne = 'Pastikan Data Anda Lengkap & Benar.';
    pesan("danger", "", pesanne);
    swal({ html:true, title : "Gagal!", text : pesanne, type : "warning", showConfirmButton: false, allowEscapeKey: false });
    setTimeout(function(){
      swal.close();
      // window.location.reload();
    }, 5*1000);
  }
}
</script>

<script src="assets/js/scripts/forms/validation/localization/messages_id.js"></script>
<script src="assets/js/scripts/forms/wizard-steps.js"></script>
