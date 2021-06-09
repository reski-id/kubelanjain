<form id="sync_form" action="javascript:aksi_simpan();" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="pl-1 pr-1 pt-1">
  <div id="pesannya"></div>
  <?php
  $datanya[] = array('type'=>'text','name'=>'nama_approval_tipe','nama'=>'Nama Approval Tipe','icon'=>'tag','html'=>'required minlength="1"', 'value'=>$query['nama_approval_tipe']);
  $datanya[] = array('type'=>'textarea','name'=>'ket','nama'=>'Keterangan','icon'=>'file','html'=>'required minlength="1"', 'value'=>$query['ket']);
  data_formnya($datanya);

  $datanya2[] = array('type'=>'select','name'=>'id_user','nama'=>'User','icon'=>'-','html'=>'required minlength="1" multiple="multiple"', 'value'=>'');
  data_formnya($datanya2);
  ?>
</div>
<div id="v_app" class="pb-1">
    <!-- <label class="ml-1" style="font-size:20px;">LIST APPROVAL</label> -->
    <div id="pesannya"></div>
    <div id="jml_app" hidden>1</div>
    <input type="hidden" name="listnya" value="">
    <div id="v_approval_list"></div>
    <button type="button" class="btn btn-success ml-1" onclick="add_approval()">+ Approval</button>
    <input type="hidden" name="id_user_approvalnya" value="">
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-danger glow float-left" data-dismiss="modal"> <span>TUTUP</span> </button>
  <button type="submit" class="btn btn-primary glow" name="simpan"> <span>SIMPAN</span> </button>
</div>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

