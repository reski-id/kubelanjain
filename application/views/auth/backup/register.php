<div class="card-header pb-0 pt-0">
    <div class="card-title">
        <center>
          <img src="<?php echo web('logo'); ?>" alt="" width="200" class="img-responsive">
        </center>
        <!-- <h5 class="text-center mb-2">Form Pendaftaran</h5> -->
    </div>
</div>
<div class="text-center">
  <?php $KU = false;
  if (!empty($_GET['p'])){
    $no_HP = decode(get_session('no_hp'));
    if (in_array($_GET['p'], array($no_HP, $no_HP.'.html'))) {
      if (empty($_GET['reg'])) { $KU = true;?>
        <br>
        <small>Kode verifikasi telah dikirim melalui <?= get_session('sent_to'); ?> ke telepon kamu</small>
        <br><br>
  <?php }
    }
  } ?>

  <?php if (!$KU): ?>
    <p> <h4> <b>Register <?= ucwords(uri(1)); ?></b> </h4> </p>
  <?php endif; ?>
</div>
<div class="card-content pb-0 pt-0">
    <div class="card-body pb-0 pt-0">
        <?php get_pesan('msg'); ?>
        <div id="pesannya"></div>
        <?php $get_URL= base_url().uri(1); $no_HP='';
         if (!empty($GET['r'])) { $get_Ref = '&r='.$GET['r']; }
        ?>
        <form id="sync_form" action="javascript:void(0);" method="post" data-parsley-validate="true">
          <?php
          // if (empty(get_session('no_hp'))) {
          //   if (web('index_redirect')!='') {
        	// 		redirect(web('index_redirect'));
        	// 	}else {
          //     redirect('404');
          //   }
          // }
          $referal = uri(2);
          cek_Referal($referal, uri(1));
          if (empty($_GET['p'])) {
            if (uri(1)=='mitra' && uri(2)=='') {
              $get_X = 1;
            }elseif (uri(1)=='mitra' && uri(2)!='') {
              $get_X = 2;
            }elseif (uri(1)=='reseller') {
              $get_X = 3;
            }
            $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'placeholder'=>'08....', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' min="10" step="100" minlength="10" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"');
            $datanya[] = array('type'=>'text', 'name'=>'x', 'nama'=>'', 'validasi'=>true, 'value'=>$get_X, 'html'=>'', 'hidden'=>true);
            $datanya[] = array('type'=>'text', 'name'=>'id_referal', 'nama'=>'', 'value'=>$referal, 'html'=>'', 'hidden'=>true);
            $nama_btn  = 'Kirim Aktivasi';
          }else {
            $no_HP = decode(get_session('no_hp'));
            $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'value'=>$no_HP, 'placeholder'=>'08....', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' min="10" step="100" minlength="10" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)" readonly');
            if (in_array($_GET['p'], array($no_HP, $no_HP.'.html'))) {
              if (empty($_GET['reg'])) {
                $datanya[] = array('type'=>'text', 'name'=>'kode', 'nama'=>'Masukkan Kode Verifikasi', 'placeholder'=>'xxxxxx', 'validasi'=>true, 'icon'=>'key', 'html'=>' minlength="6" maxlength="6" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"');
              }else {
                if (decode($_GET['reg'])!=get_session('kode')) {
                  redirect('404');
                }
                $data_jk[] = array('id'=>'Laki - Laki', 'nama'=>'Laki - Laki');
                $data_jk[] = array('id'=>'Perempuan', 'nama'=>'Perempuan');

                $this->db->order_by('provinsi', 'ASC');
                foreach (get('provinsi', array('status'=>1))->result() as $key => $value) {
                  $data_prov[] = array('id'=>$value->id_provinsi, 'nama'=>$value->provinsi);
                }

                // $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'validasi'=>true, 'icon'=>'mobile', 'value'=>$no_HP, 'html'=>' min="10" step="100" minlength="10" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)" readonly');
                $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'placeholder'=>'Nama sesuai KTP', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup"');
                // $datanya[] = array('type'=>'select', 'name'=>'jenis_kelamin', 'nama'=>'Jenis Kelamin', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_jk);
                // $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Alamat Email', 'validasi'=>true, 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"');
                $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="show_kota();"', 'data_select'=>$data_prov);
                $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kabupaten / Kota', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" disabled');
                $datanya[] = array('type'=>'password', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"');
                // $datanya[] = array('type'=>'password', 'name'=>'password2', 'nama'=>'Konfirmasi Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup" onkeyup="validatePassword(\'password\',\'password2\')"');
              }
            }else {
              redirect($get_URL);
            }
            $nama_btn = 'Lanjut';
          }
          data_formnya($datanya);
          ?>
          <?php if ($nama_btn=='Kirim Aktivasi'){ ?>
            <center>
              <label class="pb-1">Kirim Aktivasi via</label>
            </center>
            <div class="row">
              <div class="col-md-6">
                <button type="submit" name="BtnReg" onclick="javascript:cek_Reg(1);" class="btn btn-success glow position-relative w-100 mb-1"><i class="bx bxl-whatsapp"></i> Whatsapp</button>
              </div>
              <div class="col-md-6">
                <button type="submit" name="BtnReg" onclick="javascript:cek_Reg(2);" class="btn btn-warning glow position-relative w-100"><i class="bx bxs-envelope"></i> SMS</button>
              </div>
            </div>
          <?php }else{ ?>
            <button type="submit" name="BtnReg" onclick="javascript:cek_Reg();" class="btn btn-warning glow position-relative w-100"><?= $nama_btn; ?><i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>
          <?php } ?>

          <?php if ($KU): ?>
            <center>
              <br>
              <small id="resend"></small>
            </center>
          <?php endif; ?>
        </form>
        <hr>
        <div class="text-center <?php if(view_mobile()){ echo "pb-1"; } ?>"><small class="mr-25">Sudah punya akun?</small> <a href="auth/login.html"><small>Login</small> </a></div>
    </div>
