<div class="row" id="tabel_data">
    <div class="col-md-12">
      <div class="card widget-order-activity">
        <div class="card-header justify-content-between align-items-center">
            <h4 class="card-title" id="judul_form_card"><?php echo $judul_web; ?></h4>
            <?php view("plugin/get/box_head_element"); ?>
        </div>
        <hr style="margin:0px;padding:0px;">
        <div class="card-content collapse show">
            <div class="card-body">
              <?php get_pesan('msg'); ?>
              <?php
              $data['query'] = get_field('v_user_biodata_mitra', array('id_user'=>get_session('id_user')));
              view('users/user_mitra/referral/data', $data); ?>
            </div>
        </div>
      </div>
    </div>
</div>
<?php view('plugin/dataTable/custom'); ?>
