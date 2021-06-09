<div class="modal fade" id="modal-detail" style="display: none;">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="modal_judul-detail" style="font-weight:bold;padding-bottom:10px;"></h4>
      </div>
      <form id="formnya" action="javascript:void(0)" method="post" data-parsley-validate='true' enctype="multipart/form-data">
      <div class="modal-body">
        <table class="table table-bordered table-hover table-striped" width="100%">
          <tbody>
            <?php $not_view = array("id_$tbl"); ?>
            <?php foreach ($this->db->list_fields($tbl) as $key => $value):
              if (!in_array($value,$not_view)) { ?>
              <tr>
                <th width="70" id="n_<?php echo $value; ?>"><?php echo ucwords(preg_replace('/[_]/',' ',$value)); ?></th>
                <th width="1">:</th>
                <td id="v_<?php echo $value; ?>"><?php echo $value; ?></td>
              </tr>
            <?php } ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-right" data-dismiss="modal"><i class="material-icons">close</i> <span>TUTUP</span> </button>
      </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
