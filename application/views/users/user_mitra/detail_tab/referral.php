<?php $nama_X = '';
if (!empty($query['id_user'])) {
  $level   = $query['level'];
  $id_user = $query['id_user'];
}else {
  redirect('404');
}
$tbl = 'user_biodata';
if ($level==1) {
  $tbl .= '_reseller';
  $nama_X = 'Reseller';
  $id_m = get_no_mitra($id_user);

  $this->db->select('a.id_user');
  $this->db->join('user as b', "a.id_user=b.id_user");
  $t_reseller = get("$tbl as a", array('a.id_referal'=>$id_m, 'b.status'=>'1'))->num_rows();

  $this->db->select('a.id_user');
  $this->db->join('user as b', "a.id_user=b.id_user");
  $this->db->join('order as c', "a.id_user=c.id_user");
  $this->db->group_by('id_user');
  // $this->db->where("jenis_kelamin='' or jenis_kelamin is null");
  $t_SO = get("$tbl as a", array('a.id_referal'=>$id_m, 'b.status'=>'1'))->num_rows();

  $t_BO = $t_reseller-$t_SO;
  ?>
  <div class="col-12 <?php if(uri(1)=='user_reseller' AND uri(2)=='vlist'){ echo "pt-1"; } ?>" id="vT_Order">
    <div class="row">
      <?php
      $datanya_ORDER[] = array('bg'=>'info', 'nama'=>'Total '.$nama_X, 'value'=>format_angka($t_reseller));
      $datanya_ORDER[] = array('bg'=>'danger', 'nama'=>'Belum Order', 'value'=>format_angka($t_BO));
      $datanya_ORDER[] = array('bg'=>'success', 'nama'=>'Sudah Order', 'value'=>format_angka($t_SO));
      ?>
      <?php foreach ($datanya_ORDER as $key => $value): ?>
        <div class="col-sm-4 col-12 dashboard-users-success">
            <div class="card text-center bg-<?= $value['bg']; ?>">
                <div class="card-content">
                    <div class="card-body py-1">
                        <label class="white" style="font-size:20px;"><?= $value['nama'] ?></label>
                        <h4 class="text-muted line-ellipsis white"><?= $value['value'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php
} ?>
<div class="table-responsive" style="margin-top:-10px !important">
  <table id="fileData_referral" class="table table-bordered table-striped table-hover" width="100%">
    <thead>
      <tr>
        <th width="1%">#</th>
        <th>ID</th>
        <th width="25%">Nama&nbsp;Lengkap</th>
        <th width="10%">ID&nbsp;<span id="nm_ID"></span></th>
        <th width="20%">Provinsi</th>
        <th width="20%">Kab/Kota</th>
        <th width="10%">No&nbsp;HP</th>
        <th width="10%" id="H_status">Status&nbsp;Order</th>
        <th width="5%">Fee</th>
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
    RefreshTableX(<?php if(uri(1)=='user_reseller'){ echo "1"; }else{ echo "0"; } ?>);
  <?php }else{ ?>
    RefreshTableX(1);
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

function RefreshTableX(status='') {
  stt_opsi = '';
  if (status==1) {
    status='reseller';
    if($('#vT_Order').length!=''){ $('#vT_Order').show() };
    if($('#H_status').length!=''){ $('#H_status').html('Status&nbsp;Order') };
    // if($('#H_status').length!=''){ $('#H_status').attr('hidden', true); };
    // if($('#H_status').length!=''){ $('#H_status').removeAttr('hidden'); $('#H_status').removeClass('hidden'); };
    // $('#nm_ID').html('RESELLER');
  }else {
    status='mitra';
    if($('#vT_Order').length!=''){ $('#vT_Order').hide() };
    if($('#H_status').length!=''){ $('#H_status').html('Reseller') };
    // stt_opsi = 'hidden';
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
      $('#fileData_referral').dataTable().fnDestroy();
  if (fnServerObjectToArray) {
    var oTable_Referral = $("#fileData_referral").dataTable({
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
        v_cek_user_order();
      },
      "columns": [
        {"data": null},
        {"data": "id"},
        {"data": "nama_lengkap"},
        {"data": "id_mitra"},
        {"data": "provinsi"},
        {"data": "kota"},
        {"data": "no_hp"},
        {"data": "id_user"},
        {"data": "fee_master"},
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
          className: "text-center align-top", "targets": [8],
          render: function( data, type, row ){
            return addCommas(data)
          },
        },
        {
          className: "text-center align-top "+stt_opsi, "targets": [7],
          render: function( data, type, row ){
            if (status=='reseller') {
              return '<div class="get_data_usernya" id="cek_order_'+data+'"></div>';
            }else {
              idnya = row.id_x;
              detailX  = "aksi('detail','"+idnya+"','','<?= base_url("user_mitra/view_data/mitra/referal"); ?>')";
              btn_aksi = '<a href="javascript:'+detailX+'" class="btn btn-icon rounded-circle glow btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="Detail Referral"><i class="bx bxs-user-voice"></i></a>';
              return btn_aksi;
            }
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

function v_cek_user_order(){
  $('.get_data_usernya').each(function(i, obj) {
    var val = $(this).attr('id');
    var v_status = $('#'+val);
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('user_mitra/cek_user_order'); ?>",
      data: "id="+val,
      dataType: "json",
      beforeSend: function(){
        v_status.html('Cek Order . . .');
      },
      success: function( data ) {
        v_status.html('<label class="badge badge-'+data.bg+'">'+data.nama+' ORDER</label>')
      },
      error: function(){
        v_status.html('<b>-</b>');
      }
    });
  });
}
</script>
