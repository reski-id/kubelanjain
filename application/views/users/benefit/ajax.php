<?php
$id_user = get_session('id_user');
$level   = get_session('level');
$get_ID  = encode($id_user);
if (!empty($_GET['p'])) {
  if ($level==0) {
    $get_ID = $_GET['p'];
  }
}
?>
<script type="text/javascript">
// $('.pickadate').pickadate();
function DMYtoYMD(tglnya='')
{
  if(tglnya==''){ return ''; }
  var parts = tglnya.split('-');
  return parts[2]+'-'+parts[1]+'-'+parts[0];
}

function RefreshTable() {
  loading_show();
  $("#dataSaldo").empty(); $("#totalSaldo").empty();
  tgl_1 = DMYtoYMD($('.tgl_dari').val());
  tgl_2 = DMYtoYMD($('.tgl_sampai').val());
  if (tgl_1 > tgl_2) {
    loading_close();
    swal({ title : "", text : "Dari Tanggal tidak boleh melebihi Sampai Tanggal!", type : "warning" });
    return false;
  }
  get_transaksi_awal(tgl_1, tgl_2);
}

function get_transaksi_awal(tgl_1='', tgl_2='')
{
  $.ajax({
      type: "POST",
      url : 'users/ajax_detail_benefit/awal',
      data: 'id=<?= $get_ID; ?>&tgl1='+tgl_1+'&tgl2='+tgl_2,
      dataType: "json",
      beforeSend: function(){
        // loading_show();
      },
      success: function( param ) {
        if (param.stt==1) {
          Rpnya = 'Rp.&nbsp;';
          // Rpnya = '';
          <?php if(view_mobile()){ ?>
            Rpnya = '';
          <?php } ?>
          <?php if($level==0){ ?>
            // Rpnya = '';
          <?php } ?>

          AmbilData = param.detailnya;
          saldo = param.saldo;
          $("#dataSaldo").append('<input type="hidden" id="saldo1" value="'+saldo+'" />');
          $.each(AmbilData, function(index, loaddata) {
            no_transaksi = loaddata.no_transaksi;
            tgl_input = loaddata.tgl_input;
            ket = loaddata.ket;
            trans_in = loaddata.trans_in;
            trans_out = loaddata.trans_out;
            v_transaksi_awal(2,loaddata.no_transaksi, loaddata.tgl_input, loaddata.ket, loaddata.trans_in, loaddata.trans_out, saldo);
          });
          get_view_transaksi(tgl_1, tgl_2);
        }else {
          swal({ title : "Permission Denied!", text : "", type : "warning" });
        }
      },
      error: function(){
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}

function get_view_transaksi(tgl_1='', tgl_2='')
{
  $.ajax({
      type: "POST",
      url : 'users/ajax_detail_benefit',
      data: 'id=<?= $get_ID; ?>&tgl1='+tgl_1+'&tgl2='+tgl_2,
      dataType: "json",
      beforeSend: function(){
        // loading_show();
      },
      success: function( param ) {
        if (param.stt==1) {
          AmbilData = param.detailnya;
          var ii=2;   var jj=1;
          var i=2;   var j=1;
          var saldo=0;
          var t_out=0; var t_in=0; no=0; saldo_akhir=0; t_benefit=0;
          $.each(AmbilData, function(index, loaddata) {
             stt = 1; no++;
             trans_admin = 0;
              Rpnya = 'Rp.&nbsp;';
              // Rpnya = '';
              <?php if(view_mobile()){ ?>
                Rpnya = '';
              <?php } ?>
              <?php if($level==0){ ?>
                // Rpnya = '';
              <?php } ?>

              cBg=''; cText='';
              no_transaksi = loaddata.no_transaksi;
              tgl_input = loaddata.tgl_input;
              ket_id    = loaddata.ket_id;
              ket       = loaddata.ket;
              trans_in  = loaddata.trans_in;
              trans_out = loaddata.trans_out;
              tanggal   = DMYtoYMD(loaddata.tanggal);
              var strKet = ket;
              if (ket_id=='bayar') {
                cBg = '#ffcfcf';
                cText = 'black';
              }
              get_saldo=parseFloat($("#saldo"+j).val());
              var saldo = carisaldo(get_saldo,parseFloat(trans_in),parseFloat(trans_out));
              t_in  += parseFloat(trans_in);
              t_out += parseFloat(trans_out);
              saldo_akhir = saldo;
              v_transaksi_detail(no_transaksi, tgl_input, ket, ket_id, trans_in, trans_out, saldo, i, cBg, cText, tanggal)
              i++; j++;
              // t_benefit += saldo;
          });

          $('#t_benefit').html(addCommas(param.total));
          if (no==0) {
            Detail_lenght = 6;
            $("#dataSaldo").append('\
            <tr>\
              <td colspan="'+Detail_lenght+'" class="text-center" style="background:pink;color:red;">Belum ada Transaksi</td>\
            </tr>');
          }
          <?php if($level==2){ ?>
            if (saldo_akhir==0) { $('#tmb_trans').attr('hidden', true); }
          <?php } ?>
        }else{
          swal({ title : "Permission Denied!", text : "", type : "warning" });
        }
        loading_close();
      },
      error: function(){
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}

function carisaldo(saldo,saldo_in,saldo_out){
  hasil = parseFloat((saldo + saldo_in) - saldo_out);
  return hasil;
}

function v_transaksi_awal(no_transaksi='', tgl_input='', ket='', ket_id='', trans_in='', trans_out='', saldo='')
{
  v_not_admin=''; v_data='';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(trans_in)+'</td>';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(trans_out)+'</td>';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(saldo)+'</td>';
  $("#dataSaldo").append('\
  <tr hidden>\
    <td class="align-top text-center">'+no_transaksi+'</td>\
    <td class="align-top text-center">'+tgl_id(tgl_input)+'</td>\
    <td class="align-top">'+ket+'</td>'+v_data+'\
  </tr>');
}

function v_transaksi_detail(no_transaksi='', tgl_input='', ket='', ket_id='', trans_in='', trans_out='', saldo=0, i='', cBg='', cText='', tanggal='')
{
  v_data=''; v_saldo='';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(trans_in)+'</td>';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(trans_out)+'</td>';
  v_data += '<td class="align-top text-right"><span class="float-left">'+Rpnya+'</span>'+addCommas(saldo)+'</td>';
  v_saldo = '<input type="hidden" id="saldo'+i+'" value="'+saldo+'" />';
  tgl_input = tgl_input.substr(11, 8);
  tgl = '';
  if ($('#batas_tgl_'+tanggal).length==0) {
    tgl = '\
    <tr style="background:#70a7ff;color:#eee;">\
      <td class="text-left" id="batas_tgl_'+tanggal+'" style="padding:5px !important" colspan="<?php if(view_mobile()){ echo "2"; }else{ echo "6"; } ?>">'+tanggal+'</td>\
    </tr>';
  }
  <?php if(view_mobile()){ ?>
    detail = '<b>'+no_transaksi+'</b><br />'+ket+' '+v_saldo;
    if (trans_out == 0) { set_STT='+'; set_COLOR='green'; transnya=trans_in; }
    if (trans_in  == 0) { set_STT='-'; set_COLOR='red'; transnya=trans_out; }
    $("#dataSaldo").append(tgl+'\
    <tr style="background:'+cBg+';color:'+cText+'">\
      <td class="align-top text-left" width="60%">'+detail+'</td>\
      <td class="align-top text-right" width="40%" style="color:'+set_COLOR+'">'+set_STT+' '+addCommas(transnya)+'</td>\
    </tr>');
  <?php }else{ ?>
    $("#dataSaldo").append(tgl+'\
    <tr style="background:'+cBg+';color:'+cText+'">\
      <td class="align-top text-center">'+no_transaksi+'</td>\
      <td class="align-top text-center">'+tgl_input+'</td>\
      <td class="align-top">'+ket+''+v_saldo+'</td>'+v_data+'\
    </tr>');
  <?php } ?>
}
</script>
