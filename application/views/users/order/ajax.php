<!-- Jquery Core Js -->
<?php view('plugin/dataTable/custom'); ?>
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

function RefreshTable(status='') {
  if (status=='') {
    status = $('.nav-item > a.active').attr('aria-controls');
  }
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

  dataSource = '<?= base_url("$tbl/list_data/$tbl") ?>/'+status;
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
        {"data": "no_transaksi"},
        {"data": "tgl_input"},
        {"data": "nama_pelanggan"},
        {"data": "no_hp"},
        {"data": "total_belanja", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
        {"data": null},
      ],
      "aaSorting": [[3, 'desc']],
      "columnDefs": [
        {
          "searchable": false, "targets": [1], "visible":false, className: "hide_column"
        },
        {
          className: "text-center text-bold align-top", "searchable": false, "orderable": false, "targets": [0],
          render: function( data, type, row ){
            return '<td width="1%">'+row+'</td>'
          },
        },
        {
          className: "text-left align-top", "targets": [2, 3, 4, 5, 6],
          render: function( data, type, row ){
            return data
          },
        },
        {
          "searchable": false, "orderable": false, className: "text-center align-top", "targets": 7,
          render: function(data, type, row) {
            idnya  = data.id_x;
            cetak  = "aksi_cetak('"+idnya+"')";
            btn_aksi  = '<a href="order/cetak_struk/'+idnya+'" target="_blank" class="btn btn-icon rounded-circle glow btn-secondary" data-toggle="tooltip" data-placement="top" title="Cetak"><i class="bx bx-printer"></i></a>&nbsp;';
            detail = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>', 'lg')";
            btn_aksi += '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-info" data-toggle="tooltip" data-placement="top" title="Detail"><i class="bx bx-file"></i></a>&nbsp;';
            <?php if (check_permission('view', 'update', "order")) { ?>
              if (data.status==0) {
                // edit      = "aksi('edit','"+idnya+"','sync_form','<?= $url_modal; ?>', 'xl')";
                // btn_aksi += '<a href="javascript:'+edit+'" class="btn btn-icon rounded-circle glow btn-warning" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil"></i></a>&nbsp;';
                payment   = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>/opsi_metode_pembayaran', 'sm')";
                btn_aksi += '<a href="javascript:'+payment+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Payment"><i class="bx bx-check"></i></a>&nbsp;';
              }else if (data.status==1) {
                done   = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>/opsi_done')";
                btn_aksi += '<a href="javascript:'+done+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Done"><i class="bx bx-check"></i></a>&nbsp;';
              }
            <?php } ?>
            <?php if (check_permission('view', 'delete', "order")) { ?>
              if (data.status==0) {
                cancel     = "aksi_cancel('"+idnya+"')";
                btn_aksi += '<a href="javascript:'+cancel+'" class="btn btn-icon rounded-circle glow btn-danger" data-toggle="tooltip" data-placement="top" title="Cancel"><i class="bx bx-x"></i></a>&nbsp;';
              }
            <?php } ?>
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


function btn_status(id='', stt='', msg='') {
  swal({ html:true, title: "Apakah Anda Yakin?", text: msg, type: "warning",
      showCloseButton: true, showCancelButton: true,
      confirmButtonText:'Yakin', cancelButtonText:'Tidak',
  },
  function(){
    // loading_show();
    hapus_data('order/simpan/order_cancel/'+id, stt, '5', 1);
  });
}

<?php if (check_permission('view', 'delete', "order")) { ?>
function aksi_cancel(id='')
{
  swal({
    title: "Cancel Order!",
    text: "Berikan Alasan :",
    type: "input",
    showCancelButton: true,
    closeOnConfirm: false,
    cancelButtonText:'Batal',
    animation: "slide-from-top",
    inputPlaceholder: "Input Alasan . . ."
  },
  function(inputValue){
    if (inputValue === false) return false;

    if (inputValue === "") {
      swal.showInputError("Alasan Cancel Wajib diisi!");
      return false
    }

    setTimeout(function(){
      hapus_data('order/simpan/order_cancel/'+id, inputValue, '5', 1);
    }, 50);
  });
}
<?php } ?>

// function aksi_cetak(id='')
// {
//   swal({ html:true, title: "Apakah Anda Yakin?", text: 'Cetak Order', type: "warning",
//       showCloseButton: true, showCancelButton: true,
//       confirmButtonText:'Yakin', cancelButtonText:'Tidak',
//   },
//   function(){
//     $.ajax({
//       type: "POST",
//       url : 'order/cetak_struk',
//       data: "id="+id,
//       dataType: "json",
//       beforeSend: function(){
//         loading_show();
//       },
//       success: function( data ) {
//         loading_close();
//         if (data.stt==1){
//           setTimeout(function(){ loading_close(); swal({ html:true, title : "Success", text : '', type : "success", showConfirmButton: false, allowEscapeKey: false }); }, 50);
//         }else{
//           setTimeout(function(){ loading_close(); swal({ html:true, title : "Gagal", text : data.pesan, type : "error", showConfirmButton: false, allowEscapeKey: false }); }, 50);
//         }
//         setTimeout(function(){ swal.close(); }, (3*1000)+50);
//       },
//       error: function(){
//         setTimeout(function(){ loading_close(); swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" }) }, 50);
//         setTimeout(function(){ swal.close(); }, (3*1000)+50);
//       }
//     });
//   });
// }
</script>
