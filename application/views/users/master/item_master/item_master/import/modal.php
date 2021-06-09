<div class="modal-body pl-0 pr-0">
  <form action="javascript:import_data();" method="post" id="import_form" data-parsley-validate='true' enctype="multipart/form-data">
    <?php
    $datanya[] = array('type'=>'file', 'name'=>'file', 'nama'=>'File Excel', 'icon'=>'file', 'html'=>' required accept=".xls, .xlsx" ', 'col'=>'12');
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <small class="text-danger">*Jika <b>ID KATEGORI dan Nama Item</b> tidak ada di database, maka <b>Data Import</b> tersebut tidak disimpan.</small>
    </div>
    <div id="v_data" style="max-height: 300px; overflow: auto;"></div>
    <!-- <input type="hidden" name="id_item_kategorinya" value="">
    <input type="hidden" name="plunya" value="">
    <input type="hidden" name="nama_itemnya" value="">
    <input type="hidden" name="nilai_satuannya" value="">
    <input type="hidden" name="id_item_satuannya" value=""> -->
    <hr>
    <div class="col-md-12">
      <a href="assets/file/import/template/<?= $tbl; ?>.xlsx" class="btn btn-secondary glow mr-1" target="_blank"> <i class="bx bx-download"></i> <span>Template Import</span> </a>
      <button type="submit" class="btn btn-success glow float-right" id="import"> <i class="bx bxs-file"></i> <span id="btnname">Import</span> </button>
    </div>
  </form>
</div>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
//Custom File Input
$('[name="file"]').change(function (e) {
  $(this).next(".custom-file-label").html(e.target.files[0].name);
})

  function import_data()
  {
    $('#v_data').html('');
    form = 'import_form';
    form_disabled(form, true, 'all');
    var fd = new FormData();
      fd.append('file', $('input[name="file"]')[0].files[0]);
      $.ajax({
        type: "POST",
        url : 'master/aksi_import/<?= $tbl; ?>/view',
        data: fd,
        dataType: "json",
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        chace: false,
        beforeSend: function(){
          loading_show();
        },
        success: function( data ) {
          if (data.stt==1) {
            $('#modal-aksi').modal('hide');
            RefreshTable();
            $('#v_data').html(data.pesan);
            swal({ title : "Success!", text : "", type : "success", showConfirmButton: false, allowEscapeKey: false });
            set_timeout=2;
          }else {
            swal({ title : "Gagal", text : data.pesan, type : "warning", showConfirmButton: false, allowEscapeKey: false });
            set_timeout=5;
          }
          setTimeout(function(){ swal.close(); }, set_timeout*1000);
          form_disabled(form, false, 'all');
          loading_close();
          // $('#v_data').html('<br />\
          //   <table class="table table-bordered table-striped" width="100%">\
          //     <thead class="thead-dark">\
          //       <tr>\
          //         <th class="text-center" width="1%">#</th>\
          //         <th class="text-center" width="9%">Kategori</th>\
          //         <th class="text-center" width="10%">PLU</th>\
          //         <th class="text-center" width="65%">Nama&nbsp;Item</th>\
          //         <th class="text-center" width="15%">Isian&nbsp;Angka</th>\
          //         <th class="text-center" width="10%">Satuan</th>\
          //       </tr>\
          //     </thead>\
          //     <tbody id="v_data_body"></tbody>\
          //   </table>');
          // AmbilData = param.detailnya; no=1;
          // $.each(AmbilData, function(index, loaddata) {
          //   id_item_kategori = loaddata.id_item_kategori;
          //   plu              = loaddata.plu;
          //   nama_item        = loaddata.nama_item;
          //   nilai_satuan     = loaddata.nilai_satuan;
          //   id_item_satuan   = loaddata.id_item_satuan;
          //   $('#v_data_body').append('\
          //   <tr style="background:'+loaddata.warna+'">\
          //     <td>'+no+'</td>\
          //     <td><input type="text" class="form-control" name="id_item_kategori[]" id="id_item_kategori_'+index+'" value="'+id_item_kategori+'"/></td>\
          //     <td><input type="text" class="form-control" name="plu[]" id="plu_'+index+'" value="'+plu+'"/></td>\
          //     <td><input type="text" class="form-control" name="nama_item[]" id="nama_item_'+index+'" value="'+nama_item+'"/>'+loaddata.ket+'</td>\
          //     <td><input type="text" class="form-control" name="nilai_satuan[]" id="nilai_satuan_'+index+'" value="'+nilai_satuan+'"/></td>\
          //     <td><input type="text" class="form-control" name="id_item_satuan[]" id="id_item_satuan_'+index+'" value="'+id_item_satuan+'"/></td>\
          //   </tr>');
          //   no++;
          // });
          // form_disabled(form, false, 'all');
          // $('#btnname').html('Simpan');
          // $('#import_form').attr('action', 'javascript:simpan();');
        },
        error: function(){
          loading_close();
          swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
        }
      });
  }

  function get_multiple(get='', set='')
  {
    if ($('[name="'+get+'[]"]').length!=0) {
      var x = [];
      $('[name="'+get+'[]"]').each(function(i, selected) {
        x[i] = i + ' ' + $(selected).val();
      });
      if ($('[name="'+set+'"]').length!=0) {
        $('[name="'+set+'"]').val(JSON.stringify(x));
      }
    }
  }

  function save_array()
  {
    get_multiple('id_item_kategori', 'id_item_kategorinya');
    get_multiple('plu', 'plunya');
    get_multiple('nama_item', 'nama_itemnya');
    get_multiple('nilai_satuan', 'nilai_satuannya');
    get_multiple('id_item_satuan', 'id_item_satuannya');
  }

  function simpan()
  {
    loading_show();
    save_array();
    simpan('sync_form','<?= base_url()."master/simpan/item_master_import"; ?>','','swal','5','1','1');
  }

  function run_function_check(stt='')
  {
    if (stt==1) {
      $('#modal-aksi').modal('hide');
      RefreshTable();
    }
    loading_close();
  }
</script>
