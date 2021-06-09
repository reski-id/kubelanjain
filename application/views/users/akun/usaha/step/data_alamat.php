<div class="row">
    <div class="col-md-3"></div>
    <div class="col-12 col-md-6">
        <div class="form-group">
          <label for="id_kota">
              Kota
          </label>
          <select class="form-control" name="id_kota" id="id_kota" required disabled onchange="show_kec()">
            <option value=""> - Pilih Kota - </option>
          </select>
        </div>
        <div class="form-group">
            <label for="kecamatan">
                Kecamatan
            </label>
            <select class="form-control" name="id_kecamatan" id="id_kecamatan" required onchange="show_kel()">
            </select>
        </div>
        <div class="form-group">
            <label for="id_kelurahan">
                Kelurahan
            </label>
            <select class="form-control" name="id_kelurahan" id="id_kelurahan" required>
            </select>
        </div>
    <!-- </div>
    <div class="col-md-6"> -->
        <div class="form-group">
            <label for="alamat">Alamat Lengkap</label>
            <textarea name="alamat" id="alamat" rows="4" class="form-control" placeholder="Isi Alamat Lengkap ONLINE SHOP Anda" required></textarea>
        </div>
    </div>
</div>

<script type="text/javascript">
loading_show();
setTimeout(function(){ show_kota(); }, 3*1000);
function show_kota()
{
  $('[name="id_kota"]').empty();
  $('[name="id_kota"]').append('<option value=""> - Pilih Kota - </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kota",
      data: 'p='+$('[name="id_kota"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() { },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_kota"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          reset_select2nya("[name='id_kota']", '2', 'val');
          loading_close();
      }
  });
}

function show_kec()
{
  $('[name="id_kecamatan"]').empty();
  $('[name="id_kecamatan"]').append('<option value=""> Pilih Kecamatan </option>');
  $('[name="id_kelurahan"]').empty();
  $('[name="id_kelurahan"]').append('<option value=""> Pilih Kelurahan </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kec",
      data: 'p='+$('[name="id_kota"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() { },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_kecamatan"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
      }
  });
}

function show_kel()
{
  $('[name="id_kelurahan"]').empty();
  $('[name="id_kelurahan"]').append('<option value=""> Pilih Kelurahan </option>');
  $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>web/ajax_kel",
      data: 'p='+$('[name="id_kota"] :selected').val()+'&kec='+$('[name="id_kecamatan"] :selected').val(),
      cache: false,
      dataType : 'json',
      beforeSend: function() {
        loading_show();
      },
      success: function(param){
          AmbilData = param.plus;
          $.each(AmbilData, function(index, loaddata) {
              $('[name="id_kelurahan"]').append('<option value="'+loaddata.id+'">'+loaddata.nama+'</option>');
          });
          loading_close();
      }
  });
}
</script>
