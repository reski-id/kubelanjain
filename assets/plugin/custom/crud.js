// ======= TABEL ====== //
function reload_tabel()
{
  if ($('#fileData').length!=0) {
    $('#fileData').DataTable().ajax.reload(null, false);
  }
}
// ======= TABEL ====== //

// ======= FORM SIMPAN ====== //
function simpan(form='', get_url='', redirect='', set_pesan='swal', set_timeout='3', set_clear='', run_function='')
{
  if(form==''){ log_r('id form belum ditetukan!','background:pink;color:#222;'); return false; }
  if($('#pesannya')==''){ log_r('id pesannya belum ditetukan!','background:pink;color:#222;'); return false; }

  form_disabled(form, true, 'all');
  var fd = new FormData();
  $('#'+form+' *').each(function(key, field) {
    var field_name = field.name;
    var field_type = field.type;
    var field_multiple = field.multiple;
    if ($('[name="'+field_name+'"]').length!=0) {
      if ($('[name="'+field_name+'"] required').val() == '') {
        return false;
      }
      if (field_type === 'file') {
        if ($('[name="'+field_name+'"]').val() !== '') {
          fd.append(field_name, $('input[name='+field_name+']')[0].files[0]);
        }
      }else if (field_type === 'checkbox') {
        if (field_multiple === true) {
          var checkboxes = document.getElementsByName(field_name);
          var vals = [];
          for (var i=0, n=checkboxes.length;i<n;i++)
          {
              if (checkboxes[i].checked)
              {
                  vals[i] = checkboxes[i].value;
              }
          }
          sel = JSON.stringify(vals);
          fd.append(field_name, sel);
        }else{
          if ($('input[name="'+field_name+'"]:checked').val() !== '') {
            fd.append(field_name, $('input[name='+field_name+']:checked').val());
          }
        }
      }else if ($('select[name="'+field_name+'"]').length!=0) {
        if (field_multiple === true) {
          var sel = [];
          $('[name="'+field_name+'"] :selected').each(function(i, selected) {
              sel[i] = $(selected).val();
          });
          sel = JSON.stringify(sel);
        }else {
          sel =  $('[name="'+field_name+'"] :selected').val();
        }
        fd.append(field_name, sel);
        // console.log(1);
      }else {
        fd.append(field_name, $('[name="'+field_name+'"]').val());
        // console.log(2);
      }
    }
  });
  $.ajax({
    type: "POST",
    url : get_url,
    data: fd,
    dataType: "json",
    processData: false,  // tell jQuery not to process the data
    contentType: false,   // tell jQuery not to set contentType
    chace: false,
    beforeSend: function(){
      get_mohon_tunggu();
    },
    success: function( data ) {
      if (data.stt==1) {
        // reload_tabel();
        if ($('.custom-file-label').length!=0) {
          var get_vfl  = $('.custom-file-label').attr('for');
          var nama_vfl = $('label[for="'+get_vfl+'"]').html();
          $('.custom-file-label').html('Pilih '+nama_vfl);
        }
        if (set_clear=='') {
          $('#'+form).parsley().reset();
          $('#'+form)[0].reset();
          if ($('select').length!=0) {
            $('select').val(null).trigger("change")
          }
        }
        if (set_pesan=='swal') {
          swal({ html:true, type: "success", title: "Sukses!", text: data.pesan, showConfirmButton: false, allowEscapeKey: false });
          setTimeout(function(){ swal.close(); }, set_timeout*1000);
        }else{
          pesan("success", "", data.pesan);
        }
        if (redirect!='') {
          if (redirect == 1) { setTimeout(function(){ window.location.reload(); }, set_timeout*1000); }else{
          setTimeout(function(){ window.location.href = redirect; }, set_timeout*1000); }
        }
        if (run_function=='1') {
          if ( $.isFunction(window.run_function_check) ) {
              run_function_check(1);
          }
        }
      }else {
        if (data.pesan=='') {
          get_pesan = 'Gagal! Ada kesalahan, silahkan coba lagi!';
        }else {
          get_pesan = data.pesan;
        }
        if (set_pesan=='swal') {
          swal({ html:true, type: "warning", title: "Gagal!", text: get_pesan, showConfirmButton: false, allowEscapeKey: false });
          setTimeout(function(){ swal.close(); }, set_timeout*1000);
        }else{
          pesan("warning", "", get_pesan);
        }
        if (run_function=='1') {
          if ( $.isFunction(window.run_function_check) ) {
              run_function_check(2);
          }
        }
      }
      form_disabled(form, false, 'all');
    },
    error: function(){
      if (set_pesan=='swal') {
        swal({ html:true, type: "error", title: "Error!", text: "Ada kesalahan, silahkan coba lagi!", showConfirmButton: false, allowEscapeKey: false });
        setTimeout(function(){ swal.close(); }, set_timeout*1000);
      }else{
        pesan("danger", "", "Error! Ada kesalahan, silahkan coba lagi!");
      }
      form_disabled(form, false, 'all');
    }
  });
}
// ======= FORM SIMPAN ====== //


