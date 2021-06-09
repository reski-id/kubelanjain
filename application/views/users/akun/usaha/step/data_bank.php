<div class="row">
    <div class="col-md-3"></div>
    <div class="col-12 col-md-6">
        <div class="form-group">
          <label for="id_bank">
              BANK
          </label>
          <select class="form-control" name="id_bank" id="id_bank" required>
            <option value=""> - Pilih BANK - </option>
            <?php $this->db->order_by('bank', 'ASC');
            foreach (get('bank', array('status'=>1))->result() as $key => $value): ?>
              <option value="<?= $value->id_bank; ?>"><?= $value->bank; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
            <label for="nama">
                Atas Nama
            </label>
            <input type="text" class="form-control required" id="nama" name="nama" placeholder="Atas Nama">
        </div>
        <div class="form-group">
            <label for="no_rek">
                Nomor Rekening
            </label>
            <input type="text" class="form-control required" id="no_rek" name="no_rek" placeholder="Nomor Rekening" minlength="5" data-parsley-validation-threshold="1" data-parsley-trigger="keyup" data-parsley-type="number" onkeypress="return hanyaAngka(event)">
        </div>
    </div>
</div>
