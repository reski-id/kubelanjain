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

// $(document).ready(function () {
//   RefreshTable();
// });

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
  judul = '';
  if (status=='') {
    status = $('.nav-item > a.active').attr('aria-controls');
  }
  stt_mitra = $('#id_mitrax :selected').val();
  stt = $('#id_provinsix :selected').val();
  if (stt=='') {
    swal({ html:true, type: "warning", title: "Peringatan!", text: 'Provinsi Belum dipilih!' });
    $('#judul_form_card').html('<?= $judul_web; ?>');
    $('#tabel_data').attr('hidden', true);
    return false;
  }else {
    judul += '<b>Provinsi: </b>';
    if (stt==0) {
      judul += 'Semua';
    }else {
      judul += $('#id_provinsix :selected').text();
    }
    $('#tabel_data').removeAttr('hidden');
  }

  stt_kota = $('#id_kotax :selected').val();
  if (stt_kota=='') {
    swal({ html:true, type: "warning", title: "Peringatan!", text: 'Kota Belum dipilih!' });
    $('#judul_form_card').html('<?= $judul_web; ?>');
    $('#tabel_data').attr('hidden', true);
    return false;
  }else {
    judul += ' &nbsp; <b>Kab/Kota: </b>';
    if (stt_kota==0) {
      judul += 'Semua';
    }else {
      judul += $('#id_kotax :selected').text();
    }
  }

  $('#judul_form_card').html(judul);
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

  dataSource = '<?= base_url("$url/list_data/$tbl") ?>/'+stt+'/'+stt_kota+'/'+stt_mitra+'/'+status;
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
        loading_hide();
      },
      "columns": [
        {"data": null},
        {"data": "id"},
        {"data": "nama_lengkap"},
        {"data": "id_mitra"},
        {"data": "no_hp"},
        {"data": "provinsi"},
        {"data": "kota"},
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
            btn_aksi  = '<a href="users/benefit?p='+idnya+'" target="_blank" class="btn btn-icon rounded-circle glow btn-warning mb-1" data-toggle="tooltip" data-placement="top" title="Detail Benefit"><i class="bx bxs-credit-card"></i></a>&nbsp;';
            detailX   = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>/referal')";
            btn_aksi += '<a href="javascript:'+detailX+'" class="btn btn-icon rounded-circle glow btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="Detail Referral"><i class="bx bxs-user-voice"></i></a>&nbsp;';
            detail    = "aksi('detail','"+idnya+"','','<?= $url_modal; ?>')";
            btn_aksi += '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-info mb-1" data-toggle="tooltip" data-placement="top" title="Detail"><i class="bx bx-file"></i></a>&nbsp;';
            if (data.status=='0') { //jika Non-Active
              edit  = "aksi('hapus','"+idnya+"','','<?= $url_hapus; ?>/aktifkan')";
              btn_aksi += '<a href="javascript:'+edit+'" class="btn btn-icon rounded-circle glow btn-success mb-1" data-toggle="tooltip" data-placement="top" title="Aktifkan"><i class="bx bxs-user-check"></i></a>&nbsp;';
            }else {
              <?php if (uri(3)=='2') { ?>
                if (status==1) {
                  detail_fee = "aksi('edit','"+idnya+"','sync','<?= base_url("user_mitra/view_data/mitra/referal/edit_fee"); ?>')";
                  btn_aksi += '<a href="javascript:'+detail_fee+'" class="btn btn-icon rounded-circle glow btn-primary mb-1" data-toggle="tooltip" data-placement="top" title="Detail Fee"><i class="bx bx-money"></i></a>&nbsp;';
                }
              <?php } ?>
              <?php if (check_permission('view', 'update', 'user_mitra/v/'.uri(3))) { ?>
                edit  = "aksi('edit','"+idnya+"','sync_form','<?= $url_modal; ?>')";
                btn_aksi += '<a href="javascript:'+edit+'" class="btn btn-icon rounded-circle glow btn-success mb-1" data-toggle="tooltip" data-placement="top" title="Edit"><i class="bx bx-pencil"></i></a>&nbsp;';
              <?php } ?>
              <?php if (check_permission('view', 'delete', 'user_mitra/v/'.uri(3))) { ?>
                hapus = "aksi('hapus','"+idnya+"','','<?= $url_hapus; ?>/oKe/<?= uri(3); ?>')";
                btn_aksi += '<a href="javascript:'+hapus+'" class="btn btn-icon rounded-circle glow btn-danger mb-1" data-toggle="tooltip" data-placement="top" title="Ubah menjadi Tidak Aktif"><i class="bx bxs-user-x"></i></a>';
              <?php } ?>
            }
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
