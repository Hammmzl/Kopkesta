<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
              <div class="navbar-brand-box text-center">
                <a href="<?= base_url() ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="logo-sm-dark" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="logo-dark" height="50">
                    </span>
                </a>
                <a href="<?= base_url() ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="logo-sm-light" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url(); ?>assets/images/logo-sm.png" alt="logo-light" height="50">
                    </span>
                </a>
            </div>
            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                <i class="ri-menu-2-line align-middle"></i>
            </button>
        </div>
        <div class="d-flex">
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ri-search-line"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">
                    <form class="p-3">
                        <div class="mb-3 m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ...">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="ri-search-line"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line"></i>
                </button>
            </div>
            <?php if ($js == 'notif') { ?>
            <?php
              if ($this->session->userdata('id_lvl') == 1) {?>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                      data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-notification-3-line"></i>
                    <span class="noti-dot"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifikasi </h6>
                            </div>
                        </div>
                    </div>
                      <?php foreach ($notif as $s) { ?>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <span class="avatar-title bg-primary rounded-circle font-size-16">
                                            <i class="ri-checkbox-circle-line"></i>
                                        </span>
                                    </div>
                                </div>

                                  <div class="flex-grow-1">
                                      <h6 class="mb-1">APPROVAL</h6>
                                      <div class="font-size-12 text-muted">
                                          <p class="mb-1">Pengajuan Pinjaman Dengan Kode Pinjaman <?= $s->kode_pinjaman; ?> Dengan Nama Nama dan jumlah Pinjaman <?=$this->conv->convRupiah($s->plafon);  ?></p>
                                          <p class="mb-0"><i class="mdi mdi-clock-outline"></i>Tanggal Pengajuan <?= $s->tanggal_pengajuan;  ?></p>
                                      </div>
                                  </div>
                            </div>
                        </a>
                    </div>
                  <?php } ?>
                    <div class="p-2 border-top">
                        <div class="d-grid">
                            <a href="<?= base_url('C_Manager'); ?>" class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                <i  class="mdi mdi-arrow-right-circle me-1"></i> View More..
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <?php } ?>
            <div class="dropdown d-inline-block user-dropdown">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="rounded-circle header-profile-user" src="<?= base_url(); ?>assets/images/users/avatar-2.jpg"
                        alt="Header Avatar">
                    <span class="d-none d-xl-inline-block ms-1"><?= $this->session->userdata('user'); ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i><?= $this->session->userdata('lvl'); ?></a>
                  <a class="dropdown-item" href="<?= base_url('C_Admin'); ?>"><i class="mdi mdi-account-cog align-middle me-1"></i>Data Petugas</a>
                  <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="<?= base_url('C_Auth/logout') ?>"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
