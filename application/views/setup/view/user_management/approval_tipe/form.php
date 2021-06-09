<form id="sync_form" action="javascript:aksi_simpan();" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="pl-1 pr-1 pt-1">
    <?php
      $tipenya=array();
      $data_app=array();
      if ($id=='') {
        $this->db->select('id_approval_tipe');
        $this->db->group_by('id_approval_tipe');
        $get_tipe = get('approval', array('id_user'=>$id_user));
        foreach ($get_tipe->result() as $key => $value) {
          $tipenya[] = $value->id_approval_tipe;
        }
        if (!empty($tipenya)) {
          $this->db->where_not_in('id_approval_tipe', $tipenya);
        }
        $this->db->order_by('nama_approval_tipe', 'ASC');
        foreach (get('approval_tipe', array('status'=>1))->result() as $key => $value) {
          $data_app[] = array('id'=>$value->id_approval_tipe, 'nama'=>$value->nama_approval_tipe);
        }
        $datanya[] = array('type'=>'select', 'name'=>'id_approval_tipe', 'nama'=>'Tipe Approval *', 'icon'=>'-', 'html'=>'data-parsley-trigger="keyup" onchange="show_detail();"', 'data_select'=>$data_app, 'class'=>'select');
        data_formnya($datanya);
      }else{
        $this->db->select('nama_approval_tipe');
        $data_app = get_field('approval_tipe', array('id_approval_tipe'=>$query['id_approval_tipe']))['nama_approval_tipe'];
        echo "<label>Tipe Approval : $data_app</label><br />";
      }
    ?>
    <label><span class="text-danger">Keterangan&nbsp;:</span>&nbsp;<span id="app_ket">-</span></label>
</div>
<div id="v_app" hidden>
    <hr>
    <label class="ml-1" style="font-size:20px;">LIST APPROVAL</label>
    <div id="pesannya"></div>
    <div id="jml_app" hidden>1</div>
    <input type="hidden" name="listnya" value="">
    <div id="v_approval_list"></div>
    <button type="button" class="btn btn-success ml-1" onclick="add_approval()">+ Approval</button>
    <input type="hidden" name="id_user_approvalnya" value="">
</div>
<hr>
<button type="button" class="btn btn-danger glow float-left mb-1 ml-1" data-dismiss="modal"> <span>TUTUP</span> </button>
<?php if (check_permission('view', 'create', "user_management/data_user")){ ?>
  <button type="submit" class="btn btn-primary glow float-right mb-1 mr-1" name="simpan"> <span>SIMPAN</span> </button>
<?php } ?>
</form>

<?php view('plugin/parsley/custom'); ?>

<script type="text/javascript">
$('#modal_judul').html("<?= ($id=='') ? 'Tambah' : 'Edit'; ?> Approval");
if ($('select').length!=0) {
  $('select').select2({ width: '100%' });
}

<?php if ($id!='') { ?>
  // reset_select2nya("[name='id_approval_tipe']", '<?= $query['id_approval_tipe']; ?>', 'val');
  show_detail('<?= $query['id_approval_tipe']; ?>');
<?php } ?>

function show_detail(id_app='')
{
  if(id_app==''){ id_app = $('[name="id_approval_tipe"] :selected').val(); }
  if (id_app!='') {
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>user_management/get_approval_ket",
        data: 'p='+id_app,
        cache: false,
        dataType : 'json',
        beforeSend: function() {
          loading_show();
        },
        success: function(data){
          $('#app_ket').html(data.ket);
          $('#v_app').removeAttr('hidden');
          <?php if($id==''){ ?>
            // add_approval();
            // loading_close();
            $('#jml_app').html(1);
            $('#v_approval_list').html('');
            add_app_edit();
          <?php }else{ ?>
            setTimeout(function(){
              add_app_edit();
            }, 100);
          <?php } ?>
        }
    });
  }
}

<?php //if ($id!='') { ?>
  function add_app_edit(no='')
  {
    if (no=='') { no = parseInt($('#jml_app').html()); }
    <?php if ($id=='') { ?>
      id_approval_tipex = $('[name="id_approval_tipe"] :selected').val();
      userx = '';
    <?php }else{ ?>
      id_approval_tipex = '<?= $id; ?>';
      userx = '<?= $id_user; ?>';
    <?php } ?>
    v_app_list_edit='';
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>user_management/ajax_get_list_user_approval",
        data: 'p='+id_approval_tipex+'&p2='+userx,
        cache: false,
        dataType : 'json',
        beforeSend: function() { },
        success: function(param){
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
    // var Selectnya = $('#id_'+i);
    // // create the option and append to Select2
    // var option = new Option(loaddata.id_user_approval, loaddata.id_user_approval, true, true);
    // Selectnya.append(option).trigger('change');
    var $newOption = $("<option selected='selected'></option>").val(loaddata.id).text(loaddata.text);
    $('#id_'+i).append($newOption).trigger('change');
  }
<?php //} ?>

// add_approval();
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
    simpan('sync_form','<?= base_url()."user_management/simpan/approval/".encode($id_user).'/'.encode($query["id_approval_tipe"]); ?>','','swal','5','1','1')
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
