<form id="sync_form" action="javascript:aksi_done()" method="post" data-parsley-validate='true' enctype="multipart/form-data">
<div class="modal-body">
  <div class="row">
    <?php
    $datanya[] = array('type'=>'text', 'name'=>'', 'nama'=>'NO.ORDER', 'icon'=>'label', 'html'=>'data-parsley-trigger="keyup" readonly', 'col'=>6, 'value'=>$query['no_transaksi']);
    $datanya[] = array('type'=>'text', 'name'=>'', 'nama'=>'NAMA&nbsp;PELANGGAN', 'icon'=>'user', 'html'=>'data-parsley-trigger="keyup" readonly', 'col'=>6, 'value'=>$query['nama_pelanggan']);
    $datanya[] = array('type'=>'text', 'name'=>'total_belanja', 'nama'=>'TOTAL BELANJA', 'icon'=>'money', 'html'=>'data-parsley-trigger="keyup" readonly', 'col'=>6, 'value'=>format_angka($query['total_belanja'], 'rp'));
    $datanya[] = array('type'=>'text', 'name'=>'real_payment', 'nama'=>'REAL PAYMENT', 'icon'=>'money', 'html'=>'data-parsley-trigger="keyup" onkeyup="gapnya()" required', 'col'=>6, 'value'=>format_angka($query['total_belanja'], 'rp'));
    $datanya[] = array('type'=>'text', 'name'=>'gap', 'nama'=>'GAP', 'icon'=>'money', 'html'=>'data-parsley-trigger="keyup" readonly', 'col'=>6, 'value'=>'Rp. 0');
    $datanya[] = array('type'=>'text', 'name'=>'benefit', 'nama'=>'BENEFIT', 'icon'=>'money', 'html'=>'data-parsley-trigger="keyup" required', 'col'=>6, 'value'=>'0');
    data_formnya($datanya);
    ?>
  </div>
</div>
<div class="modal-footer">
  <button class="btn btn-success glow float-right" onclick="btnSubmit()">Submit</button>
</div>
</form>
<script type="text/javascript">
  $('#modal_judul').html('Proses DONE');

  function format_angkanya(name='', rp='')
  {
    ok = true;
    str = name.val();
    if (str.substr(0, 2) === '- ') {
      result = str.match(/[0-9]/g);
      if (result === null) {
        val = '';
        ok = false;
      }
    }
    if (ok) {
      min='';
      if (str < 0) {
        min='- ';
      }else if (str.indexOf('-') >= 0) {
        min='- ';
      }
      get = get_formatRupiah(str);
      val = min+rp+get;
    }
    name.val(val);
  }

  $('[name="real_payment"]').keyup(function() {
    formatRupiah('real_payment', 'Rp. ');
  });

  $('[name="benefit"]').keyup(function() {
    format_angkanya($('[name="benefit"]'), 'Rp. ');
  });

  function gapnya()
  {
    total = parseInt($('[name="total_belanja"]').val().replace(/[^0-9]/g, ''));
    real  = parseInt($('[name="real_payment"]').val().replace(/[^0-9]/g, ''));
    gap   = total - real;
    $('[name="gap"]').val(gap);
    formatRupiah('gap', 'Rp. ');
  }

  function aksi_done()
  {
    swal({ html:true, title: "Apakah Anda Yakin?", text: "Proses Done", type: "warning",
        showCloseButton: true, showCancelButton: true,
        confirmButtonText:'Yakin', cancelButtonText:'Tidak',
    },
    function(){
      loading_show();
      simpan('sync_form','<?= base_url("order/simpan/order_done")."/".encode($query["id_$tbl"]); ?>','','swal','3','1','1');
    });
  }

  function run_function_check(stt='')
  {
    loading_close();
    if (stt==1) {
      $('#modal-aksi').modal('hide');
      RefreshTable();
    }
  }
</script>
