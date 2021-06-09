<?php
if (dbnya()==$this->db->database) {
  // $view = true;
  $view = false;
}else {
  $view = false;
}
?>
<div class="card-header pb-0 pt-0">
    <div class="card-title">
        <center>
          <img src="<?php echo web('logo'); ?>" alt="" width="200" class="img-responsive">
        </center>
        <!-- <h5 class="text-center mb-2">Form Pendaftaran</h5> -->
    </div>
</div>
<div class="text-center">
  <p> <h4> <b>Register <?= ucwords(uri(1)); ?></b> </h4> </p>
</div>
<div class="card-content pb-0 pt-0">
    <div class="card-body pb-0 pt-0">
        <?php get_pesan('msg'); ?>
        <div id="pesannya"></div>
        <?php $get_URL= base_url().uri(1); ?>
        <form id="sync_form" action="javascript:void(0);" method="post" data-parsley-validate="true">
          <div class="row">
          <?php $p=''; $no_HP='';
          if (!empty($_GET['p'])) {
            $p = preg_replace("/[^0-9]/",'',$_GET['p']);
          }
          $referal = uri(2);
          cek_Referal($referal, uri(1));
          if (in_array(uri(1), array('mitra','reseller')) && $p!='') {
            $readonly = 'readonly';
            $autofocus = '';
            $no_HP = $p;
          }else {
            if (uri(2)!='') {
              $get_URL .= '/'.uri(2);
            }
            $readonly = '';
            $autofocus = 'autofocus';
          }

          // mau mendaftar sebagai . . .
          if (uri(1)=='mitra' && uri(2)=='') { //mitra 1
            $get_X = 1;
          }elseif (uri(1)=='mitra' && uri(2)!='') { //mitra 2
            $get_X = 2;
          }elseif (uri(1)=='reseller' && uri(2)=='') { //reseller umum
            $get_X = 5;
          }elseif (uri(1)=='reseller' && uri(2)!='') { //reseller dari mitra 1 / 2
            $this->db->select('type_id');
            $get_M = get('user_biodata_mitra', array('id_mitra'=>$referal))->row();
            if (!empty($get_M)) {
              $get_type_M = $get_M->type_id;
              if ($get_type_M==1) { //dari mitra 1
                $get_X = 3;
              }elseif ($get_type_M==2) { //dari mitra 2
                $get_X = 4;
              }else {
                redirect(web('index_redirect'));
              }
            }else {
              redirect(web('index_redirect'));
            }
          }else{
            redirect(web('index_redirect'));
          }

          $datanya[] = array('type'=>'text', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'value'=>$no_HP, 'placeholder'=>'08....', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' min="10" step="100" minlength="10" maxlength="14" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)" '.$readonly.' '.$autofocus, 'col'=>12);
          $datanya[] = array('type'=>'text', 'name'=>'x', 'nama'=>'', 'validasi'=>true, 'value'=>$get_X, 'html'=>'', 'hidden'=>true);
          $datanya[] = array('type'=>'text', 'name'=>'id_referal', 'nama'=>'', 'value'=>$referal, 'html'=>'', 'hidden'=>true);
            if ($readonly!='') {
              $cek_data = get_un($p);
              if ($cek_data->num_rows() <> 0) {
                if (get_block_USER($no_HP, $get_X)) {
                  if (get_session('username')==$no_HP) {
                    redirect('dashboard');
                  }else{
                    redirect('404');
                  }
                }
              }

                $data_jk[] = array('id'=>'Laki - Laki', 'nama'=>'Laki - Laki');
                $data_jk[] = array('id'=>'Perempuan', 'nama'=>'Perempuan');

                $this->db->order_by('provinsi', 'ASC');
                foreach (get('provinsi', array('status'=>1))->result() as $key => $value) {
                  $data_prov[] = array('id'=>$value->id_provinsi, 'nama'=>$value->provinsi);
                }

                $datanya[] = array('type'=>'text', 'name'=>'nama_lengkap', 'nama'=>'Nama Lengkap', 'placeholder'=>'Nama sesuai KTP', 'validasi'=>true, 'icon'=>'user', 'html'=>'autofocus data-parsley-trigger="keyup" data-parsley-pattern="^[a-z A-Z]+$"', 'col'=>12);
                $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="show_kota();"', 'data_select'=>$data_prov, 'col'=>12);
                $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kabupaten / Kota', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" disabled', 'col'=>12);

              if ($view) {
                $datanya[] = array('type'=>'select', 'name'=>'jenis_kelamin', 'class'=>'select2', 'nama'=>'Jenis Kelamin', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_jk, 'col'=>12);
                $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"', 'col'=>12);
                $datanya[] = array('type'=>'textarea', 'name'=>'alamat', 'nama'=>'Alamat Tinggal', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);
                $datanya[] = array('type'=>'text', 'name'=>'pekerjaan', 'nama'=>'Pekerjaan Saat ini', 'validasi'=>true, 'icon'=>'briefcase-alt-2', 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);

                if (uri(1)=='mitra') { //jika mitra
                  $data_bank = array();
                  $this->db->order_by('bank', 'ASC');
                  foreach (get('bank', array('status'=>1))->result() as $key => $value) {
                    $data_bank[] = array('id'=>$value->id_bank, 'nama'=>$value->bank);
                  }
                  $datanya[] = array('type'=>'select', 'name'=>'id_bank', 'value'=>'', 'nama'=>'Bank', 'validasi'=>true, 'icon'=>'-', 'html'=>'', 'data_select'=>$data_bank, 'col'=>12);
                  $datanya[] = array('type'=>'text', 'name'=>'nama', 'value'=>'', 'nama'=>'Nama Pemilik Bank', 'validasi'=>true, 'icon'=>'user-pin', 'html'=>'', 'col'=>12);
                  $datanya[] = array('type'=>'text', 'name'=>'no_rek', 'value'=>'', 'nama'=>'Nomor Rekening Bank', 'validasi'=>true, 'icon'=>'paperclip', 'html'=>' minlength="1" maxlength="20" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'col'=>12);
                }else { //jika reseller
                  $datanya[] = array('type'=>'textarea', 'name'=>'alamat_pengantaran', 'nama'=>'Alamat Pengantaran', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);
                  $data_info = array();
                  $this->db->order_by('id_informasi', 'ASC');
                  foreach (get('informasi', array('status'=>1))->result() as $key => $value) {
                    $data_info[] = array('id'=>$value->informasi, 'nama'=>$value->informasi);
                  }
                  $datanya[] = array('type'=>'select', 'name'=>'informasi_dari', 'nama'=>'Dapat Informasi Dari ?', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_info, 'col'=>12);
                  $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>12);

                  foreach (get_sosmed() as $key => $value) {
                    $datanya[] = array('type'=>'text', 'name'=>'sosmed'.$key, 'value'=>'', 'nama'=>$value['nama'], 'icon'=>' '.$value['icon'], 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);
                  }
                  if ($referal != '') {
                    $jenis = 1;
                  }else {
                    $jenis = 2;
                  }
                  $data_paket = array();
                  foreach (get_paketnya($jenis)->result() as $key => $value) {
                    $data_paket[] = array('id'=>encode($value->id_paketnya), 'nama'=>$value->paketnya);
                  }
                  $datanya[] = array('type'=>'select', 'name'=>'paket', 'nama'=>'Paket', 'validasi'=>true, 'icon'=>'-', 'html'=>' onchange="hitung_harga_paket()" data-parsley-trigger="keyup"', 'data_select'=>$data_paket, 'col'=>12);
                  $datanya[] = array('type'=>'number', 'name'=>'jumlah', 'nama'=>'Jumlah Pesan', 'value'=>1, 'validasi'=>true, 'icon'=>' bxs-calculator', 'html'=>' onkeyup="hitung_harga_paket()" readonly maxlength="3" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'col'=>5);
                  $datanya[] = array('type'=>'text', 'name'=>'harga', 'nama'=>'Harga', 'value'=>'Rp. 0', 'validasi'=>true, 'icon'=>'money', 'html'=>' readonly', 'col'=>7);
                }
              }
                $datanya[] = array('type'=>'password', 'name'=>'password', 'nama'=>'Password', 'validasi'=>true, 'icon'=>'key', 'html'=>'minlength="5" data-parsley-uppercase="1" data-parsley-lowercase="1" data-parsley-number="1" data-parsley-trigger="keyup"', 'col'=>12);
          }
          $nama_btn = 'Lanjut';
          data_formnya($datanya);
          ?>
          </div>
          <button type="submit" name="BtnReg" onclick="javascript:cek_Reg();" class="btn btn-warning glow position-relative w-100"><?= $nama_btn; ?><i id="icon-arrow" class="bx bx-right-arrow-alt"></i></button>

        </form>
        <hr>
        <div class="text-center <?php if(view_mobile()){ echo "pb-1"; } ?>"><small class="mr-25">Sudah punya akun?</small> <a href="auth/login.html"><small>Login</small> </a></div>
    </div>
