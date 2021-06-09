<?php get_pesan('msg'); ?>
<style>
  .collapse-title{
    font-size: 16px; font-weight: bold;
  }
  .collapsible .card .card-header, .accordion .card .card-header {
    border-radius: 0rem !important;
    margin-bottom: -1px;
  }
  .collapsible .card, .accordion .card {
    margin-bottom: 0rem;
    box-shadow: none;
    border-radius: 1px !important;
  }
  .collapsible .card.open, .accordion .card.open {
    box-shadow: 0 0px 0px 0 rgba(0, 0, 0, 0.1) !important;
    border-radius: 0.267rem !important;
  }

  .collapsible .card.open .card-header, .accordion .card.open .card-header {
    border: 1px solid #DFE3E7 !important;
  }
</style>
<div class="collapsible collapse-icon accordion-icon-rotate">
    <?php get_menu_list(0, 0, 0, decode(uri(3))); ?>
</div>


<script type="text/javascript">
$("[data-toggle='collapse'] .custom-control").click(function(event) {
    event.stopPropagation();
});

function permission_menu(id='', permission=''){
    if ($('#customSwitch_'+id+'_'+permission).is(':checked')) {
      checked = "true";
    }else {
      checked = "false";
    }
    $.ajax({
      type: "POST",
      url : "<?php echo base_url("$url/set_permission_menu/".uri(3)); ?>",
      data: "id="+id+'&permission='+permission+'&checked='+checked,
      dataType: "json",
      beforeSend: function(){
        loading_show();
      },
      success: function( data ) {
        if (data.stt==1) {

        }else {
          swal({ title : "Gagal", text : data.pesan, type : "warning" });
        }
        loading_close();
      },
      error: function(){
        swal({ title : "Error!", text  : "Ada kesalahan, silahkan coba lagi!", type : "error" },
          function() {
            // window.location.reload();
          }
        );
      }
    });
}
</script>
