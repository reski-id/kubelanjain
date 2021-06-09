<style>
.daterangepicker {
  z-index: 11000 !important;
}

.select2-container {
  /* min-width: 50px !important; */
  width: 100% !important
}

#tbl_pelanggan tbody > tr > td {
  font-size: 10px !important;
  padding: 5px !important;
}
</style>
<form id="sync_form" action="javascript:aksi_simpan();" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body row">
  <div id="pesannya"></div>
  <div class="row">
    <?php
      $huruf_angka = '0123456789abcdefghijklmnopqrstuvwxyz';
      $kode =  strtoupper(substr(str_shuffle($huruf_angka), 0, 2));
      $id_user = get_session('id_user');
      if ($id=='') {
        del_nomor('ket', "Order Pasar - $id_user");
        $unik = get_nomor($kode, "Order Pasar - $id_user");
        $input_date = hari_id(tgl_now()).', '.date('d F Y ');
      }else {
        $unik = $query['no'];
        $input_date = hari_id($query['input_date']).', '.tgl_format($query['input_date'], 'd F Y H:i');
      }


    $datanya[] = array('type'=>'text', 'name'=>'id_order', 'nama'=>'No Order', 'icon'=>'label', 'html'=>'data-parsley-trigger="keyup" readonly', 'col'=>3, 'value'=>$unik);

    $datanya[] = array('type'=>'text', 'name'=>'tanggal', 'nama'=>'Tanggal','icon'=>'calendar', 'html'=>'data-parsley-trigger="keyup" readonly',  'value'=>$input_date, 'col'=> 3);

    $datanya[] = array('type'=>'select', 'name'=>'id_provinsi', 'nama'=>'Provinsi','icon'=>'-','html'=>'disabled required onchange="show_kota()"', 'value'=>'', 'col'=> 3, 'selected'=>'21');

    $datanya[] = array('type'=>'select', 'name'=>'id_kota', 'nama'=>'Kota','icon'=>'-','html'=>'disabled required ', 'value'=>'', 'col'=> 3);

    $datanya[] = array('type'=>'select', 'name'=>'id_pelanggan', 'nama'=>'Pelanggan','icon'=>'-','html'=>'onchange="show_detail_pelanggan();"', 'value'=>'', 'col'=> 6);

    $datanya[] = array('type'=>'select', 'name'=>'id_pasar', 'nama'=>'Pasar','icon'=>'-','html'=>'onchange="show_detail_item();"', 'value'=>'', 'col'=> 6, 'hidden'=>true);

    $data_stt = array('Tidak Aktif', 'Aktif');
    foreach ($data_stt as $key => $value) {
      $data_status[] = array('id'=>$key, 'nama'=>$value);
    }
    data_formnya($datanya);
    ?>
    <div class="col-md-12">
      <div id="vdetail_pelanggan" hidden></div>
    </div>
    <div class="col-md-12" id="vdetail_itemnya" hidden>
      <div id="jml_item" hidden>1</div>
      <div id="vdetail_item"></div>
      <div class="row">
        <?php
        $tgl_pengantaran = (empty($query['tgl_pengantaran'])) ? tgl_now('tgl') : tgl_format($query['tgl_pengantaran'], 'Y-m-d');
        $jam_pengantaran = (empty($query['tgl_pengantaran'])) ? date('H:i') : tgl_format($query['tgl_pengantaran'], 'H:i');
        $datanya2[] = array('type'=>'text', 'name'=>'tgl_pengantaran', 'nama'=>'Tanggal Pengantaran *', 'icon'=>'calendar', 'html'=>'data-parsley-trigger="keyup"', 'col'=>3, 'value'=>$tgl_pengantaran);
        data_formnya($datanya2);
        ?>
        <div class="col-md-2">
          <label>Jam Pengantaran *</label>
          <input type="time" class="form-control" name="jam_pengantaran" value="<?= $jam_pengantaran;?>">
        </div>
        <?php
        $datanya3[] = array('type'=>'textarea', 'name'=>'catatan', 'nama'=>'Catatan *', 'icon'=>'note', 'html'=>'data-parsley-trigger="keyup"', 'col'=>7, 'value'=>'', 'readonly');
        data_formnya($datanya3);
        ?>
      </div>
    </div>
    <div class="col-md-12" hidden>
      <label>Item Master</label>
      <input type="text" name="plunya" value="">
      <input type="text" name="harganya" value="">
      <input type="text" name="harga_belinya" value="">
      <input type="text" name="qtynya" value="">
      <input type="text" name="notenya" value="">
    </div>
    <div class="col-md-12" hidden>
      <label>Item Manual</label>
      <input type="text" name="item_manualnya" value="">
      <input type="text" name="harganya2" value="">
      <input type="text" name="harga_belinya2" value="">
      <input type="text" name="qtynya2" value="">
      <input type="text" name="notenya2" value="">
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>
<script type="text/javascript">
 $('#modal_judul').html('<?= ($id=='') ? 'Tambah' : 'Edit'; ?> <?= strtoupper(preg_replace('/[_]/', ' ', $tbl)); ?>');
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

