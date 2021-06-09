<div class="modal fade" id="modal-aksi" style="display: none;">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="modal_judul" style="font-weight:bold;padding-bottom:10px;"></h4>
      </div>
      <form id="formnya" action="javascript:void(0)" method="post" data-parsley-validate='true' enctype="multipart/form-data">
      <div class="modal-body">
        <?php
        $datanya[] = array('type'=>'text','name'=>'nama','nama'=>'Nama','icon'=>'label','html'=>'required');
        $datanya[] = array('type'=>'text','name'=>'url','nama'=>'URL','icon'=>'label','html'=>'required');
        $datanya[] = array('type'=>'textarea','name'=>'ket','nama'=>'Keterangan','icon'=>'label','html'=>'required');
        ?>
        <?php foreach ($datanya as $key => $value): ?>
          <b><?php echo $value['nama']; ?></b>
            <div class="input-group">
              <span class="input-group-addon"> <i class="material-icons"><?php echo $value['icon']; ?></i> </span>
              <div class="form-line">
              <?php if ($value['type']=='textarea'){ ?>
                <textarea name="<?php echo $value['name']; ?>" rows="2" cols="80" class="form-control" placeholder="Input <?php echo $value['nama']; ?>" <?php echo $value['html']; ?>></textarea>
              <?php }else{ ?>
                <input type="<?php echo $value['type']; ?>" name="<?php echo $value['name']; ?>" class="form-control" value="" placeholder="Input <?php echo $value['nama']; ?>" <?php echo $value['html']; ?>>
              <?php } ?>
              </div>
            </div>
        <?php endforeach; ?>
        <div id="modal_id" hidden></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"><i class="material-icons">close</i> <span>TUTUP</span> </button>
        <button type="submit" class="btn btn-info" name="simpan" onclick="Q_simpan()"><i class="material-icons">save</i> <span>SIMPAN</span> </button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
