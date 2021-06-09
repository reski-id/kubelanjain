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
        {"data": "username"},
        {"data": "no_hp"},
        {"data": "gudang"},
        {"data": "status"},
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
          className: "text-left", "targets": [2,3,4],
          render: function( data, type, row ){
            return data
          },
        },
        {
          className: "text-left", "targets": [5],
          render: function( data, type, row ){
            if (data==1) {
              return '<label class="badge badge-success">Aktif</label>';
            }else {
              return '<label class="badge badge-danger">Tidak Aktif</label>';
            }
          },
        },
        {
          "searchable": false, "orderable": false, className: "text-center", "targets": 6,
          render: function(data, type, row) {
            idnya = data.id_x;
            detail = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>')";
            // hapus = "aksi('hapus','"+idnya+"','','<?= $url_hapus; ?>')";
            btn_aksi  = '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-info" data-toggle="tooltip" data-placement="top" title="Detail"><i class="bx bx-file"></i></a>&nbsp;';
            <?php if (check_permission('view', 'update', "user_management/data_user")){ ?>
              edit  = "aksi('edit','"+idnya+"','sync_form','<?= $url_modal; ?>')";
              btn_aksi += '<a href="javascript:'+edit+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil"></i></a>&nbsp;';
            <?php } ?>
            <?php if (check_permission('view', 'approve', "user_management/data_user")){ ?>
              btn_aksi += '<a href="user_management/approval/'+idnya+'" target="_blank" class="btn btn-icon rounded-circle glow btn-primary" data-toggle="tooltip" data-placement="top" title="Management Approval"><i class="bx bxs-user-check"></i></a>&nbsp;';
            <?php } ?>
            btn_aksi += '<a href="user_management/menu/'+idnya+'" target="_blank" class="btn btn-icon rounded-circle glow btn-secondary" data-toggle="tooltip" data-placement="top" title="Management Menu"><i class="bx bx-menu"></i></a>&nbsp;';
            // if (data.status==1) {
            //   btn_aksi += '<a href="javascript:'+hapus+'" class="btn btn-icon rounded-circle glow btn-danger" data-toggle="tooltip" data-placement="top" title="Ubah menjadi tidak Aktif"><i class="bx bx-x"></i></a>';
            // }else {
            //   btn_aksi += '<a href="javascript:'+hapus+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="bx bx-check"></i></a>';
            // }
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
</script>