$('[name="tanggal"]').daterangepicker({
  singleDatePicker: true,
  timePicker: false,
  timePicker24Hour: false,
  locale: {
      "format": "DD MMMM YYYY",
  },
  setDate: moment.locale('en')
});

var nowDate = new Date();
var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

$('[name="tgl_pengantaran"]').daterangepicker({
  singleDatePicker: true,
  timePicker: false,
  timePicker24Hour: false,
  drops: "up",
  minDate: today,
  locale: {
      "format": "DD MMMM YYYY",
  },
  setDate: moment.locale('en')
});

<?php if($id!=''){ ?>
  reset_select2nya("[name='status']", '<?= $query['status']; ?>', 'val');
<?php }else{ ?>
  reset_select2nya("[name='status']", '1', 'val');
<?php } ?>

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
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_provinsi']", '<?= $query['id_provinsi']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            reset_select2nya("[name='id_provinsi']", '21', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_kota()
{
  kota = $('[name="id_kota"]');
  // kota.removeAttr('disabled');
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
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_kota']", '<?= $query['id_kota']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            reset_select2nya("[name='id_kota']", '2171', 'val');
            loading_close();
          <?php } ?>
      }
  });
}


show_pelanggan();
function show_pelanggan()
{
  $('[name="id_pelanggan"]').empty();
  $('[name="id_pelanggan"]').append('<option value=""> - Pilih Pelanggan - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_pelanggan",
      data: 'p=0',
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_pelanggan"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+' - '+loaddata.hp1+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_pelanggan']", '<?= $query['id_pelanggan']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            loading_close();
          <?php } ?>
      }
  });
}


function show_detail_pelanggan()
{
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_detail_pelanggan",
      data: 'p='+$('[name="id_pelanggan"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          $('#vdetail_pelanggan').removeAttr('hidden');
          add_table();
          add_detail(param.pelanggan, 'No. Order : '+$('[name="id_order"]').val());
          add_detail(param.nohp_satu, 'Tanggal : '+$('[name="tanggal"]').val());
          add_detail(param.nohp_dua, 'Kota : '+param.kota);
          add_detail(param.alamat, 'Kec : '+param.kecamatan);
          $('#Hfg_id_pasar').removeAttr('hidden');
          show_pasar();
          loading_close();
      }
  });
}

function add_table()
{
  $('#vdetail_pelanggan').html('\
  <table id="tbl_pelanggan" class="table table-bordered table-striped" width="100%">\
    <tbody></tbody>\
  </table>');
}

function add_detail(col1='', col2='')
{
  $('#vdetail_pelanggan tbody').append('\
  <tr>\
    <td width="50%"><label>'+col1+'</label></td>\
    <td width="50%"><label>'+col2+'</label></td>\
  </tr>');
}


function show_pasar()
{
  $('[name="id_pasar"]').empty();
  $('[name="id_pasar"]').append('<option value=""> - Pilih Pasar - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_pasar",
      data: 'status=1&p='+$('[name="id_kota"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_pasar"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          <?php if ($id!='') { ?>
            form_disabled('sync_form', true, 'all');
            setTimeout(function(){
              form_disabled('sync_form', false, 'all');
              reset_select2nya("[name='id_pasar']", '<?= $query['id_pelanggan']; ?>', 'val');
            }, 1000);
          <?php }else{ ?>
            reset_select2nya("[name='id_pasar']", '16', 'val');
            loading_close();
          <?php } ?>
      }
  });
}

function show_detail_item()
{
  $('#vdetail_itemnya').removeAttr('hidden');
  add_table_item();
}

