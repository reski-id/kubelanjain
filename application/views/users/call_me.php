<!-- <div class="modal fade" id="modal-call-me" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel1"> <i class="bx bx-phone-call"></i> Call Me</h3>
                <button type="button" class="close rounded-pill" data-dismiss="modal" aria-label="Close">
                    <i class="bx bx-x"></i>
                </button>
            </div>
            <div class="modal-body p-0">

              <iframe frameborder="0" height="280" src="<?= web('map'); ?>" style="border: 1px solid #ddd;padding:0px;" width="100%"></iframe>

              <div class="pl-1">
                <table>
                  <tr>
                    <td class="align-top pl-0 pr-0 pb-0" width="1"><label>Alamat</label></td>
                    <td class="align-top pl-0 pr-0 pb-0" width="1">&nbsp;:&nbsp;</td>
                    <td class="pl-0 pr-0 pb-0"><?php echo web('alamat'); ?></td>
                  </tr>
                  <tr>
                    <td class="align-top pl-0 pr-0 pt-0"><label>Email</label></td>
                    <td class="align-top pl-0 pr-0 pt-0">&nbsp;:&nbsp;</td>
                    <td class="pl-0 pr-0 pt-0"><a href="mailto:app.meluncur@gmail.com" ><?php echo web('email'); ?></a></td>
                  </tr>
                </table>
              </div>

            </div>
            <div class="modal-footer p-0">
                <?php $text = "Kami ingin menanyakan sesuatu tentang Layanan *".web('website')."* Anda. "; ?>
                <a href="https://api.whatsapp.com/send?phone=<?php echo convert_phone(web('no_hp'), 'ID'); ?>&text=<?php echo $text; ?>&source=&data=" target="_blank" class="btn btn-secondary btn-lg btn-block" style="border-radius: 0px;">
                    <i class="bx bxl-whatsapp"></i>
                    <span><?php echo web('no_hp'); ?></span>
                </a>
            </div>
        </div>
    </div>
</div> -->

<script type="text/javascript">
  function call_me()
  {
    // $('#modal-call-me').modal({'show':true, 'backdrop':'static', 'keyboard': false});
    url = 'https://api.whatsapp.com/send?phone=<?php echo convert_phone(web('no_hp'), 'ID'); ?>&text=Kami%20ingin%20menanyakan%20sesuatu%20tentang%20Layanan%20*<?= web('index_redirect'); ?>*&source=&data='
    window.open(url, '_blank');
  }
</script>
