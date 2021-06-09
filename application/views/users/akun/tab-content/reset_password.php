<div class="card">
    <div class="card-content">
        <div class="card-body">
            <h5>Reset Password</h5>
            <hr>
            <div class="row">
              <div class="col-md-3"></div>
              <div class="col-12 col-md-6">
                <div id="pesannya"></div>
                <form id="sync_formx" action="javascript:simpan('sync_formx','users/proses/reset_password');" method="post" data-parsley-validate="true">
                  <div class="form-body">
                    <div class="row">

                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="Input0" id="Lbl_password1">Password Lama</label>
                          <div class="position-relative has-icon-left ">
                            <input type="password" name="password1" class="form-control  " id="Input0 " value="" placeholder="Password Lama" required="" data-parsley-minlength="5" autofocus="">
                            <div class="form-control-position"> <i class="bx bx-lock"></i> </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="Input1" id="Lbl_password2">Password Baru</label>
                          <div class="position-relative has-icon-left ">
                            <input type="password" name="password2" class="form-control  " id="Input1 " value="" placeholder="Password Baru" required="" data-parsley-minlength="5">
                            <div class="form-control-position"><i class="bx bx-key"></i></div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="Input2" id="Lbl_password3">Konfirmasi Password Baru</label>
                          <div class="position-relative has-icon-left ">
                            <input type="password" name="password3" class="form-control  " id="Input2 " value="" placeholder="Konfirmasi Password Baru" required="" data-parsley-minlength="5">
                            <div class="form-control-position"><i class="bx bx-key"></i></div>
                          </div>
                        </div>
                      </div>

                      <div class="col-12">
                        <hr>
                        <button type="reset" class="btn btn-light-secondary glow float-left">Reset</button>
                        <button type="submit" class="btn btn-primary glow float-right">Simpan</button>
                      </div>

                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>
</div>
