<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled" id="side-menu">
              <?php if ($this->session->userdata('id_lvl') == 1 || $this->session->userdata('id_lvl') == 2) { ?>
              <li>
                <a href="<?= base_url('dashboard'); ?>" class="waves-effect">
                  <i class="mdi mdi-home-variant-outline"></i>
                  <span>Dashboard</span>
                </a>
              </li>
              <li class="menu-title">Manejemen Dasar</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span>Anggota</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?=base_url('anggota'); ?>">Data Anggota</a></li>
                        <li><a href="<?=base_url('daftar'); ?>">Tambah Anggota Baru</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-office-building-outline"></i>
                        <span>Instansi</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                      <li><a href="<?=base_url('instansi'); ?>"> Data Instansi</a></li>
                      <li><a href="<?=base_url('tambah_instansi'); ?>"> Tambah Instansi Baru</a></li>
                    </ul>
                </li>
              <?php } ?>
                <?php if ($this->session->userdata('id_lvl') == 2) { ?>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-safe"></i>
                        <span>Simpanan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?=base_url('cari_simpanan'); ?>">Cari ID Anggota</a></li>
                        <li><a href="<?=base_url('simpanan'); ?>">Data Simpanan Anggota</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-safe"></i>
                        <span>Pinjaman</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?=base_url('cari_pinjaman'); ?>">Cari Rekening Anggota</a></li>
                        <li><a href="<?=base_url('pinjaman'); ?>">Data Pinjaman Anggota</a></li>
                        <li><a href="<?=base_url('C_Akutansi/respon_manager'); ?>">Hasil Respon Manajer</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span>Angsuran</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('angsuran') ?>">Data Angsuran Terakhir</a></li>
                        <li><a href="<?= base_url('angsuran_tertunda') ?>">Cek Angsuran Tertunda</a></li>
                    </ul>
                </li>
              <?php } ?>
              <?php if ($this->session->userdata('id_lvl') == 1) { ?>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-folder-user-line"></i>
                        <span>Manajer</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('C_Manager'); ?>">Persetujuan Pinjaman</a></li>
                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-application"></i>
                        <span>Buku Laporan</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?= base_url('neraca_tahunan'); ?>">Neraca Tahunan</a></li>
                    </ul>
                </li>
                <li class="menu-title">Pengaturan</li>
                <li>
                  <a href="javascript: void(0);" class="has-arrow waves-effect">
                      <i class="mdi mdi-cog"></i>
                      <span> Pengaturan </span>
                  </a>
                  <ul class="sub-menu" aria-expanded="false">
                    <li><a href="<?= base_url('reset_kas'); ?>">Reset Kas</a></li>
                  </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-account-cog"></i>
                        <span>Data Petugas</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="<?=base_url('C_Admin'); ?>">List Petugas</a></li>
                        <li><a href="<?=base_url('C_Admin/daftar'); ?>">Daftar Petugas</a></li>
                    </ul>
                </li>
                  <?php } ?>
                <li>
                    <a href="<?= base_url('C_Auth/logout') ?>">
                        <i class="mdi mdi-logout"></i>
                        <span> logout </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