function add_table_item()
{
  nox='0';
  nox1='00';
  st_head = 'padding: 1.3rem 2rem !important;padding-left:10px !important;';
  st_foot = 'padding: 10px;';
  v_item = '\
  <table id="fileData" class="table table-striped table-bordered" width="100%">\
    <thead class="thead-dark">\
      <tr>\
        <th class="text-center" style="'+st_head+'" width="47%">Nama&nbsp;Item</th>\
        <th class="text-center" width="10%">Harga</th>\
        <th class="text-center" width="7%">QTY</th>\
        <th class="text-center" width="15%">TOTAL</th>\
        <th class="text-center" width="20%">NOTE</th>\
        <th class="text-center" width="1%">Opsi</th>\
      </tr>\
    </thead>\
    <tbody id="v_item_list">\
    </tbody>\
    <tfoot id="v_foot_list">\
      <tr>\
        <th style="'+st_foot+'" class="align-middle" style="'+st_head+'" colspan="3" width="49%">\
          <button type="button" class="btn btn-success btn-sm" onclick="add_detail_item()">+ Item</button>\
          <button type="button" class="btn btn-primary btn-sm" onclick="add_detail_item(\'\', \'lainnya\')">+ Item Lainnya</button>\
          <label class="float-right">Subtotal</label>\
        </th>\
        <th style="'+st_foot+'" class="text-right"><label id="subtotal" style="font-size: 16px !important;">0</label></th>\
        <th style="'+st_foot+'" class="text-center" colspan="2"></th>\
      </tr>\
      <tr>\
        <th style="'+st_foot+'" class="text-right" style="'+st_head+'" colspan="3" width="49%"><label>Ongkir</label></th>\
        <th style="'+st_foot+'" class="text-right"><input type="text" name="ongkir" class="form-control text-right" id="ongkir" value="0" minlength="1" onkeyup="hitung_total_all();" /></th>\
        <th style="'+st_foot+'" class="text-center" colspan="2"></th>\
      </tr>\
      <tr>\
        <th style="'+st_foot+'" class="text-right" style="'+st_head+'" colspan="3" width="49%"><label>Diskon</label></th>\
        <th style="'+st_foot+'" class="text-right"><input type="text" name="diskon" class="form-control text-right" id="diskon" value="0" minlength="1" onkeyup="hitung_total_all();" required/></th>\
        <th style="'+st_foot+'" class="text-center" colspan="2"></th>\
      </tr>\
      <tr>\
        <th style="'+st_foot+'" class="text-right" style="'+st_head+'" colspan="3" width="49%"><label>Total</label></th>\
        <th style="'+st_foot+'" class="text-right"><label id="total_all" style="font-size: 16px !important;">0</label></th>\
        <th style="'+st_foot+'" class="text-center" colspan="2"></th>\
      </tr>\
    </tfoot>\
  </table>';
  $('#vdetail_item').html(v_item);
  add_detail_item();
}

function add_detail_item(no='', aksi='')
{
  if (no=='') { no = parseInt($('#jml_item').html()); }
  if (aksi=='lainnya') { //jika item lainnya
    itemnya = '<input type="text" name="pluy[]" class="form-control text-left" id="plu_'+no+'" value="" placeholder="Input Item Lainnya" minlength="1" onkeyup="show_inputannya('+no+');"/>';
    hbnya = 'onkeyup="hitung_hb_lainnya('+no+')"';
    namenya = '_manual';
  }else {
    itemnya = '\
    <select name="plux[]" class="form-control" id="plu_'+no+'" data-placeholder="Pilih PLU ITEM '+no+'" required onchange="show_item_list('+no+');">\
    <option value=""> - Pilih PLU ITEM '+no+' - </option>\
    </select>';
    hbnya = '';
    namenya = '';
  }
  v_item_list = '\
  <tr id="data_item_'+no+'">\
    <td>'+itemnya+'</td>\
    <td><input type="text" name="harga'+namenya+'[]" '+hbnya+' class="form-control text-right" id="harga'+namenya+'_'+no+'" value="0" readonly minlength="1" required/><input type="hidden" name="harga_beli'+namenya+'[]" class="form-control text-right" id="harga_beli'+namenya+'_'+no+'" value="0" readonly minlength="1" required/></td>\
    <td><input type="text" name="qty'+namenya+'[]" class="form-control text-right" id="qty'+namenya+'_'+no+'" value="0" readonly minlength="1" min="0" required onkeyup="hitung_total('+no+');"/></td>\
    <td><input type="text" name="total'+namenya+'[]" class="form-control text-right" id="total'+namenya+'_'+no+'" value="0" readonly minlength="1" required/></td>\
    <td><input type="text" name="note'+namenya+'[]" class="form-control text-left" id="note'+namenya+'_'+no+'" value="" readonly minlength="1" /></td>\
    <td><center><button type="button" class="btn btn-danger btn-sm" onclick="del_item('+no+')" title="Hapus Item">X</button></center></td>\
  </tr>';
  $('#v_item_list').append(v_item_list);
  $('#jml_item').html(parseInt($('#jml_item').html()) + 1);
  if (aksi!='lainnya') { check_list_item(); }
}

