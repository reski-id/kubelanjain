<script type="text/javascript">
dataHeadnya();
function show_data() {
  $.ajax({
      type: "POST",
      url : 'belanja/list_data',
      data: "tgl="+$("[name='cari_tgl']").val()+"&cari="+$('#cari_ordernya').val()+"&limit="+$('#vlist_order :selected').val(),
      dataType: "json",
      beforeSend: function(){
        $("#dataBodynya").html('\
        <tr style="background:#b9d1ff;color:#222;">\
          <td class="text-center" colspan="5">Mencari . . .</td>\
        </tr>');
        $("#dataBodynya").fadeIn("slow");
        $("#dataFootnya").html('');
      },
      success: function( param ) {
        $("#dataBodynya").html('');
        $("#dataFootnya").html('');

        AmbilData = param.detailnya;
        no=0; grand_total=0;
        $.each(AmbilData, function(index, loaddata) {
          no++;
          dataBodynya(no, loaddata);
          grand_total += loaddata.total_harga;
        });
        if (no==0) {
          $("#dataBodynya").html('\
          <tr style="background:pink;color:red;">\
            <td class="text-center" colspan="5">Item tidak ditemukan!</td>\
          </tr>');
        }else {
          dataFootnya(grand_total);
        }
      },
      error: function(){
        $("#dataBodynya").html('');
        $("#dataFootnya").html('');
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}

function dataHeadnya()
{
  $('#dataHeadnya').append('\
    <tr>\
      <th class="text-center" width="1%">#</th>\
      <th class="text-center" width="44%">Nama&nbsp;Item</th>\
      <th class="text-center" width="20%">Harga</th>\
      <th class="text-center" width="15%">SATUAN</th>\
      <th class="text-center" width="20%">TOTAL</th>\
    </tr>');
}

function dataBodynya(no=1, data)
{
  $("#dataBodynya").append('\
  <tr>\
    <td><label>'+no+'</label></td>\
    <td><label>'+data.nama_item+'</label></td>\
    <td class="text-right"><label>'+addCommas(data.harga_satuan)+'</label></td>\
    <td class="text-right"><label>'+addCommas(data.satuan)+'</label></td>\
    <td class="text-right"><label>'+addCommas(data.total_harga)+'</label></td>\
  </tr>');
}

function dataFootnya(grand_total=0)
{
  $("#dataFootnya").append('\
  <tr>\
    <td colspan="4" class="text-right"><label style="font-size:16px"><b>GRAND&nbsp;TOTAL</b></label></td>\
    <td class="text-right p-1"><label style="font-size:16px"><b>'+addCommas(grand_total)+'</b></label></td>\
  </tr>');
}
</script>
