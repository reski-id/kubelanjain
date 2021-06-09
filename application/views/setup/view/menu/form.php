<form class="form-horizontal" action="" method="post" data-parsley-validate="true" enctype="multipart/form-data">
  <?php get_pesan('msg'); ?>
  <?php
  $datanya[] = array('type'=>'text', 'name'=>'nama', 'nama'=>'Nama MENU', 'icon'=>'menu', 'html'=>'required autofocus onfocus="this.value=this.value"');
  $datanya[] = array('type'=>'text', 'name'=>'icon', 'nama'=>'Icon', 'icon'=>'label', 'html'=>'');
  $datanya[] = array('type'=>'text', 'name'=>'url', 'nama'=>'URL', 'icon'=>'label', 'html'=>'');
  ?>
    <?php foreach ($datanya as $key => $value): ?>
      <div class="form-group">
        <div class="form-label-group position-relative has-icon-left">
            <input type="text" name="<?php echo $value['name']; ?>" class="form-control" value="<?php if(!empty($query)){echo $query[$value['name']];} ?>" placeholder="<?php echo $value['nama']; ?>" <?php echo $value['html']; ?>>
            <div class="form-control-position">
                <i class="bx bx-<?php echo $value['icon']; ?>"></i>
            </div>
            <label for="contact-floating-icon"><?php echo $value['nama']; ?></label>
        </div>
      </div>
    <?php endforeach; ?>
    <b>Group Menu / Batas Menu</b>
    <div class="input-group">
        <select class="form-control" name="batas" required>
          <option value="0" <?php if(!empty($query)){if($query['batas']==0){echo "selected";}} ?>>Tidak</option>
          <option value="1" <?php if(!empty($query)){if($query['batas']==1){echo "selected";}} ?>>Ya</option>
        </select>
    </div>
    <?php if (uri(5)!=0): ?>
      <b>Master Menu</b>
      <div class="input-group">
          <select class="form-control" name="master_menu" required>
            <option value="">-=== Pilih ===- </option>
            <?php $this->db->order_by('urutan','ASC');
            $menunya = get('menu', array('level_menu'=>uri(5)-1, 'batas'=>0, 'akun_menu'=>$akun_menux))->result(); ?>
            <?php foreach ($menunya as $key => $value):
              if(!empty($query)){ $mm=$query['master_menu']; }else{ $mm=''; }?>
              <option value="<?php echo $value->id_menu; ?>" <?php if($value->id_menu==$mm){ echo "selected"; } ?>><?php if($value->nama=='<!-->'){ echo "<----- Batas ----->"; }else{echo $value->nama;} ?></option>
            <?php endforeach; ?>
          </select>
      </div>
    <?php endif; ?>
    <hr>
    <a href="<?php echo "$url/$akun_menux"; ?>" class="btn btn-warning">Menu</a>
    <button type="submit" name="btnsimpan" class="btn btn-primary float-right">Simpan</button>
</form>
