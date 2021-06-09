<?php $level=get_session('level'); ?>
<script type="text/javascript">
$(document).ready(function () {
  RefreshTable();
});

function RefreshTable() {
  dataHeadnya('');

  $.ajax({
      type: "POST",
      url : 'report/ajax_report/payment',
      data: "id_kota="+$('#v_sel_kota option:selected').val()+'&tgl_dari='+$('#tgl_dari').val()+'&tgl_sampai='+$('#tgl_sampai').val(),
      dataType: "json",
      beforeSend: function(){
        // loading_show();
        $("#dataBodynya").html('\
        <tr style="background:#b9d1ff;color:#222;">\
          <td class="text-center" colspan="6">Mencari . . .</td>\
        </tr>\
        ');
        $("#dataFootnya").html('');
      },
      success: function( param ) {
        $("#dataBodynya").html('');
        $("#dataFootnya").html('');

        AmbilData = param.detailnya;
        var total=0; no=0;
        $.each(AmbilData, function(index, loaddata) {
          no++;
          val = loaddata.nominal_pembayaran;
          dataBodynya(no, loaddata.no_transaksi, loaddata.nama_lengkap, loaddata.no_hp, loaddata.tanggal, val);
          total  += parseFloat(val);
        });

        dataFootnya(total);

        // JIKA TIDAK ADA DATA-NYA
        if (no==0) {
          $('#h_check_all').hide();
          pesanX = 'Data belum ada!';
          $("#dataBodynya").html('\
          <tr style="background:pink;color:red;">\
            <td class="text-center" colspan="6">'+pesanX+'</td>\
          </tr>\
          ');
          $("#dataFootnya").html('');
        }else {
          $('#h_check_all').show();
        }
        $('#none').html(no);

      },
      error: function(){
        $("#dataBodynya").html('');
        $("#dataFootnya").html('');
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}


function dataHeadnya(stt='')
{
  $('#dataHeadnya').empty();
  $('#dataHeadnya').append('\
    <tr>\
      <th width="1%" class="text-center">No.</th>\
      <th width="10%" class="text-center">TANGGAL&nbsp;PAYMENT</th>\
      <th width="10%" class="text-center">ID&nbsp;ORDER</th>\
      <th width="40%" class="text-center">NAMA</th>\
      <th width="10%" class="text-center">NO&nbsp;HP</th>\
      <th width="29%" class="text-center">VALUE</th>\
    </tr>');
}

function dataBodynya(no='', id_order='', nama='', no_hp='', tanggal='', val='')
{
  $("#dataBodynya").append('\
  <tr>\
    <td class="text-center">'+no+'</td>\
    <td>'+tanggal+'</td>\
    <td>'+id_order+'</td>\
    <td>'+nama+'</td>\
    <td>'+no_hp+'</td>\
    <td class="text-right">'+addCommas(val)+'</td>\
  </tr>\
  ');
}

function dataFootnya(total=0)
{
  $("#dataFootnya").append('\
  <tr>\
    <th class="text-center" colspan="5">TOTAL</th>\
    <th class="text-right p-1">'+addCommas(total)+'</th>\
  </tr>\
  ');
}

</script>
