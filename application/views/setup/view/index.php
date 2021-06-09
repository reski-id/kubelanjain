<div class="card">
  <div class="card-header">
      <h4 class="card-title">
        <b><?php echo $judul_web; ?></b>
        <?php if (!empty($btnAdd)){  echo $btnAdd; } ?>
      </h4>
  </div>
  <hr style="margin:0px;margin-bottom:30px;">
  <div class="card-content">
      <div class="card-body">
        <div class="row">
          <?php if ($Lebar_BOX=='form'): ?>
            <div class="col-md-3"></div>
            <div class="col-md-6">
          <?php endif; ?>
          <?php
            $this->load->view("$view");
          ?>
          <?php if ($Lebar_BOX=='form'): ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
  </div>
</div>