</div>

<?php view('plugin/select2/custom'); ?>
<script type="text/javascript">

  function cek_Reg(stt='')
  {
    no_hp = '';
    if ($('[name="no_hp"]').length!='') {
      no_hp = $('[name="no_hp"]').val();
      if (no_hp=='') {
        return false;
      }
    }
    <?php if($readonly==''){ ?>
      auth('konfirmasi_register/'+no_hp,'','<?= $get_URL; ?>?p='+no_hp+'<?= $get_Ref; ?>');
    <?php }else{ ?>
      auth('register');
    <?php } ?>
  }
  if ($('select').length!=0) {
    $('select').select2({ width: '100%' });
  }

  function show_kota()
  {
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

  $('[name="nama_lengkap"]').keypress(function (e) {
        var regex = new RegExp("^[a-z A-Z]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (regex.test(str)) {
            return true;
        }
        else
        {
          // pesan("warning", "", 'Nama Lengkap tidak boleh Angka');
          return false;
        }
  });

<?php if($view){ ?>
  if ($('[name="foto"]').length!=0) {
    //Custom File Input
    $('[name="foto"]').change(function (e) {
      $(this).next(".custom-file-label").html(e.target.files[0].name);
    })
  }

  function hitung_harga_paket()
  {
    jumlah = $('[name="jumlah"]');
    harganya = $('[name="harga"]');
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>web/ajax_hitung_harga_paket",
        data: 'p='+$('[name="paket"] :selected').val()+'&jml='+jumlah.val(),
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          harganya.val('Menghitung . . .');
          jumlah.attr('readonly',true);
        },
        success: function(data){
          jumlah.removeAttr('readonly');
          harganya.val(data.harga);
        }
    });
  }
<?php } ?>
</script>
