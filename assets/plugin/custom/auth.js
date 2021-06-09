// ======= FORM LOGIN & REGISTER ====== //
function auth(get='', get_in=1, redirect='', timeoutnya='')
{
  var form = "sync_form";
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
        if ($('input[name="'+field_name+'"]:checked').val() !== '') {
          fd.append(field_name, $('input[name='+field_name+']:checked').val());
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
    url : "auth/proses/"+get+'/'+get_in,
    data: fd,
    dataType: "json",
    processData: false,  // tell jQuery not to process the data
    contentType: false,   // tell jQuery not to set contentType
    beforeSend: function(){
      get_mohon_tunggu(1);
    },
    success: function( data ) {
      if (data.stt==1) {
        pesan("success", "", data.pesan, timeoutnya);
        if (redirect=='') {
          window.location.href = 'dashboard.html';
        }else if(redirect=='-'){
          $('#'+form).parsley().reset();
          $('#'+form)[0].reset();
          form_disabled(form, false, 'all');
        }else {
          window.location.href = redirect+'.html';
        }
      }else {
        if (data.pesan=='') {
          get_pesan = 'Gagal! Ada kesalahan, silahkan coba lagi!';
        }else {
          get_pesan = data.pesan;
        }
        pesan("warning", "", get_pesan);
        form_disabled(form, false, 'all');
      }
    },
    error: function(){
      pesan("danger", "", "Error! Ada kesalahan, silahkan coba lagi!");
      form_disabled(form, false, 'all');
    }
  });
}
// ======= FORM LOGIN & REGISTER ====== //
