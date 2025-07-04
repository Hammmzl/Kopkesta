<div class="page-content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0"><?= $title ?></h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Data Petugas</a></li>
                          <li class="breadcrumb-item active"><?= $title ?></li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
        <div class="row">
                    <div class="col-xl-8">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"><?= $title; ?></h4>
                                    <?php echo validation_errors('<div class="alert alert-warning">','</div>'); ?>
                                    <form method="post" action="<?= base_url('C_Admin/proses_update'); ?>" class="custom-validation">
                                      <input type="hidden" name="id" value="<?= $id; ?>">
                                      <div class="row">
                                        <div class="mb-3 col-xl-6">
                                          <label class="form-label">Nama</label>
                                          <div>
                                            <input type="text" class="form-control" name="nama" maxlength="30" value="<?= $nama ?>" required />
                                          </div>
                                        </div>
                                        <div class="mb-3 col-xl-6">
                                          <label class="form-label"> Username</label>
                                          <div>
                                            <input type="text" class="form-control" name="username" maxlength="30" value="<?= $username ?>" required />
                                          </div>
                                        </div>
                                        <div class="mb-3 col-xl-6">
                                          <label class="form-label"> Password </label>
                                          <div>
                                            <input type="text" class="form-control" name="pass" maxlength="30" required />
                                          </div>
                                        </div>
                                        <div class="mb-3 col-xl-6">
                                        <label class="form-label">Level Akses</label>
                                        <?php if ($action != 'update_petugas') { ?>
                                        <div class="input-group">
                                            <select class="form-control" name="level" value="<?= $level ?>"  required>
                                                <option selected value="<?= $level ?>" ><?php
                                                if ($level == 'manajer') {
                                                  echo "Manajer";
                                                }
                                                elseif ($level == 'teller') {
                                                  echo "Teller";
                                                }
                                                  ?></option>
                                                </select>
                                              </div>
                                            <?php }
                                             else { ?>
                                               <div class="input-group">
                                                    <select class="form-control" name="level" value="<?= $level ?>"  required>
                                                    <option default>-- Pilih --</option>
                                                    <option value="manajer">Manager</option>
                                                    <option value="teller">Teller</option>
                                                    <option selected value="<?= $level; ?>"><?php
                                                    if ($level == 'manajer') {
                                                      echo "Manajer";
                                                    }
                                                    elseif ($level == 'teller') {
                                                      echo "Teller";
                                                    }
                                                     ?></option>
                                                    </select>
                                                  </div>
                                                <?php }
                                                ?>
                                          </div>
                                        <div>
                                            <div>
                                                <button class="btn btn-primary waves-effect waves-light me-1">
                                                    Submit
                                                </button>
                                                <button class="btn btn-secondary waves-effect">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