$("[name='id_user']").select2({
  language: {
   inputTooShort: function() {
     return 'Ketik Nama User / Nama Gudang / Nama Akses';
   },
    searching: function() {
        return "Mencari . . .";
    }
  },
  ajax: {
    url: "<?= base_url(); ?>user_management/ajax_get_user_approval_tipe",
    type: "POST",
    dataType: 'json',
    delay: 250,
    data: function(params) {
      return {
        cari: params.term, // search term
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
  placeholder: 'Pilih User',
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
});

loading_show();
setTimeout(function(){
  <?php if ($id==''){ ?>
    add_approval();
    loading_close();
  <?php }else{ ?>
    add_app_edit();
  <?php } ?>
}, 200);

<?php if ($id!='') { ?>
  function add_app_edit(no='')
  {
    if (no=='') { no = parseInt($('#jml_app').html()); }
    v_app_list_edit='';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>user_management/ajax_get_list_user_approval",
        data: 'p=<?= $id; ?>',
        cache: false,
        dataType : 'json',
        beforeSend: function() { },
        success: function(param){
            AmbilDataUser = param.user;
            i=0;
            $.each(AmbilDataUser, function(index, loaddata) {
              var $newOption = $("<option selected='selected'></option>").val(loaddata.id).text(loaddata.text);
              $('[name="id_user"]').append($newOption).trigger('change');
            });

            AmbilData = param.plus;
            i=0;
            $.each(AmbilData, function(index, loaddata) {
              i++;
              v_app_list_edit = '\
              <div class="row ml-0 mr-0 mb-1" id="data_approval_'+i+'">\
                <div class="col-12 col-md-12">\
                  <label> Approval&nbsp;<span id="no_app_'+i+'">'+i+'</span> </label>\
                </div>\
                <div class="col-10 col-md-10 pr-0">\
                  <select name="id_user_approval[]" class="form-control" id="id_'+i+'" onchange="save_array();">\
                  </select>\
                </div>\
                <div class="col-2 col-md-2">\
                  <center><button type="button" class="btn btn-danger btn-sm" onclick="del_app('+i+')" title="Hapus Item">X</button></center>\
                </div>\
              </div>';
              $('#v_approval_list').append(v_app_list_edit);
              edit_sel_data(i, loaddata);
            });
            $('#jml_app').html(parseInt(i) + 1);
            check_list_produk();
            reset_no_app();
            loading_close();
        }
    });
  }

  function edit_sel_data(i, loaddata)
  {
    var $newOption = $("<option selected='selected'></option>").val(loaddata.id).text(loaddata.text);
    $('#id_'+i).append($newOption).trigger('change');
  }
<?php } ?>

function add_approval(no='')
{
  if (no=='') { no = parseInt($('#jml_app').html()); }
  v_approval_list = '\
  <div class="row ml-0 mr-0 mb-1" id="data_approval_'+no+'">\
    <div class="col-12 col-md-12">\
      <label> Approval&nbsp;<span id="no_app_'+no+'">'+no+'</span> </label>\
    </div>\
    <div class="col-10 col-md-10 pr-0">\
      <select name="id_user_approval[]" class="form-control" id="id_'+no+'" onchange="save_array();">\
      </select>\
    </div>\
    <div class="col-2 col-md-2">\
      <center><button type="button" class="btn btn-danger btn-sm" onclick="del_app('+no+')" title="Hapus Item">X</button></center>\
    </div>\
  </div>';
  $('#v_approval_list').append(v_approval_list);
  $('#jml_app').html(parseInt($('#jml_app').html()) + 1);
  check_list_produk();
  reset_no_app();
}

function reset_no_app()
{
  no=1;
  for (var i=1; i <=$('#jml_app').html(); i++) {
    if ($("#id_"+i).length!=0) {
      $('#no_app_'+i).html(no);
      no++;
    }
  }
}

function del_app(no=1)
{
  $('#data_approval_'+no).remove();
  check_list_produk();
  reset_no_app();
  save_array();
}

function check_list_produk()
{
  for (var i=1; i <=$('#jml_app').html(); i++) {
    if ($("#id_"+i).length!=0) {
      get_app(i);
    }
  }
  save_array();
}

function get_app(no=1)
{
   if ($("#id_"+no).val() == null) {
     $("#id_"+no).empty();
   }
   var selectednumbers = [];
   $('[name="id_user_approval[]"] :selected').each(function(i, selected) {
     selectednumbers[i] = $(selected).val();
   });

   $("#id_" + no).select2({
     language: {
      inputTooShort: function() {
        return 'Ketik Nama User / Nama Gudang / Nama Akses';
      },
       searching: function() {
           return "Mencari . . .";
       }
     },
     ajax: {
       url: "<?= base_url(); ?>user_management/ajax_get_user_approval_tipe/cek_select",
       type: "POST",
       dataType: 'json',
       delay: 250,
       data: function(params) {
         return {
           cari: params.term, // search term
           sel: JSON.stringify(selectednumbers),
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
     placeholder: 'Pilih Tipe Approval',
     minimumInputLength: 1,
     templateResult: formatRepo,
     templateSelection: formatRepoSelection
   });
  return false;
}

function formatRepo (repo) {
  if (repo.loading) { return repo.nama; }
  idnya = repo.id;
  var $container = $(
    "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__avatar_"+idnya+" float-left' style='margin-left:5px;margin-right:10px;'><img src='" + repo.img_url + "' class='round' style='width:40px !important;'/></div>" +
      "<div class='select2-result-repository__meta_"+idnya+" float-left'>" +
        "<div class='select2-result-repository__title_"+idnya+"'></div>" +
        "<small class='select2-result-repository__description_"+idnya+"'></small>" +
      "</div>" +
    "</div>"
  );

  $container.find(".select2-result-repository__title_"+idnya+"").text(repo.nama);
  $container.find(".select2-result-repository__description_"+idnya+"").html('<i class="bx bx-briefcase pr-0 mr-0" style="font-size:12px;"></i>&nbsp;'+repo.akses);

  return $container;
}

function formatRepoSelection (repo) {
  return repo.text || repo.nama + ' [ '+repo.akses+' ]';
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

function save_array()
{
  var id_user_approval = [];
  $('[name="id_user_approval[]"] :selected').each(function(i, selected) {
    id_user_approval[i] = $(selected).val();
  });
  $('[name="id_user_approvalnya"]').val(JSON.stringify(id_user_approval));
}

function aksi_simpan()
{
  save_array();
  stt_simpan = true;
  <?php if($id==''){ ?>
    if (wajib_isi('Tipe Approval', 'id_approval_tipe', ':selected')){ return false; }
  <?php } ?>
  sel_val = $('[name="id_user_approvalnya"]').val();
  if (sel_val=='[""]' || sel_val=='[]' || sel_val=='') {
    if (wajib_isi('User Approval')){ return false; }
  }

  if (stt_simpan) {
    // swal({ title : "Sukses!", text : "OK", type : "success" });
    simpan('sync_form','<?= base_url()."master/simpan/approval_tipe/".encode($query["id_approval_tipe"]); ?>','','swal','5','1','1')
  }
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
