<?php
    foreach ($query->result() as $key => $value):
      $video   = $value->video;
      $judul = $value->judul;
  ?>
  <div class="col-md-3">
    <div class="embed-responsive embed-responsive-16by9 mb-2">
      <iframe class="embed-responsive-item" src="<?= $video ;?>" alt="<?= $judul; ?>" allowfullscreen></iframe>
    </div>
  </div>
 
<?php endforeach;

echo "<div class='col-md-12 card-body mt-0 pt-0'>$halaman</div>";
?>