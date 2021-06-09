<style>
<?php if (view_mobile()){ ?>
  .app-content .content-wrapper{
    padding: 0px !important;
    padding-top: 10px !important;
  }
  .card .card-header .card-title {
      margin-bottom: -10px;
      margin-top: 10px;
  }
<?php } ?>
</style>
<form action="javascript:cari_user_benefit()" method="post">
  <div class="row">
    <div class="col-12 col-md-10">
      <input type="search" name="cari" class="form-control" value="" placeholder="Cari Nama, Nomor Handphone & ID MITRA . . ." autofocus>
    </div>
    <div class="col-12 col-md-2">
      <button type="submit" name="btncari" class="btn btn-primary btn-block"><i class="bx bx-search"></i> Cari</button>
    </div>
  </div>
</form>

<div class="list_user_benefit"></div>

<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="modal_judul"></h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
      </div>
      <div id="modal_datanya">

      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
function cari_user_benefit()
{
  view = $('.list_user_benefit');
  cari = $('[name="cari"]');
  if (cari.val()=='') {
    view.css('color', 'red');
    view.html('<small>*Wajib diisi!</small>');
    cari.focus();
  }else if (cari.val().length < 3) {
    view.css('color', 'red');
    view.html('<small>*Minimal 3 karakter!</small>');
    cari.focus();
  }else {
    $.ajax({
      type: "POST",
      url : 'users/ajax_detail_pembayaran_benefit',
      data: 'p='+cari.val(),
      dataType: "json",
      beforeSend: function(){
        loading_show();
      },
      success: function( param ) {
        if (param.stt==1) {
          btn_show=false; h_view='';
          <?php if(check_permission('view', 'create', 'users/pembayaran_benefit')){ ?>
            btn_show=true; h_view='<th>Bayar</th>';
          <?php } ?>
          view.css('color', '');
          view.html('<br /><div id="data_total"></div>\
          <div class="table-responsive" id="list_user_benefit">\
            <table class="table table-fixed table-bordered table-striped table-hover" width="100%">\
              <thead class="thead-dark">\
                <tr> <th>Keterangan</th> '+h_view+' </tr>\
              </thead>\
              <tbody id="data_user_benefit"></tbody>\
            </table>\
          </div>');
          i=0; t_m1=0; t_m2=0;
          AmbilData = param.detailnya;
          $.each(AmbilData, function(index, loaddata) {
            i++; ke=loaddata.ke; nama=loaddata.nama_lengkap; no_hp=loaddata.no_hp; id_mitra=loaddata.id_mitra;
            if (ke==1) { t_m1++; }else{ t_m2++; }
            detail = "aksi('detail','"+loaddata.id+"','','<?= base_url("users/view_detail_pembayaran_benefit"); ?>','md')";
            btn_aksi = '<a href="javascript:'+detail+'" class="btn btn-icon rounded-circle glow btn-success" data-toggle="tooltip" data-placement="top" title="Pembayaran"><i class="bx bxs-wallet"></i></a>';
            if (btn_show) {
              b_view = '<td width="1%" class="text-center">'+btn_aksi+'</td>';
            }else{ b_view=''; }
            $("#data_user_benefit").append('\
            <tr>\
              <td><b>Mitra '+ke+'</b> - <b>'+id_mitra+'</b><br />'+nama+'<br />'+no_hp+'</td>\
              '+b_view+'\
            </tr>');
          });
          if (i==0) {
            view.css('color', 'red');
            datanya = "Pencarian '<b>"+cari.val()+"</b>' tidak ditemukan!";
            view.html(datanya);
          }else {
            $("#data_total").append('<b>Total Mitra I :</b> '+addCommas(t_m1)+' &nbsp; <b>Total Mitra II :</b> '+addCommas(t_m2));
          }
        }else {
          swal({ title : "Permission Denied!", text : "", type : "warning" });
        }
        loading_close();
      },
      error: function(){
        loading_close();
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
  }
}
</script>
