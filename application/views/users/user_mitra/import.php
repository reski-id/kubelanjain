<div class="modal-body">
  <form action="javascript:simpan_aja('import_form','<?= $url_import; ?>');" method="post" id="import_form" data-parsley-validate='true' enctype="multipart/form-data">
    <?php
    $datanya[] = array('type'=>'file', 'name'=>'file', 'nama'=>'File Excel', 'icon'=>'file', 'html'=>' required accept=".xls, .xlsx" ', 'col'=>'12');
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <small class="text-danger">*Jika <b>NAMA PROVINSI, KOTA, BANK</b> tidak ada di database atau <b>NOMOR HANDPHONE</b> sudah ada didatabase, maka <b>LIST</b> tersebut tidak disimpan (diabaikan).</small>
    </div>
    <hr>
    <div class="col-md-12">
      <a href="assets/file/import/template/<?= $tbl; ?>.xlsx" class="btn btn-secondary glow mr-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a>
      <button type="submit" class="btn btn-success glow float-right" id="import"> <i class="bx bxs-file"></i> <span>Import</span> </button>
    </div>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
//Custom File Input
$('[name="file"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
})

function simpan_aja(form='', get_url='', set_timeout='3')
{
  if(form==''){ log_r('id form belum ditetukan!','background:pink;color:#222;'); return false; }
  if($('#pesannya')==''){ log_r('id pesannya belum ditetukan!','background:pink;color:#222;'); return false; }
  form_disabled(form, true, 'all');
  var fd = new FormData();
  $('#'+form+' *').each(function(key, field) {
    var field_name = field.name;
    var field_type = field.type;
    var field_multiple = field.multiple;
    if ($('[name="'+field_name+'"]').length!=0) {
      if (field_type === 'file') {
        if ($('[name="'+field_name+'"]').val() !== '') {
          fd.append(field_name, $('input[name='+field_name+']')[0].files[0]);
        }
      }
    }
  });
  $.ajax({
    type: "POST",
    url : get_url,
    data: fd,
    dataType: "json",
    processData: false,  // tell jQuery not to process the data
    contentType: false,   // tell jQuery not to set contentType
    chace: false,
    beforeSend: function(){
      loading_show();
    },
    success: function( data ) {
      loading_close();
      if (data.stt==1) {
        swal({ html:true, type: "success", title: "Sukses!", text: data.pesan, showConfirmButton: false, allowEscapeKey: false });
        setTimeout(function(){ window.location.reload(); }, set_timeout*1000);
      }else {
        if (data.pesan=='') {
          get_pesan = 'Gagal! Ada kesalahan, silahkan coba lagi!';
        }else {
          get_pesan = data.pesan;
        }
        swal({ html:true, type: "warning", title: "Gagal!", text: get_pesan });
      }
      form_disabled(form, false, 'all');
    },
    error: function(){
      swal({ html:true, type: "error", title: "Error!", text: "Ada kesalahan, silahkan coba lagi!", showConfirmButton: false, allowEscapeKey: false });
      setTimeout(function(){ swal.close(); }, set_timeout*1000);
      form_disabled(form, false, 'all');
      loading_close();
    }
  });
}

</script>
