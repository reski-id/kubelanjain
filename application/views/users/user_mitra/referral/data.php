<div class="table-responsive">
  <table id="fileData_list_referral" class="table table-bordered table-striped table-hover" width="100%">
    <thead>
      <tr>
        <th width="1%">#</th>
        <th>ID</th>
        <th width="25%">Nama&nbsp;Lengkap</th>
        <th width="10%">ID&nbsp;<span id="nm_ID"></span></th>
        <th width="20%">Provinsi</th>
        <th width="20%">Kab/Kota</th>
        <th width="10%">No&nbsp;HP</th>
        <th width="14%">OPSI</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>


<!-- Jquery Core Js -->
<script type="text/javascript">
var oTable_Referral;
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
              "error": handleAjaxError_Referral
          });
        }
    }

$(document).ready(function () {
  <?php if ($query['type_id']==1) { ?>
    RefreshTable(<?php if(uri(1)=='user_reseller'){ echo "1"; }else{ echo "0"; } ?>);
  <?php }else{ ?>
    RefreshTable(1);
  <?php } ?>
});

function handleAjaxError_Referral(xhr, textStatus, error) {
    if (textStatus === 'timeout') {
        alert('The server took too long to send the data.');
    }
    else {
        alert('An error occurred on the server. Please try again in a minute.');
        // window.location.reload();
    }
    oTable_Referral.fnProcessingIndicator(false);
}

function RefreshTable(status='') {
  if (status==1) {
    status='reseller';
    // $('#nm_ID').html('RESELLER');
  }else {
    status='mitra';
    // $('#nm_ID').html('MITRA');
  }
  loading_show();
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

  dataSource = '<?= base_url("user_mitra/list_referral/".$query['id_mitra']."/".$query['type_id']) ?>/'+status;
  if (oTable_Referral)
      oTable_Referral.fnDestroy();
      $('#fileData_list_referral').dataTable().fnDestroy();
  if (fnServerObjectToArray) {
    var oTable_Referral = $("#fileData_list_referral").dataTable({
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
        loading_hide();
      },
      "columns": [
        {"data": null},
        {"data": "id"},
        {"data": "nama_lengkap"},
        {"data": "id_mitra"},
        {"data": "provinsi"},
        {"data": "kota"},
        {"data": "no_hp"},
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
          className: "text-left align-top", "targets": [2],
          render: function( data, type, row ){
            st=''; jk=row.jenis_kelamin;
            if (jk=='Laki - Laki') {
              st = 'Bpk ';
            }else if (jk=='Perempuan') {
              st = 'Ibu ';
            }
            return st+data;
          },
        },
        {
          className: "text-left align-top", "targets": [3,4,5,6],
          render: function( data, type, row ){
            return data
          },
        },
        {
          "searchable": false, "orderable": false, className: "text-center align-top", "targets": 7,
          render: function(data, type, row) {
            idnya = data.id_x;
            detailX  = "aksi('detail','"+idnya+"','','<?= base_url("user_mitra/view_data/mitra/referal"); ?>')";
            btn_aksi = '<a href="javascript:'+detailX+'" class="btn btn-icon rounded-circle glow btn-secondary" data-toggle="tooltip" data-placement="top" title="Detail Referral"><i class="bx bxs-user-voice"></i></a>&nbsp;&nbsp;';
            <?php if (get_session('type_id')==1) { ?>
              detail = "aksi('edit','"+idnya+"','sync','<?= base_url("user_mitra/view_data/mitra/referal/edit_fee"); ?>')";
              btn_aksi += '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-primary" data-toggle="tooltip" data-placement="top" title="Detail"><i class="bx bx-money"></i></a>';
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
</script>

<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md" id="lebar_modalnya">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal_judul"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
      </div>
      <div id="modal_datanya" class="p-1">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
