<div class="modal-body">
  <center>
    <fieldset class="form-group position-relative has-icon-left">
    <?php $this->db->order_by('pembayaran', 'ASC');
    foreach (get('type_pembayaran', array('status'=>1))->result() as $key => $value): ?>
      <div class="custom-control custom-radio custom-control-inline">
        <input type="radio" id="customRadioInline1_<?= $key; ?>" name="type_pembayaran" class="custom-control-input" value="<?= $value->id_type_pembayaran; ?>" required>
        <label class="custom-control-label" for="customRadioInline1_<?= $key; ?>"><?= $value->pembayaran; ?></label>
      </div>
    <?php endforeach; ?>
    </fieldset>
    <button class="btn btn-success glow btn-block" onclick="btnSubmit()">Submit</button>
  </center>
</div>
<script type="text/javascript">
  $('#modal_judul').html('Metode Pembayaran');

  function btnSubmit()
  {
    if (!$('[name="type_pembayaran"]').is(':checked')) {
      swal({ title : "Warning!", text : "Metode Pembayaran belum dipilih!", type : "warning" });
    }else {
      swal({ html:true, title: "Apakah Anda Yakin?", text: "Proses Payment", type: "warning",
          showCloseButton: true, showCancelButton: true,
          confirmButtonText:'Yakin', cancelButtonText:'Tidak',
      },
      function(){
        loading_show();
        hapus_data('order/simpan/order_payment/<?= encode($id); ?>', $('input[name="type_pembayaran"]:checked').val(), '3', 1);
      });
    }
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