</div>

<?php view('plugin/select2/custom'); ?>
<script type="text/javascript">
<?php if ($KU):
  if (get_session('sent_to')=='SMS') {
    $sent_TO = 2;
  }elseif (get_session('sent_to')=='Whatsapp') {
    $sent_TO = 1;
  }
?>
    // $(document).ready(function() {
        var detik   = 60;
        function hitung_ya() {
          setTimeout(hitung_ya,1000);
          $('#resend').html('Mohon tunggu '+detik+' detik untuk mengirim ulang.');
          detik --;
          if(detik < 0) {
            clearInterval(hitung_ya);
            $('#resend').html('Tidak menerima kode? <a href="javascript:void(0);" id="resend_lagi">Kirim ulang</a>');
            $("#resend_lagi").on( "click", function() {
              auth('Send_aja/<?= $sent_TO; ?>','','<?= $get_URL; ?>?p=<?= decode(get_session('no_hp')); ?><?= $get_Ref; ?>');
            });
          }
        }
        hitung_ya();
    // });
<?php endif; ?>

  function cek_Reg(stt='')
  {
    no_hp = '';
    if ($('[name="no_hp"]').length!='') {
      no_hp = $('[name="no_hp"]').val();
      if (no_hp=='') {
        return false;
      }
    }
    <?php if(empty($_GET['p'])){ ?>
      auth('cek_register/'+stt,'','<?= $get_URL; ?>?p='+no_hp+'<?= $get_Ref; ?>');
    <?php }else{ ?>
      <?php if(empty($_GET['reg'])){ ?>
        auth('konfirmasi_register/<?= $no_HP; ?>','','<?= $get_URL; ?>?reg=<?= encode(get_session('kode')); ?>&p=<?= $no_HP; ?><?= $get_Ref; ?>');
      <?php }else{ ?>
        auth('register');
      <?php } ?>
    <?php } ?>
  }
  if ($('select').length!=0) {
    $('select').select2({ width: '100%' });
  }

  function show_kota()
  {
    // $('[name="id_kota"]').empty();
    // id_prov = $('[name="id_provinsi"] :selected').val();
    // if (id_prov!='') {
    //   $('[name="id_kota"]').removeAttr('disabled');
    //   $("[name='id_kota']").select2({
    //     ajax: {
    //       url: '<?php echo base_url('web/get_select/kota'); ?>/'+id_prov+'?filter=yes',
    //       dataType: 'json',
    //       delay: 250,
    //       processResults: function (data) {
    //         return { results: data };
    //       },
    //       cache: true
    //     }
    //   });
    // }else {
    //   $('[name="id_kota"]').attr('disabled', true);
    // }

    $('[name="id_kota"]').empty();
    $('[name="id_kota"]').append('<option value=""> Pilih Kota </option>');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>web/ajax_kota",
        data: 'p='+$('[name="id_provinsi"] :selected').val(),
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(param){
            AmbilData = param.plus;
            $.each(AmbilData, function(index, loaddata) {
                $('[name="id_kota"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
            });
            $('[name="id_kota"]').removeAttr('disabled');
            loading_close();
        }
    });
  }
</script>
