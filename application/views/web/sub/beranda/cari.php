<?php $url_pdf = 'web/cek_pdf'; ?>
<style>
.select2-container {
  min-width: 150px !important;
}
</style>
<div class="col-6 col-md-2 <?php if(view_mobile()){ echo "pr-0"; } ?>">
    <select id="p_kategori" class="form-control" onchange="cari_kat()">
      <option value="" selected>Kategori</option>
      <?php
      foreach (get_item_kat('', $status)->result() as $key => $value) { ?>
        <option value="<?= encode($value->id_item_kategori); ?>" <?php if(!empty($_GET['kat'])){ if($value->id_item_kategori==decode($_GET['kat'])) { echo "selected"; } } ?>><?= $value->nama ?></option>
      <?php } ?>
    </select>
</div>

<?php if (view_mobile()): ?>
  <div class="col-6 col-md-2 mb-1 pl-0">
    <a href="<?= $url_pdf; ?>" class="btn btn-danger glow float-right"> <i class="bx bx-download"></i> PDF Katalog </a>
  </div>
<?php endif; ?>

<div class="col-12 col-md-4">
  <div class="input-group">
      <input type="search" class="form-control" id="c_item" value="<?= (!empty($_GET['p'])) ? $_GET['p'] : ''; ?>" placeholder="Item Barang" aria-describedby="button-addon2">
      <div class="input-group-append" id="button-addon2">
          <button class="btn btn-secondary" type="button" onclick="cari_item()" id="cari">Cari</button>
      </div>
  </div>
</div>

<?php if (!view_mobile()): ?>
  <div class="col-md-2">
    <a href="<?= $url_pdf; ?>" class="btn btn-danger glow float-right"> <i class="bx bx-download"></i> <b>PDF Katalog</b> </a>
  </div>
<?php endif; ?>


<script type="text/javascript">
  function cari_kat()
  {
    window.location.href = 'cek?kat='+$('#p_kategori :selected').val();
  }

  var input = document.getElementById("c_item");
  input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
     event.preventDefault();
     document.getElementById("cari").click();
    }
  });

  function cari_item()
  {
    c_item = $('#c_item');
    // if (c_item.val()=='') {
    //   c_item.focus();
    //   return false;
    // }else {
      window.location.href = 'cek?p='+c_item.val()+'&kat='+$('#p_kategori :selected').val();
    // }
  }
</script>
