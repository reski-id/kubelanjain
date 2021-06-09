<div id="pesannya"></div>
<form id="sync_form" action="javascript:simpan('sync_form','users/proses/send_bc_info','','','','','1');" method="post" data-parsley-validate="true">
    <div class="form-body">
        <div class="row">
          <?php
          $get_data_group = array('', 'Kontak Mitra & Reseller', 'Kontak Group');
          foreach ($get_data_group as $key => $value) {
            $data_jenis_group[] = array('id'=>$key, 'nama'=>$value);
          }

          $i=0;
          $get_data = array('Nomor Handphone','Mitra 1','Mitra 2', 'Reseller');
          foreach ($get_data as $key => $value) {
            if($key==0){ $group=0; }else{ $group=1; }
            $data_jenis[] = array('id'=>$i, 'nama'=>$value, 'group'=>$group);
            $i++;
          }
          $this->db->order_by('nama_group', 'ASC');
          foreach (get('bc_info_group')->result() as $key => $value) {
            $data_jenis[] = array('id'=>$i.'-'.$value->id_bc_info_group, 'nama'=>$value->nama_group, 'group'=>2);
            $i++;
          }
          $datanya[] = array('type'=>'select', 'name'=>'jenis', 'nama'=>'Kategori', 'icon'=>'-', 'html'=>'required autofocus onchange="cek_user()"', 'col'=>'12', 'data_select'=>$data_jenis, 'data_select_group'=>$data_jenis_group);
          $datanya[] = array('type'=>'number', 'name'=>'no_hp', 'nama'=>'Nomor Handphone', 'validasi'=>true, 'icon'=>'mobile', 'html'=>' data-parsley-minlength="10" data-parsley-maxlength="14" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event);"', 'col'=>12, 'hidden'=>true);
          $datanya[] = array('type'=>'select', 'name'=>'id_user', 'nama'=>'User', 'placeholder'=>'Semua', 'icon'=>'-', 'html'=>'multiple', 'col'=>'12', 'hidden'=>true);
          $datanya[] = array('type'=>'textarea', 'name'=>'pesan', 'nama'=>'Isi Pesan', 'icon'=>'message', 'html'=>'required', 'col'=>'12');
          $get_data2 = array('SMS', 'Whatsapp');
          foreach ($get_data2 as $key => $value) {
            $data_via[] = array('id'=>$key, 'nama'=>$value);
          }
          $datanya[] = array('type'=>'checked', 'name'=>'via', 'nama'=>'Kirim Via', 'html'=>'required multiple', 'col'=>'12', 'data_select'=>$data_via);
          data_formnya($datanya);
          ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary glow btn-block "><i class="bx bx-message-dots"></i> Kirim</button>
</form>

<center>
  <div class="pt-1 pb-0">
    <a href="master/bc_info.html" target="_blank">Tambah Kategori</a>
  </div>
</center>

<script type="text/javascript">
  function cek_user()
  {
    jenis = $('[name="jenis"] :selected');
    if (jenis.val() == 0) {
      set_Attr('remove', '#Hfg_no_hp', 'hidden');
      set_Attr('add', '#Hfg_id_user', 'hidden', true);
      $('[name="no_hp"]').attr('required', true);
      // $('[name="id_user"]').removeAttr('required');
    }else if (jenis.val() == 1 || jenis.val() == 2 || jenis.val() == 3) {
      loading_show();
      $('[name="no_hp"]').val('');
      get_data_usernya();
      $('[name="no_hp"]').removeAttr('required');
      // $('[name="id_user"]').attr('required', true);
      set_Attr('remove', '#Hfg_id_user', 'hidden');
      set_Attr('add', '#Hfg_no_hp', 'hidden', true);
    }else {
      $('[name="no_hp"]').val('');
      set_Attr('add', '#Hfg_no_hp', 'hidden', true);
      set_Attr('add', '#Hfg_id_user', 'hidden', true);
      $('[name="no_hp"]').removeAttr('required');
    }
  }

  function  get_data_usernya()
  {
    var fd = new FormData();
    $.ajax({
      type: "POST",
      url : "<?= base_url('users/get_user_bc'); ?>",
      data: "jenis="+$('[name="jenis"] :selected').val(),
      dataType: "json",
      beforeSend: function(){ },
      success: function( data ) {
        $('[name="id_user"]').empty();
        // $('[name="id_user"]').append('<option value="0">Semua '+$('[name="jenis"] :selected').text()+'</option>');
        $.each(data, function(index, loaddata) {
            $('[name="id_user"]').append('<option value="'+loaddata.no_hp+'">'+loaddata.nama_lengkap+'</option>');
        });
        loading_close();
      },
      error: function(){
        loading_close();
        swal({ title : "Error!", text : "Ada kesalahan, silahkan coba lagi!", type : "error" });
      }
    });
  }

  function run_function_check(stt='')
  {
    loading_close();
    swal.close();
  }

  function set_Attr(aksi='', attr='', data='', val='')
  {
    if (aksi=='add') {
      $(attr).attr(data, val);
    }else if (aksi=='remove') {
      $(attr).removeAttr(data);
    }
  }
</script>