function show_inputannya(no=1)
{
  $('#harga_manual_'+no).removeAttr('readonly');
  $('#qty_manual_'+no).removeAttr('readonly');
  // $('#total_'+no).removeAttr('readonly');
  $('#note_manual_'+no).removeAttr('readonly');
}

function hitung_hb_lainnya(no=1)
{
  harga = parseInt($('#harga_manual_'+no).val());
  if ($('#harga_manual_'+no).val()=='') {
    hb = 0;
  }else {
    hb = harga - ((harga * 10) / 100);
  }
  $('#harga_beli_manual_'+no).val(hb);
}

function show_item_list(no=1)
{
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>order/ajax_harga",
      data: 'tbl=update_harga&p='+$('#plu_'+no+' :selected').val()+'&p2='+$('[name="id_pasar"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
        $('#harga_'+no).val(param.harga);
        $('#harga_beli_'+no).val(param.harga_beli);
        // format_angkanya($('#harga_'+no));
        $('#harga_'+no).removeAttr('readonly');
        $('#qty_'+no).removeAttr('readonly');
        $('#note_'+no).removeAttr('readonly');
        loading_close();
      }
  });
}

function hitung_total(no=1)
{
  // harga = parseInt($('#harga_'+no).val().replace(/[^0-9]+/g, ''));
  // qty   = parseInt($('#qty_'+no).val().replace(/[^0-9]+/g, ''));
  // harga  = parseInt(harga1) + parseInt(harga2);
  // qty    = parseInt(qty1) + parseInt(qty2);
  if ($('#total_'+no).length!=0) {
    harga1 = $('#harga_'+no).val();
    qty1   = $('#qty_'+no).val();
    total1 = harga1 * qty1;
    $('#total_'+no).val(total1);
  }
  if ($('#total_manual_'+no).length!=0) {
    harga2 = $('#harga_manual_'+no).val();
    qty2   = $('#qty_manual_'+no).val();
    total2 = harga2 * qty2;
    $('#total_manual_'+no).val(total2);
  }
  // format_angkanya($('#harga_'+no));
  // format_angkanya($('#qty_'+no));
  // format_angkanya($('#total_'+no));
  hitung_subtotal();
}

function hitung_subtotal()
{
  total=0;
  $('[name="total[]"]').each(function(i, selected) {
    // total += parseInt($(selected).val().replace(/[^0-9]/g, ''));
    total += parseInt($(selected).val());
  });
  $('[name="total_manual[]"]').each(function(i, selected) {
    // total += parseInt($(selected).val().replace(/[^0-9]/g, ''));
    total += parseInt($(selected).val());
  });
  $('#subtotal').html(total);
  format_angkanya2($('#subtotal'));
  hitung_total_all();
}

function hitung_total_all()
{
  total = (parseInt($('#subtotal').html().replace(/[^0-9]/g, '')) + parseInt($('#ongkir').val().replace(/[^0-9]/g, ''))) - parseInt($('#diskon').val().replace(/[^0-9]/g, ''));
  $('#total_all').html(total);
  format_angkanya($('#ongkir'));
  format_angkanya($('#diskon'));
  format_angkanya2($('#total_all'));
  if (total < 0) {
    $('#total_all').html('-'+$('#total_all').html());
  }
  save_array();
}

function format_angkanya(name='')
{
  get = get_formatRupiah(name.val());
  name.val(get);
}

function format_angkanya2(name='')
{
  get = get_formatRupiah(name.html());
  name.html(get);
}

function del_item(no=1)
{
  $('#data_item_'+no).remove();
  no=0;
  for (var i=1; i <=$('#jml_item').html(); i++) {
    if ($("#plu_"+i).length!=0) {
      no+=1;
    }
  }
  save_array();
}

function check_list_item()
{
  for (var i=1; i <=$('#jml_item').html(); i++) {
    if ($("#plu_"+i).length!=0) {
      get_plu(i);
    }
  }
  save_array();
}

