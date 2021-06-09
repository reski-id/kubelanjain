<form id="sync_form" action="javascript:simpan_datanya();" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body row">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'required onchange="show_kota()"', 'value'=>'', 'col'=>6);
  $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'required disabled onchange="show_kecamatan()"', 'value'=>'', 'col'=>6);
  $datanya[] = array('type'=>'select', 'name'=>'id_kecamatan', 'nama'=>'Kecamatan','icon'=>'-','html'=>'required disabled onchange="show_pasar()"', 'value'=>'', 'col'=>6);
  $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'required disabled onchange="show_item_kat();"', 'value'=>'', 'col'=>6);
  $datanya[] = array('type'=>'select', 'name'=>'id_item_kategori', 'nama'=>'Kategori','icon'=>'-','html'=>'required disabled onchange="cek_item();"', 'value'=>'', 'col'=>6);
  data_formnya($datanya);
  $checked='';
  if ($id!='') {
    $this->db->select('id_item_master');
    foreach (get('item_lokasi_detail', array('status'=>1, 'id_item_lokasi'=>$query['id_item_lokasi']))->result() as $key => $value) {
      $checked .= $value->id_item_master.',';
    }
    if ($checked!='') { $checked = substr($checked, 0, -1); }
  }
  ?>
  <input type="hidden" name="checked_box" value="[<?= $checked; ?>]">
  <input type="hidden" id="total_check" value="0">
  <div class="col-md-12" id="tabel_itemnya" hidden>
    <div class="row">
      <div class="col-md-12">
        <!-- <label>ITEM</label> -->
        <input type="text" class="form-control" id="cari_ordernya" placeholder="Cari PLU, NAMA ITEM . . ." style="border-radius:0px;border-top-left-radius: 10px;border-top-right-radius: 10px;">
      </div>
      <div class="col-md-12 <?php if(!view_mobile()){ echo "table-responsive"; } ?>">
        <table id="tabelnya" class="table table-fixed <?php if(!view_mobile()){ echo "table-bordered"; } ?> table-striped table-hover scroll" width="100%">
          <thead id="dataHeadnya" class="thead-dark"></thead>
          <tbody id="dataBodynya"></tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-12">
    <label>Total Item : <span id="vtotal_check">0</span></label>
    &nbsp;&nbsp;&nbsp;
    <label class="text-success">Dipilih : <span id="vtotal_check_select">0</span></label>
    &nbsp;&nbsp;&nbsp;
    <label class="text-danger">Tidak Dipilih : <span id="vtotal_check_unselect">0</span></label>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
</div>
</form>

<style>
.checkbox label:after {
    background: #fff;
}
#tabelnya tbody th, tbody td {
    padding: 5px !important;
}
</style>
<?php view('plugin/parsley/custom'); ?>
<?php view('plugin/style/table_scroll'); ?>
<script type="text/javascript">
 $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

$(document).ready(function(){
  $("#cari_ordernya").on("keyup", function() {
    // var value = $(this).val().toLowerCase();
    // get_list_item('cari');
    searchTable();
  });
});

function searchTable() {
    var input;
    var saring;
    var status;
    var tbody;
    var tr;
    var td;
    var i;
    var j;
    input = document.getElementById("cari_ordernya");
    saring = input.value.toUpperCase();
    tbody = document.getElementById("dataBodynya");
    tr = tbody.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j].innerHTML.toUpperCase().indexOf(saring) > -1) {
                status = true;
            }
        }
        if (status) {
            tr[i].style.display = "";
            status = false;
        } else {
            tr[i].style.display = "none";
        }
    }
    save_checked();
}

