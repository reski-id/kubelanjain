<style media="screen">
.select2-container {
  min-width: 100% !important;
}
</style>
<link rel="stylesheet" type="text/css" href="assets/vendors/css/charts/apexcharts.css">
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
              <div class="row">
                <h4 class="card-title col-12 col-md-<?php if(SuperAdmin()){ echo "4"; }else{ echo "7"; } ?>">Informasi Paket</h4>
                <div class="col-12 col-md-2 pr-1">
                  <select class="form-control" id="chart_paket" onchange="chart_PU()">
                    <option value="1" <?php if(!empty($_GET)){ if($_GET['paket']==1 && $_GET['tipe']==1){ echo "selected"; } } ?>>Package</option>
                    <option value="2" <?php if(!empty($_GET)){ if($_GET['paket']==2 && $_GET['tipe']==1){ echo "selected"; } } ?>>Express</option>
                  </select>
                </div>
                <?php if (SuperAdmin()): ?>
                  <div class="col-12 col-md-3 pr-1">
                    <select class="form-control" id="chart_kota" onchange="chart_PU()">
                      <?php foreach (get('kota', array('status'=>1))->result() as $key => $value):
                        if ($value->id_kota==1) {
                          $kota = 'Semua Kota';
                        }else {
                          $kota = 'Kota '.$value->kota;
                        }?>
                        <option value="<?= $value->id_kota; ?>" <?php if(!empty($_GET)){ if($_GET['kota']==$value->id_kota && $_GET['tipe']==1){ echo "selected"; } } ?>><?= $kota; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                <?php endif; ?>
                <div class="col-8 col-md-2 pr-1">
                  <select class="form-control" id="chart_bln" onchange="chart_PU()">
                    <option value="0" selected>Semua</option>
                    <?php for ($i=1; $i <=12; $i++) { if($i<10){ $i="0$i"; }else{ $i=$i; } ?>
                      <option value="<?= $i ?>" <?php if(!empty($_GET)){ if($_GET['bln']==$i && $_GET['tipe']==1){ echo "selected"; } }else{ if(date('m')==$i){ echo "selected"; } } ?>><?= bln_id($i); ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-4 col-md-1 p-0">
                  <select class="form-control" id="chart_thn" onchange="chart_PU()">
                    <?php for ($i=2020; $i <=date('Y'); $i++) { ?>
                      <option value="<?= $i ?>" <?php if(!empty($_GET)){ if($_GET['thn']==$i && $_GET['tipe']==1){ echo "selected"; } }else{ if(date('Y')==$i){ echo "selected"; } } ?>><?= $i ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <div id="mixed-chart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
