<?php
$this->db->select('foto_slide, slide, ket');
$this->db->order_by('id_slide', 'DESC');
$this->db->limit('5');
$get_slide = get('slide', array('status'=>1));
?>
<div class=" p-0 mb-<?= (view_mobile())?'1':'5'; ?>">
<div id="carousel-example-caption" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php foreach ($get_slide->result() as $key => $value): ?>
        <li data-target="#carousel-example-caption" data-slide-to="<?= $key ?>" class="<?= ($key==0) ? 'active':''; ?>"></li>
      <?php endforeach; ?>
    </ol>
    <div class="carousel-inner" role="listbox">
      <?php foreach ($get_slide->result() as $key => $value):
        $img   = $value->foto_slide;
        $img_default = 'img/slide/default.png';
        $slide = $value->slide; ?>
        <div class="carousel-item <?= ($key==0) ? 'active':''; ?>">
            <img class="img-fluid" src="<?= (file_exists($img)) ? $img : $img_default; ?>" alt="<?= $slide; ?>" width="100%" style="height:<?= (view_mobile()) ? '100%':'450px' ?> !important;">
            <div class="card-img-overlay bg-overlay">
                <div class="carousel-caption d-none d-sm-block">
                </div>
            </div>

            <!-- <div class="card-img-overlay bg-overlay">
                <div class="carousel-caption d-none d-sm-block">
                    <h3><?= $slide; ?></h3>
                    <p><?= $value->ket; ?></p>
                </div>
            </div> -->

        </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#carousel-example-caption" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carousel-example-caption" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
</div>
