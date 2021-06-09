<?php $logo=web('logo'); $i=0;
    foreach ($query->result() as $key => $value):
      $i++;
      $size_W = '180px';
      $size_H = '130px';
      $plu = substr($value->plu_sub, 0, 7);
      $this->db->select('foto_item_sub');
			$gambar = get_field('item_master_sub', array('plu_sub'=>$value->plu_sub))['foto_item_sub'];
			if (!file_exists($gambar)) {
        $this->db->select('foto_item');
        $gambar = get_field('item_master', array('plu'=>$plu))['foto_item'];
        if (!file_exists($gambar)) {
          $size_W = '100px';
          $gambar = $logo;
        }
      }
      $judul  = $value->nama_item;
  ?>

  <div class="col-md-3">
    <div class="card" style="">
      <center>
        <a class="example-image-link" href="<?= $gambar ?>" data-lightbox="example-set">
          <img class="card-img-top" src="<?php echo $gambar; ?>" alt="<?= $judul ?>" onerror="imgError(this);" class="img-thumbnail" style="width:<?= $size_W; ?> !important;height:<?= $size_H; ?> !important">
        </a>
      </center>
      <div class="text-center">
        <label class="" style="font-size:14px"><?= $judul ?></label>
        <p class="text-lead text-center"> <b> <?= format_angka($value->harga, 'rp'); ?> </b></p>
      </div>
    </div>
  </div>
<?php endforeach;

echo "<div class='col-md-12 card-body mt-0 pt-0'>$halaman</div>";

if ($i==0) { ?>

  <div class="col-md-12 text-center mb-4 pb-4">
    <?php $nama=''; $katnya=''; $carinya='';
    if (!empty($_GET['kat'])):
      $katnya = 'Karegori '.get_name_item_kategori(decode($_GET['kat']));
      $nama .= $katnya;
    endif;
    if (!empty($_GET['p'])):
      $carinya = $_GET['p'];
      if ($katnya!='') {
        $nama .= ' & ';
      }
      $nama .= $carinya;
    endif; ?>
    <h1>Pencarian <b>"<?= $nama; ?>"</b> Tidak Ditemukan!</h1>
  </div>

<?php
}
?>


<script type="text/javascript">
function imgError(image) {
    image.onerror = "";
    image.src = "<?= $logo; ?>";
    return true;
}
</script>
