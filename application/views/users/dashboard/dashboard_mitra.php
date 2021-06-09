<div class="alert alert-success alert-dismissible mb-2 col-md-12" id="show_first_jk" role="alert" hidden>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="d-flex align-items-center">
        <i class="bx bx-check-double"></i>
        <span>
            Selamat bergabung bersama MEEJU INDONESIA
        </span>
    </div>
</div>


<?php
$tbl = 'user_biodata_mitra';
$this->db->select('id_mitra, type_id, alamat');
$getV = get_field($tbl, array('id_user'=>get_session('id_user')));
$alamatnya = $getV['alamat'];
$id_mitra = $getV['id_mitra'];
$lv_mitra = $getV['type_id'];
$link = web('website').'/';
$link_reseller = $link."reseller/$id_mitra";
$link_mitra    = $link."mitra/$id_mitra";

$this->db->select('id_user');
$this->db->limit('1');
$get_RES = get('user_biodata_reseller', array('no_hp'=>get_session('username')))->row();
?>

<div class="row">
  <div class="col-12 col-md-6 timline-card">
    <div class="card ">
        <div class="card-header">
          <h4 class="card-title col-12 col-md-10 pt-1 pl-0">
              <b>ID MITRA : <label class="badge badge-secondary"><?= $id_mitra; ?></label></b>
          </h4>
        </div>
        <div class="card-content">
            <div class="card-body">

              <label>LINK ADD RESELLER</label><br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <button class="btn btn-secondary pl-1 pr-1" data-toggle="tooltip" data-placement="top" title="Copy Link" onclick="copy_linknya('copy-reseller')"><i class="bx bx-copy-alt"></i></button>
                </div>
                <input type="text" class="form-control" id="copy-reseller" readonly aria-describedby="basic-addon1" onclick="copy_linknya('copy-reseller')" value="<?= $link_reseller; ?>">
              </div>
              <br>
              <?php if ($lv_mitra==1): ?>
              <label>LINK ADD MITRA II</label><br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <button class="btn btn-secondary pl-1 pr-1" data-toggle="tooltip" data-placement="top" title="Copy Link" onclick="copy_linknya('copy-mitra')"><i class="bx bx-copy-alt"></i></button>
                </div>
                  <br>
                  <input type="text" class="form-control" id="copy-mitra" readonly aria-describedby="basic-addon2" onclick="copy_linknya('copy-mitra')" value="<?= $link_mitra; ?>">
              </div>
              <?php endif; ?>

            </div>
        </div>
    </div>
  </div>
  <div class="col-12 col-md-6 timline-card">
    <?php if (empty($get_RES)){ ?>
      <button class="btn btn-warning glow btn-block" onclick="add_reseller();">DAFTAR SEKARANG SEBAGAI RESELLER</button>
    <?php }else{
      $this->db->select('id_user, id_mitra');
      // $get_R = get_field('user_biodata_reseller', array('id_referal'=>$id_mitra, 'no_hp'=>get_session('username')));
      $get_R = get_field('user_biodata_reseller', array('no_hp'=>get_session('username')));
      $id_reseller = $get_R['id_mitra'];
      $id_nya['id_nya'] = $get_R['id_user'];
      $id_nya['sbg']    = 'RESELLER';
      ?>
      <div class="card">
          <div class="card-header">
            <h4 class="card-title col-12 col-md-10 pt-1 pl-0">
                <b>ID RESELLER : <label class="badge badge-secondary"><?= $id_reseller; ?></label></b>
            </h4>
          </div>
          <div class="card-content">
              <div class="card-body">
                <button class="btn btn-warning glow btn-block" onclick="login_aja();">LOGIN SEBAGAI RESELLER</button>
              </div>
          </div>
      </div>
      <?php view('users/dashboard/login', $id_nya); ?>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
function copy_linknya(idnya) {
  var copyText = document.getElementById(idnya);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");

  $('#'+idnya).tooltip({
        title: "Copied: " + copyText.value,
        trigger: 'click',
        placement: 'top',
  });
  $('#'+idnya).on('mouseout', function() {
      $(this).tooltip('hide');
  });
}
</script>

<?php if (empty($get_RES)){ ?>
<div class="modal fade" id="modal-add_reseller" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">DAFTAR SEBAGAI RESELLER</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <form id="sync_form" action="javascript:add_data_reseller();" method="post" data-parsley-validate="true">
            <div class="modal-body pb-0 mb-0">
              <div class="row">
                <?php
                  $datanya[] = array('type'=>'textarea', 'name'=>'alamat_pengantaran', 'nama'=>'Alamat Pengantaran', 'validasi'=>true, 'icon'=>'map', 'html'=>' data-parsley-trigger="keyup"', 'value'=>$alamatnya, 'col'=>12);
                  $datanya[] = array('type'=>'file', 'name'=>'foto', 'nama'=>'Foto', 'icon'=>'image', 'html'=>'', 'col'=>12);

                  $get_sosmed[] = array('icon'=>'bxl-facebook-square', 'nama'=>'Facebook');
                  $get_sosmed[] = array('icon'=>'bxl-instagram-alt', 'nama'=>'Instagram');
                  $get_sosmed[] = array('icon'=>'bxl-twitter', 'nama'=>'Twitter');
                  foreach ($get_sosmed as $key => $value) {
                    $datanya[] = array('type'=>'text', 'name'=>'sosmed'.$key, 'value'=>'', 'nama'=>$value['nama'], 'icon'=>' '.$value['icon'], 'html'=>' data-parsley-trigger="keyup"', 'col'=>6);
                  }

                  $data_paket = array();
                  foreach (get_paketnya(1)->result() as $key => $value) {
                    $data_paket[] = array('id'=>encode($value->id_paketnya), 'nama'=>$value->paketnya);
                  }
                  $datanya[] = array('type'=>'select', 'name'=>'paket', 'nama'=>'Paket', 'validasi'=>true, 'icon'=>'-', 'html'=>' onchange="hitung_harga_paket()" data-parsley-trigger="keyup"', 'data_select'=>$data_paket, 'col'=>12);
                  $datanya[] = array('type'=>'number', 'name'=>'jumlah', 'nama'=>'Jumlah Pesan', 'value'=>1, 'validasi'=>true, 'icon'=>' bxs-calculator', 'html'=>' onkeyup="hitung_harga_paket()" readonly maxlength="3" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)"', 'col'=>5);
                  $datanya[] = array('type'=>'text', 'name'=>'harga', 'nama'=>'Harga', 'value'=>'Rp. 0', 'validasi'=>true, 'icon'=>'money', 'html'=>' readonly', 'col'=>7);
                data_formnya($datanya);
                ?>
              </div>
            </div>
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
<script type="text/javascript">
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

  function add_reseller()
  {
    $('#modal-add_reseller').modal({'show':true, 'backdrop':'static', 'keyboard': false});
  }

  function add_data_reseller()
  {
    simpan('sync_form','users/proses/add_mitra_reseller','','swal','5','','1');
  }

  function run_function_check(stt='')
  {
    if (stt=='1') {
      $('#modal-add_reseller').modal('hide');
      setTimeout(function(){
        window.location.reload();
      }, 2*1000);
    }
  }
</script>
<?php } ?>
