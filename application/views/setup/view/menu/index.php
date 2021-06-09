<?php get_pesan('msg'); ?>
<div class="dd nestable-with-handle">
  <ol class="dd-list">
    <?php set_list_menu($akun_menux); ?>
  </ol>
</div>
<div hidden>
  <b>Output JSON</b>
  <textarea cols="30" rows="3" class="form-control no-resize" id="urutan_menu" readonly></textarea>
</div>

<div class="col-md-12">
  <br>
  <?php if (check_permission('view', 'update', "setup/menu/".uri(3))): ?>
    <button onclick="Q_simpan()" name="simpan" class="btn btn-success glow float-right"> SIMPAN </button>
  <?php endif; ?>
</div>

<script type="text/javascript">
function Q_simpan(){
  if ($('#urutan_menu').val()=='') {
    swal({ title : "Sukses", text  : "Berhasil disimpan!", type : "success", showConfirmButton: false, });
    setTimeout(function(){ swal.close(); }, 1000);
    return false;
  }

  $.ajax({
    type: "POST",
    url : "<?php echo base_url($url.'/simpan_urutan/'.uri(3)); ?>",
    data: "urutan_menu="+$('#urutan_menu').val(),
    dataType: "json",
    beforeSend: function(){
      $('[name="simpan"]').html('Menyimpan . . .');
      get_mohon_tunggu();
    },
    success: function( data ) {
      $('[name="simpan"]').attr('disabled',false);
      $('[name="simpan"]').html('SIMPAN');
      if (data.stt==1) {
        swal({ title : "Sukses", text  : "Berhasil disimpan!", type : "success", showConfirmButton: false, });
        setTimeout(function(){window.location.reload();}, 1000);
      }else if (data.stt==0) {
        swal({ title : "Gagal", text  : "Silahkan coba lagi!", type : "error" });
      }else if (data.stt=='x') {
        swal({ title : "Permission Denied!", text  : "", type : "warning" });
      }else {
        swal({ title : "Gagal", text  : data.stt, type : "error" });
      }
    },
    error: function(){
      $('[name="simpan"]').attr('disabled',false);
      $('[name="simpan"]').html('SIMPAN');
      swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
        function() {
          // window.location.reload();
        }
      );
    }
  });
}

function Q_tambah(){
  sel = $('#add_menu option:selected').val();
  window.location.href = "<?php echo "$url/$tblnya/$akun_menux"; ?>/t/"+sel;
}

function status_menu(id){
    $.ajax({
      type: "POST",
      url : "<?php echo base_url("$url/status_menu/$tblnya"); ?>",
      data: "id="+id,
      dataType: "json",
      beforeSend: function(){
        loading_show();
      },
      success: function( data ) {
        if (data.stt==1) {
          $('#menunya_'+id).prop('hidden','');
        }else if (data.stt==0) {
          $('#menunya_'+id).prop('hidden','hidden');
        }else {
          swal({ title : "Gagal", text  : "Silahkan coba lagi!", type : "error" });
        }
        loading_close();
      },
      error: function(){
        swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
          function() {
            // window.location.reload();
          }
        );
      }
    });
}

function Q_hapus(id){
  swal({
      title: "Apakah Anda Yakin?",
      text: "",
      type: "warning",
      showCloseButton: true,
      showCancelButton: true,
      confirmButtonText:'Yakin',
      cancelButtonText:'Tidak',
  },
  function(){

    $.ajax({
      type: "POST",
      url : "<?php echo base_url("$url/hapus/$tblnya"); ?>",
      data: "id="+id,
      dataType: "json",
      beforeSend: function(){
        get_mohon_tunggu();
      },
      success: function( data ) {
        if (data.stt==1) {
          swal({ title : "Sukses", text  : "Berhasil disimpan!", type : "success" });
          setTimeout(function(){window.location.reload();}, 1000);
        }else if (data.stt==0) {
          swal({ title : "Gagal", text  : "Silahkan coba lagi!", type : "error" });
        }else {
          swal({ title : "Gagal", text  : data.stt, type : "error" });
        }
      },
      error: function(){
        swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
          function() {
            // window.location.reload();
          }
        );
      }
    });

  });
}
</script>
