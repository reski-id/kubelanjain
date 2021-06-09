setTimeout(function(){$('.page-loader-wrapper').fadeOut();},50);
window.onbeforeunload = function (e){ $('.page-loader-wrapper').show(); }

function get_loading(stt=0)
{
  if (stt==1) {
    $('.page-loader-wrapper').show();
  }else {
    setTimeout(function(){$('.page-loader-wrapper').fadeOut();},50);
  }
}

function loading_show() { get_loading(1); }
function loading_close() { get_loading(0); }
function loading_hide() { get_loading(0); }

function log_r(params='',css='')
{
  if (params!=='') {
    console.log('%c '+params,css);
  }
}

$(document).ready(function(){
  if ($('select').length!=0) {
    $('select').select2({ width: '100%' });
  }
  if ($(".nestable-with-handle").length!=0) {
    $(".nestable-with-handle").nestable({
      maxDepth: 10,
      collapsedClass:'dd-collapsed',
    }).nestable('collapseAll');//Add this line
  }
});

function ucwords (str) {
    return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}

function reset_select2nya(name='', text='', val='')
{
  if (name!='') {
    if ($(name).length!=0) {
      $(name+" option").removeAttr('selected');
      if (val=='') {
        $(name+" option").filter(function() { return this.text == text; }).attr('selected', true);
        $(name).val($(name).val()).trigger("change");
      }else {
        $(name+" option").filter(function() { return this.value == text; }).attr('selected', true);
        $(name).val(text).trigger("change");
      }
    }
  }
}

function validatePassword(pass1,pass2) {
  $('.parsley-'+pass2).remove();
  if ($('[name="'+pass1+'"]').val() !== $('[name="'+pass2+'"]').val()) {
    var elem = $('[name="'+pass2+'"]').parsley();
    var error_name = pass2;
    elem.addError(error_name, {message: 'Password tidak cocok!'});
    return false;
  }
}

function hanyaAngka(evt) {
		  var charCode = (evt.which) ? evt.which : event.keyCode
		   if (charCode > 31 && (charCode < 48 || charCode > 57))
		    return false;
		  return true;
}

function form_disabled(namenya='',ket='',stt='')
{
  if (stt=='all') {
    $("#"+namenya+" *").prop("disabled", ket);
  }else {
    $('[name="'+namenya+'"]').attr('disabled', ket);
  }
}

function get_mohon_tunggu(stt='')
{
  if ( stt == '' ) {
    swal({
      title: "Mohon tunggu!",
      text: "",
      type: "info",
      showConfirmButton: false,
      allowEscapeKey: false
    });
  } else {
    return pesan('warning','','Mohon tunggu!');
  }
}

// tangal indonesia
function tgl_id(tgl='')
{
  var date = new Date(tgl);
  tanggal = ((date.getDate() > 9) ? date.getDate() : ('0' + date.getDate()));
  bulan   = ((date.getMonth() > 8) ? (date.getMonth() + 1) : ('0' + (date.getMonth() + 1)));
  tahun   = date.getFullYear();
  jam     = ((date.getHours() > 9) ? date.getHours() : ('0' + date.getHours()));
  menit   = ((date.getMinutes() > 9) ? date.getMinutes() : ('0' + date.getMinutes()));
  detik   = ((date.getSeconds() > 9) ? date.getSeconds() : ('0' + date.getSeconds()));
  waktu   = jam+ ':' +menit+ ':' +detik;
  return tanggal + '-' + bulan + '-' + tahun + ' ' +waktu;
}
// tangal indonesia


function formatRupiah(val='', rp='')
{
  if (val!='') {
    tag = $('[name="'+val+'"]');
    if (tag.length!=0) {
      get = get_formatRupiah(tag.val(), rp);
      tag.val(get);
    }
  }
}

//Fungsi formatRupiah
function get_formatRupiah(angka=0, prefix=''){
  var number_string = angka.replace(/[^,\d]/g, '').toString(),
  split   = number_string.split(','),
  sisa    = split[0].length % 3,
  rupiah  = split[0].substr(0, sisa),
  ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

  // tambahkan titik jika yang di input sudah menjadi angka ribuan
  if(ribuan){
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }

  rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
  return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
}
//END Fungsi formatRupiah

function addCommas(nStr){
  nStr += '';
  x = nStr.split(',');
  x1 = x[0];
  x2 = x.length > 1 ? ',' + x[1] : '';
  var rgx = /(\d+)(\d{3})/;
  while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + ',' + '$2');
  }
  return x1 + x2;
}

//START PESAN
function pesan(alert='',icon='',pesan='',timeoutnya='',html='')
{
  $('#pesannya').fadeIn('slow');
  icon = get_pesan_icon(alert, icon);
  msg = ''+
    '<div class="alert alert-'+alert+' alert-dismissible mb-2 col-md-12" role="alert">'+
    '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
    '        <span aria-hidden="true">&times;</span>'+
    '    </button>'+
    '    <div class="d-flex align-items-center">'+
    '        '+icon+''+
    '        <span>'+
    '            '+pesan+''+
    '        </span>'+
    '    </div>'+
    '</div>'+ html;
  if ($('#pesannya').length!=0) {
    if (timeoutnya <= 5) { timeoutnya=5; }
    setTimeout(function(){ $('#pesannya').fadeOut('slow'); }, timeoutnya*1000);
    return $('#pesannya').html(msg);
  }else {
    log_r('id="pesannya" tidak ditemukan!', 'background:pink;');
  }
}

function get_pesan_icon(alert='', icon='')
{ get = '';
  if (icon=='') {
    if (alert=='danger') {
      get = 'error';
    }else if (alert=='warning' || alert=='info') {
      get = 'error-circle';
    }else if (alert=='success') {
      get = 'check';
    }
  }
  if (get!='') {
    return '<i class="bx bx-'+get+'"></i>';
  }else {
    return '';
  }
}

//END PESAN


// ======= KLIK MODE LIGHT & DARK ====== //
function mode()
{
  $.ajax({
    type: "POST",
    url : "users/mode",
    data: "id=1",
    dataType: "json",
    beforeSend: function(){
      get_mohon_tunggu();
    },
    success: function( data ) {
      if ($("body").hasClass("dark-layout")) {
        $("body").removeClass("dark-layout");
        $("#icon_mode").addClass("btn-secondary");
        $("#icon_mode").removeClass("btn-warning");
      }else {
        $("body").addClass("dark-layout");
        $("#icon_mode").removeClass("btn-secondary");
        $("#icon_mode").addClass("btn-warning");
      }
      swal.close();
      // window.location.reload();
    },
    error: function(){
      swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
        function() {
          window.location.reload();
        }
      );
    }
  });
}
// ======= KLIK MODE LIGHT & DARK ====== //
