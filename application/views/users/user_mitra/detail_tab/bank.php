<?php
$this->db->join('bank', 'user_bank.id_bank=bank.id_bank');
$get = get('user_bank', array('id_user'=>$query['id_user']));
?>

<center>
<?php $warnanya = array('primary', 'secondary', 'warning', 'success', 'danger'); ?>
<?php foreach ($get->result() as $key => $value):
  if (empty($warnanya[$key])) { $warna = 'secondary'; }else { $warna = $warnanya[$key]; } ?>
  <div class="col-xl-6 col-sm-6 col-12">
    <div class="card bg-secondary bg-lighten-1">
        <div class="card-content">
            <div class="row no-gutters">
                <div class="col-12">
                    <div class="card-body text-center">
                        <h4 class="card-title white pt-2"><?= $value->nama; ?></h4>
                        <p class="card-text white"><b><?= ccFormat_Number($value->no_rek); ?></b></p>
                        <button class="btn btn-<?= $warna; ?> glow btn-block"> <b><?= strtoupper($value->bank) ; ?></b> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
<?php endforeach; ?>
</center>
