<!-- Jquery Core Js -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript">
var oTable;
    fnServerObjectToArray = function () {
      // console.log('s '+cari);
        return function (sSource, aoData, fnCallback) {
          aoData.push( { "name": "cari", "value": "true" } );
          $.ajax
          ({
              'dataType': 'json',
              'type': 'POST',
              'url': sSource,
              'data': aoData,
              'success': fnCallback,
              "error": handleAjaxError
          });
        }
    }

$(document).ready(function () {
  RefreshTable();
});

function handleAjaxError(xhr, textStatus, error) {
    if (textStatus === 'timeout') {
        alert('The server took too long to send the data.');
    }
    else {
        alert('An error occurred on the server. Please try again in a minute.');
        // window.location.reload();
    }
    oTable.fnProcessingIndicator(false);
}

function RefreshTable() {
  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
  {
    return {
      "iStart": oSettings._iDisplayStart,
      "iEnd": oSettings.fnDisplayEnd(),
      "iLength": oSettings._iDisplayLength,
      "iTotal": oSettings.fnRecordsTotal(),
      "iFilteredTotal": oSettings.fnRecordsDisplay(),
      "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
      "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
    };
  };

  dataSource = '<?= base_url("$url/list_data/$tbl") ?>';
  if (oTable)
      oTable.fnDestroy();
      $('#fileData').dataTable().fnDestroy();
  if (fnServerObjectToArray) {
    var oTable = $("#fileData").dataTable({
      initComplete: function() {
        var api = this.api();
        $('#mytable_filter input')
        .off('.DT')
        .on('keyup.DT', function(e) {
          if (e.keyCode == 13) {
            api.search(this.value).draw();
          }
        });
      },
      "oLanguage": {
        "sProcessing": "Memproses . . ."
      },
      "bProcessing": true,
      "ScrollX": true,
      "scrollCollapse": true,
      "bServerSide": true,
      "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      'sAjaxSource': dataSource,
      "fnServerData": fnServerObjectToArray(),
      'fnDrawCallback': function (oSettings) {
          $('[name="refresh_tabel"]').html('<i class="fa fa-refresh"></i> Refresh');
      },
      "columns": [
        {"data": null},
        {"data": "id"},
        {"data": "nama"},
        {"data": "ket"},
        {"data": null},
      ],
      "aaSorting": [[1, 'desc']],
      "columnDefs": [
        {
          "searchable": false, "targets": [1], "visible":false, className: "hide_column"
        },
        {
          className: "text-center text-bold", "searchable": false, "orderable": false, "targets": [0],
          render: function( data, type, row ){
            return '<td width="1%">'+row+'</td>'
          },
        },
        {
          className: "text-left", "targets": [2,3],
          render: function( data, type, row ){
            return data
          },
        },
        {
          "searchable": false, "orderable": false, className: "text-center", "targets": 4,
          render: function(data, type, row) {
            idnya = data.id_x;
            detail = "aksi('detail','"+idnya+"')";
            edit  = "aksi('edit','"+idnya+"')";
            hapus = "aksi('hapus','"+idnya+"')";
            btn_aksi  = '<a onclick="'+detail+'" class="btn btn-info btn-xs"><i class="material-icons">list</i></a>&nbsp;';
            btn_aksi += '<a onclick="'+edit+'" class="btn btn-success btn-xs"><i class="material-icons">edit</i></a>&nbsp;';
            btn_aksi += '<a onclick="'+hapus+'" class="btn btn-danger btn-xs"><i class="material-icons">delete</i></a>';
            return btn_aksi;
          },
        }
      ],
      rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
      },
    });
  }
}

function reload_tabel()
{
  $('[name="refresh_tabel"]').html('<i class="fa fa-refresh fa-spin"></i> Refresh');
  $('#fileData').DataTable().ajax.reload(null, false);
}


function disabled_input(stt)
{
  $("#formnya input").prop("disabled", stt);
}


