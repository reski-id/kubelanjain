<div class="row">
  <?php
  $datanya[] = array('bg'=>'info',  'id'=>'v_ol', 'nama'=>'Batam Online Shop', 'url'=>'user_market.html', 'v'=>2);
  $datanya[] = array('bg'=>'success',  'id'=>'v_t', 'nama'=>'ON TRANSFER', 'url'=>'order.html?list=transfer');
  $datanya[] = array('bg'=>'warning',  'id'=>'v_c', 'nama'=>'ON CANCEL', 'url'=>'order/menu.html?list=cancel');
  ?>
  <?php foreach ($datanya as $key => $value): ?>
    <div class="col-sm-4 col-12 dashboard-users-success">
      <a href="<?= $value['url']; ?>">
        <div class="card text-center bg-<?= $value['bg']; ?>">
            <div class="card-content">
                <div class="card-body py-1">
                    <label class="white" style="font-size:20px;"><?= $value['nama'] ?></label>
                    <?php if (empty($value['v'])){ ?>
                      <h4 class="mb-0" id="<?= $value['id'] ?>_data"> . . . </h4>
                      <h4 class="text-muted line-ellipsis white" id="<?= $value['id'] ?>_value"> . . . . </h4>
                    <?php }else{ ?>
                      <h1 class="mb-1" id="<?= $value['id'] ?>_data"> . . . </h1>
                    <?php } ?>
                </div>
            </div>
        </div>
      </a>
    </div>
  <?php endforeach; ?>
</div>
