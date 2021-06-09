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
  if (status==0) {
    status=1;
  }else {
    status=0;
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

  dataSource = '<?= base_url("master/list_data/$tbl") ?>/'+status+'/<?= uri(3); ?>';
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
        {"data": "nama_item"},
        {"data": "harga_beli", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
        {"data": "harga", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
        {"data": "gap", render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' )},
        {"data": null},
      ],
      "aaSorting": [[2, 'asc']],
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
          className: "text-left align-top", "targets": [2, 3, 4, 5],
          render: function( data, type, row ){
            return data
          },
        },
        {
          "searchable": false, "orderable": false, className: "text-center align-top", "targets": 6,
          render: function(data, type, row) {
            idnya  = data.id_x;
            detail = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>', 'lg')";
            btn_aksi  = '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-info" data-toggle="tooltip" data-placement="top" title="Detail"><i class="bx bx-file"></i></a>&nbsp;';
            <?php if (check_permission('view', 'update', "master/harga")) { ?>
              if (data.status==1) {
                edit   = "aksi('edit','"+idnya+"','sync_form','<?= $url_modal; ?>', 'md')";
                btn_aksi += '<a href="javascript:'+edit+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil"></i></a>&nbsp;';
              }
            <?php } ?>
            <?php if (check_permission('view', 'delete', "master/harga")) { ?>
              if (data.status==1) {
                hapus     = "btn_status('"+idnya+"', 0, 'Ubah Status ke Tidak Aktif')";
                btn_aksi += '<a href="javascript:'+hapus+'" class="btn btn-icon rounded-circle glow btn-danger" data-toggle="tooltip" data-placement="top" title="Tidak Aktif"><i class="bx bx-x"></i></a>';
              }else {
                hapus     = "btn_status('"+idnya+"', 1, 'Ubah Status ke Aktif')";
                btn_aksi += '<a href="javascript:'+hapus+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="bx bx-check"></i></a>';
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
    hapus_data('master/simpan/harga_up_status/'+id, stt, '5', 1);
  });
}
</script>
