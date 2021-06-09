<style>
.select2-container {
  min-width: 150px !important;
}
</style>
<?php
$katanya = 'Saya sudah paham & mengerti.';
?>
<div class="modal fade" id="modal-promo-dashboard" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Lengkapi Data Anda</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button> -->
            </div>
            <form id="sync_form" action="javascript:add_data_Client();" method="post" data-parsley-validate="true">
            <div class="modal-body pb-1 mb-0">
              <div class="row">
                <?php
                $data_jk[] = array('id'=>'Laki - Laki', 'nama'=>'Laki - Laki');
                $data_jk[] = array('id'=>'Perempuan', 'nama'=>'Perempuan');
                $datanya[] = array('type'=>'select', 'name'=>'jenis_kelamin', 'class'=>'select2', 'nama'=>'Jenis Kelamin', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_jk, 'col'=>6);
                $datanya[] = array('type'=>'email', 'name'=>'email', 'nama'=>'Email', 'icon'=>'mail-send', 'html'=>' maxlength="100" data-parsley-trigger="keyup"', 'col'=>6);
                $datanya[] = array('type'=>'textarea', 'name'=>'alamat', 'nama'=>'Alamat Tinggal', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);
                $datanya[] = array('type'=>'text', 'name'=>'pekerjaan', 'nama'=>'Pekerjaan Saat ini', 'validasi'=>true, 'icon'=>'briefcase-alt-2', 'html'=>' data-parsley-trigger="keyup"', 'col'=>12);

                if (get_session('level')==1) { //jika mitra
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
                  $datanya[] = array('type'=>'select', 'name'=>'informasi_dari', 'nama'=>'Dapat Informasi Dari ?', 'validasi'=>true, 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup"', 'data_select'=>$data_info, 'col'=>6);
                  $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>6);

                  foreach (get_sosmed() as $key => $value) {
                    $datanya[] = array('type'=>'text', 'name'=>'sosmed'.$key, 'value'=>'', 'nama'=>$value['nama'], 'icon'=>' '.$value['icon'], 'html'=>' data-parsley-trigger="keyup"', 'col'=>6);
                  }
                  if (user('id_referal', get_session('id_user'), 'user_biodata_reseller') != '') {
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
                data_formnya($datanya);
                ?>
              </div>
            </div>
            <!-- <center>
              <div class="checkbox m-1">
                  <input type="checkbox" class="custom-input" name="cek_modalnya" id="cek_modalnya">
                  <label for="cek_modalnya"> <?= $katanya; ?></label>
              </div>
            </center> -->
            <div class="modal-footer p-0">
                <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 0px;">
                    <i class="bx bx-task"></i>
                    <span> <b <?php if(view_mobile()){ echo ' style="font-size: 14px;"'; } ?>>SELESAI</b> </span>
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<?php //view('plugin/parsley/custom'); ?>
<script type="text/javascript">
  $('#modal-promo-dashboard').modal({'show':true, 'backdrop':'static', 'keyboard': false});

  if ($('[name="foto"]').length!=0) {
    //Custom File Input
    $('[name="foto"]').change(function (e) {
      $(this).next(".custom-file-label").html(e.target.files[0].name);
    })
  }

<?php if (get_session('level')!=1) { ?>
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
          // if (data.harga=='-') {
          //   swal({ html:true, title : "Paket tidak valid", text : 'Silahkan di refresh & input ulang, Terimakasih!', type : "warning", confirmButtonText:'OKE Saya Mengerti', showConfirmButton: true, allowEscapeKey: false });
          // }else {
            jumlah.removeAttr('readonly');
            harganya.val(data.harga);
          // }
        }
    });
  }
<?php } ?>

  function add_data_Client() {
    // cek = $('input[name=cek_modalnya]');
    // if (cek.is(':checked')) {
      simpan('sync_form','users/proses/up_data','','swal','5','','1');
    // }else {
    //   swal({ html:true, title : "Wajib di Baca!!", text : 'Maaf, Anda belum checklist "<?= $katanya; ?>"', type : "warning", confirmButtonText:'OK Saya Cheklist Sekarang', showConfirmButton: true, allowEscapeKey: false });
    // }
  }

  function run_function_check(stt='')
  {
    if (stt==1) {
      if ($('#show_first_jk').length!='') {
        $('#show_first_jk').removeAttr('hidden');
      }
      $('#modal-promo-dashboard').modal('hide');
    }
  }
</script>
