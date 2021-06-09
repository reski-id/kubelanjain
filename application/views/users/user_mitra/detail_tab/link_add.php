<?php
$id_mitra = $query['id_mitra'];
$link = siteURL();
$link_reseller = $link."reseller/$id_mitra";
$link_mitra    = $link."mitra/$id_mitra";
?>

  <div class="col-12 p-1">
    <label>LINK ADD RESELLER</label><br>
    <div class="input-group">
      <div class="input-group-prepend">
        <button class="btn btn-secondary pl-1 pr-1" data-toggle="tooltip" data-placement="top" title="Copy Link" onclick="copy_linknya('copy-reseller')"><i class="bx bx-copy-alt"></i></button>
      </div>
      <input type="text" class="form-control" id="copy-reseller" readonly aria-describedby="basic-addon1" onclick="copy_linknya('copy-reseller')" value="<?= $link_reseller; ?>">
    </div>
    <br>
    <?php if ($query['type_id']==1): ?>
    <label>LINK ADD MITRA II</label><br>
    <div class="input-group">
      <div class="input-group-prepend">
        <button class="btn btn-secondary pl-1 pr-1" data-toggle="tooltip" data-placement="top" title="Copy Link" onclick="copy_linknya('copy-mitra')"><i class="bx bx-copy-alt"></i></button>
      </div>
        <br>
        <input type="text" class="form-control" id="copy-mitra" readonly aria-describedby="basic-addon2" onclick="copy_linknya('copy-mitra')" value="<?= $link_mitra; ?>">
    </div>
    <?php endif; ?>
  </div>


<script type="text/javascript">
function copy_linknya(idnya) {
  var copyText = document.getElementById(idnya);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");

  $('#'+idnya).tooltip({
        title: "Copied: " + copyText.value,
        trigger: 'click',
        placement: 'top',
  });
  $('#'+idnya).on('mouseout', function() {
      $(this).tooltip('hide');
  });
}
</script>
