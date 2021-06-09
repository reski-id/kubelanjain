<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function data_formnya($datanya=array(), $datanya2=array()){
  if (!empty($datanya2)){ $col_2nya=1; }else{ $col_2nya=0; } ?>
  <?php if ($col_2nya == 1): ?>
  <div class="row">
    <div class="col-md-6">
      <div class="row">
  <?php endif; ?>
      <?php
      foreach ($datanya as $k => $v):
        get_form_datanya($k,$v);
      endforeach;
      ?>
  <?php if ($col_2nya == 1): ?>
      </div>
    </div>
    <div class="col-md-6">
      <div class="row">
  <?php endif; ?>
      <?php
      foreach ($datanya2 as $k => $v):
        get_form_datanya($k,$v);
      endforeach;
      ?>
  <?php if ($col_2nya == 1): ?>
      </div>
    </div>
  </div>
  <?php endif;
}

function get_form_datanya($k='', $v='')
{
    // if ($k=='' || $v=='') { return false; }
    $type  = empty($v['type'])  ? '': $v['type'];
    $icon  = empty($v['icon'])  ? 'label': $v['icon'];
    $name  = empty($v['name'])  ? '': $v['name'];
    $name_select  = empty($v['name_select'])  ? '': $v['name_select'];
    $nama  = empty($v['nama'])  ? '': $v['nama'];
    $placeholder = empty($v['placeholder'])  ? '': $v['placeholder'];
    // $value = empty($v['value']) ? '': $v['value'];
    @$value = $v['value'];
    $class = empty($v['class']) ? '': $v['class'];
    $id    = empty($v['id'])    ? '': $v['id'];
    $html  = empty($v['html'])  ? '': $v['html'];
    $col   = empty($v['col'])   ? '': $v['col'];
    $hidden = empty($v['hidden']) ? '': $v['hidden'];
    $data_select = empty($v['data_select']) ? array() : $v['data_select'];
    $input_data_select = empty($v['input_data_select']) ? array() : $v['input_data_select'];
    $data_select_group = empty($v['data_select_group']) ? array() : $v['data_select_group'];
    $validasi = empty($v['validasi'])  ? '': $v['validasi'];
    if ($validasi==true) { $validasi = 'required'; }
    $mb = empty($v['mb'])  ? 'mb-50': $v['mb'];
    ?>

  <?php if ($type=='batas'){ ?>
    <div class="col-md-<?= $col; ?>"> <?= $nama; ?> </div>
  <?php
  }else{
    if($col!=''){ ?>
  <div class="col-md-<?= $col; ?>"> <?php
    } ?>
    <?php if(!empty($input_data_select)){ ?> <label for="Input<?= $k; ?>" id="Lbl_<?= $name; ?>"  <?php if($hidden){ echo "hidden id='Hfg_$name'"; } ?>><?= $nama; ?></label> <?php } ?>
    <div class="<?php if(empty($input_data_select)){ echo "form"; }else{ echo "input"; } ?>-group" <?php if($hidden){ echo "hidden id='Hfg_$name'"; } ?>>
      <?php if(empty($input_data_select)){ ?>
      <label for="Input<?= $k; ?>" id="Lbl_<?= $name; ?>"><?= $nama; ?></label>
      <div class="position-relative has-icon-left <?php if($type=='file'){ echo "custom-file"; } ?>">
      <?php } ?>
        <?php if ($type=='textarea'){ ?>
          <textarea name="<?= $name; ?>" class="form-control <?= $class; ?>" id="Input<?= $k; ?> <?= $id; ?>" placeholder="<?php if($placeholder==''){ echo $nama; }else{ echo $placeholder; } ?>" <?= $validasi; ?> <?= $html; ?>><?= $value; ?></textarea>
        <?php }elseif ($type=='radio'){ ?>

        <?php }elseif ($type=='checked'){ ?>
          <?php foreach ($data_select as $k_sel => $v_sel): ?>
            <div class="checkbox pr-1">
              <input type="checkbox" class="checkbox__input" name="<?= $name; ?>" value="<?= $v_sel['id']; ?>" id="checkbox<?= $k_sel; ?>" <?= $validasi; ?> <?= $html; ?>>
              <label for="checkbox<?= $k_sel; ?>"><?= $v_sel['nama']; ?></label>
            </div>
          <?php endforeach; ?>
        <?php }elseif ($type=='select'){ ?>
          <select name="<?= $name; ?>" class="form-control <?= $class; ?>" id="Input<?= $k; ?> <?= $id; ?>" data-placeholder="Pilih <?php if($placeholder==''){ echo $nama; }else{ echo $placeholder; } ?>" <?= $validasi; ?> <?= $html; ?>>
              <option value=""> - Pilih <?= $nama; ?> - </option>
              <?php if (empty($data_select_group)){ ?>
                <?php foreach ($data_select as $k_sel => $v_sel):
                  if ($name=='id_wilayah') {
                    $titlenya = htmlentities(strip_tags(get_data_sektornya($v_sel['id'])));
                  }else {
                    $titlenya = '';
                  }
                  ?>
                  <option value="<?= $v_sel['id']; ?>" <?php echo $v_sel['sel']; ?> title="<?= $titlenya; ?>"><?= $v_sel['nama']; ?></option>
                <?php endforeach; ?>
              <?php }else{ ?>

                <?php if ($name=='id_gudang'): $CI =& get_instance(); ?>
                   <option value="semua">Semua Gudang</option>
                  <?php foreach ($data_select_group as $key_sg => $value_sg):
                    $CI->db->select('id_gudang AS id, nama_gudang AS nama');
                    if ($value_sg['status']!='') {
                      $CI->db->where('status', $value_sg['status']);
                    }
                    $CI->db->order_by('nama_gudang', 'ASC');
                    $data_select = get('gudang', array('id_gudang_kota'=>$value_sg['id']))->result_array();
                    $id_g='';?>
                    <optgroup label="<?= $value_sg['nama']; ?>">
                      <?php if (!empty($value_sg['group'])): ?>
                        <?php if ($value_sg['group']=='x'): $id_g=$value_sg['id'].'_'; ?>
                          <option value="semua_<?= $value_sg['id']; ?>">Semua <?= $value_sg['nama']; ?></option>
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php foreach ($data_select as $k_sel => $v_sel): ?>
                        <option value="<?= $id_g.$v_sel['id']; ?>" <?php echo $v_sel['sel']; ?>><?= $v_sel['nama']; ?></option>
                      <?php endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                <?php endif; ?>

                <?php if (in_array($name, array('tipe','jenis'))): $CI =& get_instance(); ?>
                  <?php foreach ($data_select_group as $key_sg => $value_sg): ?>
                    <optgroup label="<?= $value_sg['nama']; ?>">
                      <?php foreach ($data_select as $k_sel => $v_sel):
                        if ($value_sg['id'] == $v_sel['group']) { ?>
                        <option value="<?= $v_sel['id']; ?>" <?php echo $v_sel['sel']; ?>><?= $v_sel['nama']; ?></option>
                      <?php
                        }
                      endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                <?php endif; ?>

              <?php } ?>
          </select>
        <?php }else{ ?>
          <input type="<?= $type; ?>" name="<?= $name; ?>" class="form-control <?= $class; ?> <?php if($type=='file'){ echo "custom-file-input"; } ?>" id="Input<?= $k; ?> <?= $id; ?>" value="<?= $value; ?>" placeholder="<?php if($placeholder==''){ echo $nama; }else{ echo $placeholder; } ?>" <?= $validasi; ?> <?= $html; ?>>
          <?php if (!empty($input_data_select)): ?>
            <div class="input-group-prepend">
                <select name="<?= $name_select; ?>" class="form-control" <?= $validasi; ?> <?= $html; ?>>
                  <?php foreach ($input_data_select as $key_is => $value_is): ?>
                    <option value="<?= $value_is['id']; ?>"><?= $value_is['nama']; ?></option>
                  <?php endforeach; ?>
                </select>
            </div>
          <?php endif; ?>
          <?php if ($type=='file'): ?>
            <label class="custom-file-label" for="Input<?= $k; ?>">Pilih <?= $nama; ?></label>
          <?php endif; ?>
        <?php } ?>
        <?php if ($icon!=''): ?>
          <?php if ($type!='checked'): ?>
            <?php if(empty($input_data_select)){ ?>
            <div class="form-control-position">
                <i class="bx bx-<?= $icon; ?>"></i>
            </div>
            <?php } ?>
          <?php endif; ?>
        <?php endif; ?>
      <?php if(empty($input_data_select)){ ?>
      </div>
      <?php } ?>
    </div>
  <?php if($col!=''){ ?>
  </div>
  <?php }
  }
}
?>