// ======= AKSI ====== //
function aksi(stt='',id='',form='',get_url='', lebar_modalnya='md', run_function='')
{
  if(form=='' && (stt=='tambah' || stt=='edit')){ log_r('id form belum ditetukan!','background:pink;color:#222;'); return false; }
  $('#modal_id').html('');
    if($('#lebar_modalnya').length!=0){
      $('#lebar_modalnya').removeClass('modal-sm');
      $('#lebar_modalnya').removeClass('modal-md');
      $('#lebar_modalnya').removeClass('modal-lg');
      $('#lebar_modalnya').removeClass('modal-xl');
      $('#lebar_modalnya').addClass('modal-'+lebar_modalnya);
    }
  if (stt=='import') {
    $('#modal_judul').html('Import Data');
    view_data_modal(form, get_url,'',1);
  }else if (stt=='tambah') {
    $('#modal_judul').html('Tambah Data');
    view_data_modal(form, get_url,'',1);
  }else if (stt=='edit' && id!='') {
    if (form=='sektor_form') {
      $('#modal_judul').html('Sektor . . .');
    }else {
      $('#modal_judul').html('Edit Data');
    }
    view_data_modal(form, get_url, id,1);
  }else if (stt=='detail' && id!='') {
    $('#modal_judul').html('Detail Data');
    view_data_modal(form, get_url, id);
  }else if (stt=='hapus' && id!='') {
    swal({ html:true, title: "Apakah Anda Yakin?", text: "", type: "warning",
        showCloseButton: true, showCancelButton: true,
        confirmButtonText:'Yakin', cancelButtonText:'Tidak',
    },
    function(){
      loading_show();
      setTimeout(function(){ hapus_data(get_url, id, '3', run_function); }, 50);
    });
  }else if (stt!='' && id!='') {
    $('#modal_judul').html('Data '+ucwords(stt));
    view_data_modal(form, get_url, id);
  }
}
// ======= AKSI ====== //

// ======= VIEW DATA ====== //
function view_data_modal(form='', get_url='', id='', input='')
{
  if(form=='' && input!=''){ log_r('id form belum ditetukan!','background:pink;color:#222;'); return false; }
  $.ajax({
    type: "POST",
    url : get_url,
    data: "id="+id+"&input="+input,
    beforeSend: function(){
      $('#modal_datanya').html('<br><center>Menampilkan data . . .</center><br><br>');
      $('#modal-aksi').modal({'show':true, 'backdrop':'static'});
    },
    success: function( data ) {
      $('#modal_datanya').html(data);
    },
    error: function(){
      swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
    }
  });
}
// ======= VIEW DATA ====== //

// ======= HAPUS ====== //
function hapus_data(get_url='', id='', set_timeout='3', run_function=''){
    $.ajax({
      type: "POST",
      url : get_url,
      data: "id="+id,
      dataType: "json",
      beforeSend: function(){
        get_mohon_tunggu();
      },
      success: function( data ) {
        reload_tabel();
        if (data.stt==1){
          stt_err = 1;
          setTimeout(function(){ loading_close(); swal({ html:true, title : "Sukses", text : data.pesan, type : "success", showConfirmButton: false, allowEscapeKey: false }); }, 50);
        }else if (data.stt==0) {
          stt_err = 2;
          setTimeout(function(){ loading_close(); swal({ html:true, title : "Gagal", text : data.pesan, type : "error", showConfirmButton: false, allowEscapeKey: false }); }, 50);
        }else {
          stt_err = 3;
          setTimeout(function(){ loading_close(); swal({ html:true, title : "Gagal", text : "Silahkan coba lagi!", type : "error", showConfirmButton: false, allowEscapeKey: false }); }, 50);
        }
        setTimeout(function(){ loading_close(); swal.close(); }, (set_timeout*1000)+50);
        if (run_function=='1') {
          if ( $.isFunction(window.run_function_check) ) {
              setTimeout(function(){ loading_close(); run_function_check(stt_err); }, (set_timeout*1000)+51);
          }
        }
      },
      error: function(){
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
}
// ======= HAPUS ====== //