show_provinsi();
function show_provinsi()
{
  $('[name="id_provinsi"]').empty();
  $('[name="id_provinsi"]').append('<option value=""> - Pilih Provinsi - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_prov",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_provinsi"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            // form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              // form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
            }, 1000);
            <?php }else{ ?>
            // reset_select2nya("[name='id_provinsi']", '21', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_kota()
{
  kota = $('[name="id_kota"]');
  kota.removeAttr('disabled');
  kota.empty();
  kota.append('<option value=""> - Pilih Kota - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kota",
      data: 'p='+$('[name="id_provinsi"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              kota.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            // form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              // form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kota']", '<?= $query['id_kota']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            // reset_select2nya("[name='id_kota']", '2171', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_kecamatan()
{
  kecamatan = $('[name="id_kecamatan"]');
  kecamatan.removeAttr('disabled');
  kecamatan.empty();
  kecamatan.append('<option value=""> - Pilih Kecamatan - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kec",
      data: 'p='+$('[name="id_kota"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          kecamatan.append('<option value="0">Semua Kecamatan</option>');
          $.each(AmbilData, function(index, loaddata) {
              kecamatan.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            // form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              // form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kecamatan']", '<?= $query['id_kecamatan']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            // reset_select2nya("[name='id_kecamatan']", '1', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_pasar()
{
  pasar = $('[name="id_pasar"]');
  pasar.removeAttr('disabled');
  pasar.empty();
  pasar.append('<option value=""> - Pilih Pasar - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_pasar",
      data: 'p='+$('[name="id_kota"] :selected').val()+'&p2='+$('[name="id_kecamatan"] :selected').val()+'&status=1',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $('#tabel_itemnya').attr('hidden', true);
          $("#dataHeadnya").html('');
          $("#dataBodynya").html('');
          if ($('[name="id_kecamatan"] :selected').val()==0) {
            pasar.append('<option value="0" selected>Semua Pasar</option>');
            pasar.attr('disabled', true);
            show_item_kat();
          }else{
            item_kat = $('[name="id_item_kategori"]');
            item_kat.attr('disabled', true);
            item_kat.empty();
            $.each(AmbilData, function(index, loaddata) {
              pasar.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
            });
          }
          <?php if ($id!='') { ?>
            // form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              // form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_pasar']", '<?= $query['id_pasar']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            // reset_select2nya("[name='id_pasar']", '3', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_item_kat()
{
  item_kat = $('[name="id_item_kategori"]');
  item_kat.removeAttr('disabled');
  item_kat.empty();
  item_kat.append('<option value=""> - Pilih Kategori - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_item_kat",
      data: 'status=1',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              item_kat.append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            // form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              // form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_item_kategori']", '<?= $query['id_item_kategori']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
          // reset_select2nya("[name='id_item_kategori']", '4', 'val');
            loading_close();
          <?php } ?>
      }
  });
}


function cek_item()
{
  <?php if($id==''){ ?>
  var fd = new FormData();
  fd.append('status', 1);
  fd.append('id_provinsi', $('[name="id_provinsi"] :selected').val());
  fd.append('id_kota', $('[name="id_kota"] :selected').val());
  fd.append('id_kecamatan', $('[name="id_kecamatan"] :selected').val());
  fd.append('id_pasar', $('[name="id_pasar"] :selected').val());
  fd.append('id_item_kategori', $('[name="id_item_kategori"] :selected').val());
  if ($('[name="id_item_kategori"] :selected').val()!='') {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>master/ajax_cek_item_lokasi",
        data: fd,
        dataType: "json",
        processData: false,  // tell jQuery not to process the data
        contentType: false,   // tell jQuery not to set contentType
        chace: false,
        beforeSend: function() {
          loading_show();
        },
        success: function(data){
          if (data.stt==0) {
            get_list_item();
          }else {
            $('#tabel_itemnya').attr('hidden', true);
            $("#dataHeadnya").html('');
            $("#dataBodynya").html('');
            reset_select2nya("[name='id_item_kategori']", '', 'val');
            loading_close();
            swal({ title : "Gagal!", text : "Kategori ini sudah tersedia, silahkan lakukan EDIT untuk perubahan.", type : "warning" });
          }
        }
    });
  }
  <?php }else{ ?>
    get_list_item();
  <?php } ?>
}

function save_checked(aksi='')
{
  var checkboxes = $('#total_check').val();
  var vals = []; x=0; y=0; s=0; u=0;
  for (var i=0, n=checkboxes;i<n;i++)
  {
      Ceklist = $('#customCheck'+i);
      if($('#CustomList'+i).css('display') == 'none') { hidden=true; }else{ hidden=false; }
      if(hidden) {
        if (Ceklist.is(':checked'))
        {
          vals[x] = Ceklist.val(); x++;
          Ceklist.prop('checked', true);
        }else {
          u++;
        }
      }else {
        y++;
        if (aksi=='all') {
          if ($('#customCheckAll').is(':checked'))
          {
            vals[x] = Ceklist.val(); x++;
            Ceklist.prop('checked', true);
          }else {
            Ceklist.prop('checked', false);
          }
        }else {
          if (Ceklist.is(':checked'))
          {
            vals[x] = Ceklist.val(); x++;
            Ceklist.prop('checked', true);
          }else {
            Ceklist.prop('checked', false);
          }
        }
      }
  }
  sel = JSON.stringify(vals);
  $('[name="checked_box"]').val(sel);
  $('#vtotal_check_select').html(addCommas(x));
  total_unselect = parseInt(y)-parseInt(x);
  $('#vtotal_check_unselect').html(addCommas(parseInt(checkboxes) - parseInt(x)));
  if (total_unselect==0) {
    $('#customCheckAll').prop('checked', true);
  }else {
    $('#customCheckAll').prop('checked', false);
  }
  // console.log(x+ ' - '+y);
}

function get_list_item(aksi='')
{
  <?php if($id!=''){ ?>
    $('[name="id_provinsi"]').attr('disabled', true);
    $('[name="id_kota"]').attr('disabled', true);
    $('[name="id_kecamatan"]').attr('disabled', true);
    $('[name="id_pasar"]').attr('disabled', true);
    $('[name="id_item_kategori"]').attr('disabled', true);
  <?php } ?>
  var fd = new FormData();
  fd.append('selectnya', $('[name="checked_box"]').val());
  fd.append('cari', $("#cari_ordernya").val());
  fd.append('id_kat', $("[name='id_item_kategori'] :selected").val());

  $('#tabel_itemnya').removeAttr('hidden');
  dataHeadnya();
  $.ajax({
      type: "POST",
      url : 'master/ajax_item_lokasi',
      data: fd,
      dataType: "json",
      processData: false,  // tell jQuery not to process the data
      contentType: false,   // tell jQuery not to set contentType
      chace: false,
      beforeSend: function(){
        if ($("#cari_ordernya").val()=='') {
          loading_show();
        }
        $("#dataBodynya").html('\
        <tr style="background:#b9d1ff;color:#222;">\
          <td width="1%" class="text-center"></td>\
          <td width="98%" class="text-center">Mencari . . .</td>\
          <td width="1%" class="text-center"></td>\
        </tr>');
        $("#dataBodynya").fadeIn("slow");
      },
      success: function( param ) {
        $("#dataBodynya").html('');
        AmbilData = param.detailnya;
        no=0;
        $.each(AmbilData, function(index, loaddata) {
          if(loaddata.checked==1){ checked='checked'; }else{ checked=''; }
          dataBodynya(no, loaddata.plu, loaddata.nama_item, loaddata.idnya, checked);
          no++;
        });
        $('#total_check').val(no);
        $('#vtotal_check').html(addCommas(no));
        $('#vtotal_check_unselect').html(addCommas(no));
        // JIKA TIDAK ADA DATA ORDER
        if (no==0) {
          $('#customCheckAll').attr('disabled', true);
          if ($('#cari_ordernya').val()=='') {
            pesanX = 'Data belum ada!';
          }else {
            pesanX = 'Pencarian "<b>'+$('#cari_ordernya').val()+'</b>" tidak ditemukan!';
          }
          $("#dataBodynya").html('\
          <tr style="background:pink;color:red;">\
            <td width="1%" class="text-center"></td>\
            <td width="98%" class="text-center">'+pesanX+'</td>\
            <td width="1%" class="text-center"></td>\
          </tr>');
        }else {
          $('#customCheckAll').removeAttr('disabled');
          if ($("#cari_ordernya").val()=='') {
            $('.checkbox').attr('disabled', true);
            setTimeout(function(){
              $('.checkbox').removeAttr('disabled');
            }, 500);
          }
        }
        $('#none').html(no);

          if ($("#cari_ordernya").val()=='') {
            $("#dataBodynya").fadeIn(1000);
            loading_close();
          }
          <?php if($id!=''){ ?>
            save_checked();
          <?php } ?>
      },
      error: function(){
        loading_close();
        $("#dataBodynya").html('');
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}

function dataHeadnya(stt='')
{
  $(document).ready(function () {
    $("#customCheckAll").click(function(){
        // $('input:checkbox').not(this).prop('checked', this.checked);
        save_checked('all');
    });
  });
  ceklist_ALL = '\
    <th style="padding: 10px !important;padding-left:5px !important" width="1%" class="text-center" id="h_check_all">\
      <div class="checkbox">\
          <input type="checkbox" class="custom-input" id="customCheckAll">\
          <label for="customCheckAll"></label>\
      </div>\
    </th>';

  $('#dataHeadnya').html('\
    <tr>\
      '+ceklist_ALL+'\
      <th style="padding: 10px !important;padding-left:5px !important" width="10%" class="text-center">PLU</th>\
      <th style="padding: 10px !important;padding-left:5px !important" width="89%" class="text-center">NAMA&nbsp;ITEM</th>\
    </tr>');
}

function dataBodynya(no=0, plu='', nama_item='', idnya='', checked='')
{
  ceklist = '\
  <td width="1%" class="text-center">\
    <div class="checkbox checkbox_body">\
        <input type="checkbox" class="custom-input" id="customCheck'+no+'" value="'+idnya+'" onclick="save_checked();" '+checked+'>\
        <label for="customCheck'+no+'" style="margin-left: 1.3rem !important;"></label>\
    </div>\
  </td>';

  $("#dataBodynya").append('\
  <tr id="CustomList'+no+'" style="padding:10px;">'+ceklist+'\
    <td width="12%" class="text-left"><label>'+plu+'</label></td>\
    <td width="87%" class="text-left"><label>'+nama_item+'</label></td>\
  </tr>');
}

function simpan_datanya()
{
  loading_show();
  setTimeout(function(){
    simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','<?= $stt; ?>','1');
  }, 100);
}


function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
    RefreshTable();
  }
  loading_close();
}
</script>
