<div class="page-content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0"><?= $title ?></h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pinjaman</a></li>
                            <li class="breadcrumb-item active"><?= $title ?></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <?php echo $this->session->flashdata('message'); ?>
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-body">
                          <div class="table-responsive">
                              <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                  <thead>
                                      <tr>
                                        <th>No</th>
                                        <th>Kode Pinjaman</th>
                                        <th>Nama Anggota</th>
                                        <th>Plafon Pinjaman</th>
                                        <th>Masa Pinjaman</th>
                                        <th>Margin</th>
                                        <th>Pokok </th>
                                        <th>Tanggal Pengajuan</th>
                                        <th>Opsi</th>

                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php $start = 1;
                                    foreach ($respon as $s) {?>
                                      <tr>
                                        <td><?= $start++; ?></td>
                                        <td><?= $s->kode; ?></td>
                                        <td><?= $s->nama; ?></td>
                                        <td><?= $this->conv->convRupiah($s->plafon); ?></td>
                                        <td><?= $s->tenor.' Bulan'; ?></td>
                                        <td><?= $this->conv->convRupiah($s->margin); ?></td>
                                        <td><?= $this->conv->convRupiah($s->pokok); ?></td>
                                        <td><?= $s->tgl_pengajuan; ?></td>
                                        <?php if ($s->approval == 1) { ?>
                                          <td>
                                            <a href="<?= base_url('tambah_pinjaman/').$s->no_rekening ?>" class="btn btn-outline-dark waves-effect">Sudah Disetujui Manajer</a>
                                          </td>
                                        <?php }elseif ($s->approval == 2) { ?>
                                          <td>
                                            <a class="btn btn-outline-info waves-effect">Sudah Ditolak Manajer</a>
                                          </td>
                                        <?php }else {?>
                                          <td>
                                            <a class="btn btn-outline-dark waves-effect">Menunggu Persetujuan Manajer</a>
                                          </td>
                                        <?php } ?>
                                      </tr>
                                    <?php } ?>
                                  </tbody>
                                  </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