function aksi(stt='',id='')
{
  $('#formnya').parsley().reset();
  $('#formnya')[0].reset();
  $('#modal_id').html('');
  if (stt=='tambah') {
    $('#modal_judul').html('Tambah Data');
    $('#modal-aksi').modal('show');
  }else if (stt=='edit' && id!='') {
    $('#modal_judul').html('Edit Data');
    Q_detail(id,1);
  }else if (stt=='detail' && id!='') {
    $('#modal_judul-detail').html('Detail Data');
    Q_detail(id);
  }else if (stt=='hapus' && id!='') {
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
      Q_hapus(id);
    });
  }
}

function Q_detail(id, edit='')
{
  $.ajax({
    type: "POST",
    url : "<?php echo base_url("$url/view_data/$tbl"); ?>",
    data: "id="+id,
    dataType: "json",
    beforeSend: function(){
      get_mohon_tunggu();
    },
    success: function( data ) {
      $('#modal_id').html(id);
      <?php foreach ($this->db->list_fields($tbl) as $key => $value): ?>
      if (edit==1) {
        if ($('[name="<?php echo $value; ?>"]').length!=0) {
          $('[name="<?php echo $value; ?>"]').val(data.<?php echo $value; ?>);
        }
        $('#modal-aksi').modal('show');
      }else {
        if ($('#v_<?php echo $value; ?>').length!=0) {
          $('#v_<?php echo $value; ?>').html(data.<?php echo $value; ?>);
        }
        $('#modal-detail').modal('show');
      }
      <?php endforeach; ?>
      swal.close();
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

function Q_simpan(){
  var fd = new FormData();
  fd.append("id",$('#modal_id').html());
  <?php foreach ($this->db->list_fields($tbl) as $key => $value): ?>
  if ($('[name="<?php echo $value; ?>"]').val()=='') {
    return false;
  }
  if ($('[name="<?php echo $value; ?>"]').length!=0) {
    fd.append('<?php echo $value; ?>',$('[name="<?php echo $value; ?>"]').val());
  }
  <?php endforeach; ?>

  disabled_input(true);
  $('[name="simpan"]').attr('disabled',true);
  dataString = $('#formnya').serialize();
  $.ajax({
    type: "POST",
    url : "<?php echo base_url("$url/simpan/$tbl"); ?>",
    data: fd,
    dataType: "json",
    processData: false,  // tell jQuery not to process the data
    contentType: false,   // tell jQuery not to set contentType
    beforeSend: function(){
      $('[name="simpan"]').html('<i class="fa fa-refresh fa-spin"></i> Menyimpan . . .');
      get_mohon_tunggu();
    },
    success: function( data ) {
      reload_tabel();
      $('[name="simpan"]').attr('disabled',false);
      $('[name="simpan"]').html('<i class="material-icons">save</i> SIMPAN');
      disabled_input(false);
      if (data.stt==1) {
        swal({ title : "Sukses", text  : "Berhasil disimpan!", type : "success" });
        $('#modal-aksi').modal('hide');
      }else if (data.stt==0) {
        swal({ title : "Gagal", text  : "Silahkan coba lagi!", type : "error" });
      }else {
        swal({ title : "Gagal", text  : data.stt, type : "error" });
      }
    },
    error: function(){
      disabled_input(false);
      $('[name="simpan"]').attr('disabled',false);
      $('[name="simpan"]').html('<i class="material-icons">save</i> SIMPAN');
      swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
        function() {
          // window.location.reload();
        }
      );
    }
  });
}


function Q_hapus(id){
    $.ajax({
      type: "POST",
      url : "<?php echo base_url("$url/hapus/$tbl"); ?>",
      data: "id="+id,
      dataType: "json",
      beforeSend: function(){
        get_mohon_tunggu();
      },
      success: function( data ) {
        reload_tabel();
        if (data.stt==1){
          swal({ title : "Sukses", text  : "Berhasil dihapus!", type : "success" });
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
}

</script>