function get_plu(no=1)
{
  if ($("#plu_"+no).val() == null) {
    $("#plu_"+no).empty();
  }
  var selectednumbers = [];
  $('[name="plux[]"] :selected').each(function(i, selected) {
    if ($(selected).val()!='') {
      selectednumbers[i] = $(selected).val();
    }
  });

  $("select#plu_" + no).select2({
    language: {
     inputTooShort: function() {
       return 'Ketik PLU atau Nama Item';
     },
      searching: function() {
          return "Mencari . . .";
      }
    },
    placeholder: 'Pilih PLU ITEM '+no,
    ajax: {
      url: "<?= base_url(); ?>order/ajax_get_item_update_harga/cek_select",
      type: "POST",
      dataType: 'json',
      delay: 250,
      data: function(params) {
        return {
          cari: params.term, // search term
          sel: JSON.stringify(selectednumbers),
          pasar: $('[name="id_pasar"] :selected').val(),
          item_plu: 'item_plu',
        };
      },
      processResults: function(data, params) {
        params.page = params.page || 1;
        return {
          results: data,
          pagination: {
            more: (params.page * 30) < data.total_count
          }
        };
      },
      cache: true
    },
    escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
    minimumInputLength: 1
  });
  return false;
}

function wajib_isi(msg='', name='', selected='')
{
  if (name!='') {
    if ($('[name="'+name+'"]').length!=0) {
      if ($('[name="'+name+'"] '+selected).val() == '') {
        return wajib_isi(msg);
      }
    }
  }else {
    swal({ title : "Warning!", text : msg+" Wajib diisi!", type : "warning" });
    return true;
  }
  return false;
}

function get_multiple(get='', set='')
{
  if ($('[name="'+get+'[]"]').length!=0) {
    var x = [];
    $('[name="'+get+'[]"]').each(function(i, selected) {
      if (get=='plux') {
        x[i] = $(selected).val();
      }else {
        x[i] = i + ' ' + $(selected).val();
      }
    });
    if ($('[name="'+set+'"]').length!=0) {
      $('[name="'+set+'"]').val(JSON.stringify(x));
    }
  }
}

function save_array()
{
  get_multiple('plux', 'plunya');
  get_multiple('harga', 'harganya');
  get_multiple('harga_beli', 'harga_belinya');
  get_multiple('qty', 'qtynya');
  get_multiple('note', 'notenya');
  // manual
  get_multiple('pluy', 'item_manualnya');
  get_multiple('harga_manual', 'harganya2');
  get_multiple('harga_beli_manual', 'harga_belinya2');
  get_multiple('qty_manual', 'qtynya2');
  get_multiple('note_manual', 'notenya2');
}

function aksi_simpan()
{
  save_array(); stt_simpan = true;
  if (wajib_isi('PROVINSI', 'id_provinsi', ':selected')){ return false; }
  if (wajib_isi('KOTA', 'id_kota', ':selected')){ return false; }
  if (wajib_isi('PELANGGAN', 'id_pelanggan', ':selected')){ return false; }
  if (wajib_isi('PASAR', 'id_pasar', ':selected')){ return false; }
  if (wajib_isi('CATATAN', 'catatan')){ return false; }
  if (wajib_isi('TANGGAL PENGANTARAN', 'tgl_pengantaran')){ return false; }
  if (wajib_isi('JAM PENGANTARAN', 'jam_pengantaran')){ return false; }
  if (wajib_isi('ONGKIR', 'ongkir')){ return false; }
  if (wajib_isi('DISKON', 'diskon')){ return false; }
  no=0;
  for (var i=1; i <=$('#jml_item').html(); i++) {
    if ($("#plu_"+i).length!=0) {
      no+=1;
    }
  }
  if (no <= 0) { if (wajib_isi('ITEM')){ return false; } }

  total = $('#total_all').html();
  if (total.match(/-/g)=='-') {
    swal({ title : "Warning!", text : "Total tidak boleh kurang dari 0!", type : "warning" });
    return false;
  }

  if (stt_simpan) {
    swal({ html:true, title: "Apakah Anda Yakin?", text: 'Simpan Order', type: "warning",
        showCloseButton: true, showCancelButton: true,
        confirmButtonText:'Yakin', cancelButtonText:'Tidak',
    },
    function(){
      // loading_show();
      setTimeout(function(){
        // swal({ title : "Warning!", text : "Maaf, Lagi Maintenis!", type : "warning" });
        simpan('sync_form','<?= $urlnya."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');
      }, 200);
    });
  }
}

function run_function_check(stt='')
{
  if (stt==1) {
    $('#modal-aksi').modal('hide');
    stt = $('#id_provinsix :selected').val();
    if (stt!='') {
      RefreshTable();
    }
  }
}
</script>
