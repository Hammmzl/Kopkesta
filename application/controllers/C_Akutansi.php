<?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class C_Akutansi extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model(
      array(
        'Data_Entry/M_views' => 'mv',
        'Data_Entry/M_create' => 'mc',
        'Data_Entry/M_function' => 'mf',
        'Data_Operasional/M_update' => 'mu',
    ));
    $this->load->library(array(
      'Curency_indo_helper' => 'conv',
      'Parsing_bulan' => 'bulan'
    ));
    if ($this->session->userdata('masuk') != TRUE && $this->session->userdata('id_lvl') != '3') {
      $this->session->set_flashdata('msg', 'Anda Tidak Memiliki Akses Manajer');
      redirect(base_url('logout'));
    }

  }

    function global_kas()
    {
      $load_brangkas = $this->mu->get_brangkas();
      $lb = $load_brangkas->row();
      $this->kas                = $lb->kas;
      $this->dana_gotongroyong  = $lb->dana_gotongroyong;
      $this->dana_simpok        = $lb->dana_simpok;
      $this->dana_simwa         = $lb->dana_simwa;
      $this->dana_kusus         = $lb->dana_kusus;
      $this->dana_lainya        = $lb->dana_lainya;
      $this->total_hutang       = $lb->total_hutang;
      $this->total_piutang      = $lb->total_piutang;
      return;
    }

    // IDEA: Menampilkan Opsi Data Rekening Anggota ( Data Simpanan )
    function rekening()
    {
      $load   = $this->mf->list_rekening()->result();
      $data = array(
        'js'    => 'dataTables',
        'title' => 'Simpanan Anggota',
        'rekening' => $load,
        'page'  => 'page/rekening/anggota_rekening',
      );
      $this->load->view('main', $data);
    }

    // IDEA: Mencari Data Anggota Berdasarkan Nomor Anggota
    function tambah_simpanan()
    {
      // IDEA: Tangkap Penampung Data ke Array Kosong
      $q['s'] = [];
      if ($s = $this->mf->cari_anggota(htmlspecialchars($this->input->post('no_anggota')))) {
        $q['s'] = $s;
        foreach ($q['s'] as $s) { $no_rekening = $s->no_rekening;}
        $load = $this->mf->detail_anggota_simpanan($no_rekening);

        $data = array(
          'js'      => false,
          'title'   =>  'Tambah Simpanan Anggota',
          'rekening' =>  $load,
          'page'    =>  'page/rekening/simpanan_rekening'
        );
        $this->load->view('main', $data);
      }else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger"> Data Tersebut Tidak Ada Di Sistem </div>');
        redirect('cari_simpanan');
      }
    }

    // IDEA: Menyimpan Data Simpanan
    function simpan_rekening($no_rekening)
    {
      // IDEA: Uraikan Kedalam Variable Dari Function Global Kas
      $this->global_kas();
      $d_kas        = $this->kas;
      $d_gotong     = $this->dana_gotongroyong;
      $d_simpok     = $this->dana_simpok;
      $d_simwa      = $this->dana_simwa;
      $d_kusus      = $this->dana_kusus;
      $d_lain       = $this->dana_lainya;
      // IDEA: Akhir Penguraian Dari Function Global kas

      $load = $this->mv->get_detail_rekening($no_rekening);
      $log_load = $this->mv->master_view_rekening($no_rekening);
      $kode_log = time();
      $p = $load->s_pokok;
      $w = $load->s_wajib;
      $k = $load->s_khusus;
      $l = $load->s_lain;
      $t = $load->total_akumulasi;
      $last_update = date('Y-m-d');
      $jumlah = str_replace('.','',$this->input->post('jml_simpan'));

      if ($this->input->post('jenis_simpanan') == 1) {
        $data = array(
            's_pokok' => $p + $jumlah,
            'total_akumulasi' => $t + $jumlah,
            'last_update' => $last_update,
        );
        $jenis_log = 'Simpanan Pokok';
        $brangkas['dana_simpok'] = $d_simpok+$jumlah;

      }elseif ($this->input->post('jenis_simpanan') == 2) {
        $data = array(
            's_wajib' => $w + $jumlah,
            'total_akumulasi' => $t + $jumlah,
            'last_update' => $last_update,
        );
        $jenis_log = 'Simpanan Wajib';
        $brangkas['dana_simwa'] = $d_simwa+$jumlah;

      }elseif ($this->input->post('jenis_simpanan') == 3) {
        $data = array(
            's_khusus' => $k + $jumlah,
            'total_akumulasi' => $t + $jumlah,
            'last_update' => $last_update,
        );
        $jenis_log = 'Simpanan Khusus';
        $brangkas['dana_kusus'] = $d_kusus+$jumlah;
      }elseif ($this->input->post('jenis_simpanan') == 4) {
        $data = array(
            's_lain' => $l + $jumlah,
            'total_akumulasi' => $t + $jumlah,
            'last_update' => $last_update,
        );
        $jenis_log = 'Simpanan Lain';
        $brangkas['dana_lainya'] = $d_lain+$jumlah;
      }else {
        $data = array(
            's_lain' => $l + $jumlah,
            'total_akumulasi' => $t + $jumlah,
            'last_update' => $last_update,
        );
        $jenis_log = 'Simpanan Lain';
        $brangkas['dana_lainya'] = $d_lain+$jumlah;
      }

      $logs = array(
        'no_rekening' => $no_rekening,
        'nama_anggota' => $log_load->nama,
        'kode_log' => $kode_log,
        'jumlah' => $jumlah,
        'kode_jenis' => 1,
        'jenis' => $jenis_log,
        'keterangan' => 'Berhasil update '.$jenis_log.' dengan jumlah '.$jumlah,
        'last_update' => $last_update,
      );

      $brangkas['kas'] = $d_kas + $jumlah;
      $brangkas['last_update'] = $last_update;

      $this->mu->update_brangkas($brangkas);
      $this->mc->add_log_simpanan($logs);
      $this->mc->update_rekening($no_rekening, $data);
      $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show">Berhasil mengupdate simpanan, <a class="btn btn-info waves-effect waves-light" href="'.base_url('cetak/simpanan/').$kode_log.'" target="_blank"> Download Invoice</a> </div>');
      redirect('simpanan');
    }

    function tambah_pinjaman_dengan_norek($no_rekening)
    {
      $load = $this->mf->detail_anggota_simpanan($no_rekening);
      $data = array(
        'js'      => false,
        'title'   =>  'Tambah Pinjaman Anggota',
        'rekening' =>  $load,
        'page'    =>  'page/rekening/pinjaman_rekening'
      );
      $this->load->view('main', $data);
    }

    // IDEA: Cari Pinjaman Berdasarkan ID anggota
    function tambah_pinjaman()
    {
      // IDEA: Tangkap Penampung Data ke Array Kosong
      $q['s'] = [];
      if ($s = $this->mf->cari_rekening(htmlspecialchars($this->input->post('no_rekening')))) {
        $q['s'] = $s;
        foreach ($q['s'] as $s) { $no_rekening = $s->no_rekening; $sts_pinjaman = $s->sts_pinjaman; }
        $load = $this->mf->detail_anggota_simpanan($no_rekening);
        if ($sts_pinjaman == 1) {
          $this->session->set_flashdata('message', '<div class="alert alert-warning"> Anggota ini tidak dapat ambil pinjaman karena masih ada pinjaman yang belum lunas </div>');
          redirect('cari_simpanan');
        }else {
          $data = array(
            'js'      => false,
            'title'   =>  'Tambah Pinjaman Anggota',
            'rekening' =>  $load,
            'page'    =>  'page/rekening/pinjaman_rekening'
          );
          $this->load->view('main', $data);
        }
      }else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger"> Data Tersebut Tidak Ada Di Sistem </div>');
        redirect('cari_simpanan');

      }
    }


    // IDEA: Proses Untuk peminjaman

    function simpan_pinjaman($no_rekening)
    {
      // IDEA: Uraikan Kedalam Variable Dari Function Global Kas
      $this->global_kas();
      $d_kas        = $this->kas;
      $d_gotong     = $this->dana_gotongroyong;
      $d_hutang     = $this->total_hutang;
      $d_piutang    = $this->total_piutang;
      // IDEA: Akhir Penguraian Dari Function Global kas

      $kode_pinjaman	= 'P-'.time();
      $plafon	        = str_replace('.','',$this->input->post('jumlah'));
      $tenor	        = $this->input->post('tenor');
      $margin	        = 0.08; // 8%

      $rumus_margin       = ($plafon*$margin)/12; // Rumus untuk mendapatkan nilai margin perbulan
      $margin_angsur      = $rumus_margin/$tenor; // akumulasi nilai margin ke setiap bulan
      $pokok_murabahan    = $plafon/$tenor; // Pembagian angsuran pokok untuk masa pinjaman/tenor
      $total_gotongroyong	= (0.015 * $plafon); // total gotong royong di ambil dari nilai plafon sebanyak 1,5%
      $total_angsuran	    = $plafon + $rumus_margin;
      $angsuran_ke	      = 0;
      $approval           = 3;
      $tanggal_pengajuan	= date('Y-m-d');
      $last_update        = date('Y-m-d');

      $pinjaman = array(
        'kode_pinjaman'       => $kode_pinjaman,
        'no_rekening'         => $no_rekening,
        'plafon'              => $plafon,
        'tenor'               => $tenor,
        'margin'              => round($rumus_margin,0,PHP_ROUND_HALF_EVEN),
        'pokok_murabahan'     => round($pokok_murabahan,0,PHP_ROUND_HALF_EVEN),
        'total_gotongroyong'  => $total_gotongroyong,
        'total_angsuran'      => round(0,0,PHP_ROUND_HALF_EVEN),
        'angsuran_ke'         => $angsuran_ke,
        'sisa_angsuran'       => $plafon,
        'approval'            => $approval,
        'tanggal_pengajuan'   => $tanggal_pengajuan,
        'last_update'         => $last_update,
      );


      $load = $this->mv->get_detail_rekening($no_rekening);
      $akumulasi_dagoro = $load->s_gotongroyong + $total_gotongroyong;

      if ($load->sts_pinjaman == 1) {
        $this->session->set_flashdata('message', '<div class="alert alert-warning"> Anggota ini masih ada pinjaman dengan kode pinjaman <b>'.$kode_pinjaman .'</b> silahkan lunaskan terlebih dahulu. </div>');
        redirect('pinjaman');
      }else {
        $rekening = array(
          'no_rekening'     => $no_rekening,
          'anggota_no'      => $load->anggota_no,
          's_gotongroyong'  => $akumulasi_dagoro,
          'sts_pinjaman'    => 1,
        );

        $brangkas['dana_gotongroyong'] = $d_gotong+$total_gotongroyong;
        $brangkas['total_piutang'] = $d_piutang+$plafon;
        $brangkas['total_hutang'] = $d_hutang+$plafon;
        $brangkas['last_update'] = $last_update;
        $brangkas['kas'] = ($d_kas - $plafon) + $total_gotongroyong ;
        if ($approval == 3) {
          $this->mc->insert_pinjaman($pinjaman);
          redirect('C_Akutansi/respon_manager');
          $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show">Berhasil melakukan pinjaman, <a class="btn btn-info waves-effect waves-light" href="'.base_url('cetak/pinjaman/').$kode_pinjaman.'" target="_blank"> Download Invoice</a> </div>');

        }else {
          $this->mu->update_brangkas($brangkas);
          $this->mc->update_dagoro($no_rekening, $rekening);
          redirect ();
        }
      }
    }
      function respon_manager()
      {
        $load = $this->mf->get_list_pinjaman();
        $data = array(
          'js'    => 'dataTables',
          'title' => 'Persetujuan Manajer',
          'respon'=> $load,
          'page'  => 'page/respon/respon_manager'
        );
        $this->load->view('main', $data);

      }

    function bayar_angsuran($kode_pinjaman)
    {
      $load_pinjaman = $this->mf->get_pinjaman_kode($kode_pinjaman);
      $load_angsuran = $this->mf->get_angsuran_kode($kode_pinjaman);
      $no_rekening = $load_pinjaman->no_rekening;
      $load_anggota = $this->mf->detail_anggota_simpanan($no_rekening);


      $data = array(
        'js'       => '',
        'title'    => 'Bayar Angsuran Anggota',
        'anggota'  => $load_anggota,
        'angsuran' => $load_angsuran,
        'pinjaman' => $load_pinjaman,
        'jenis'    => 1,
        'page'     => 'page/rekening/angsuran_pinjaman',
      );
      $this->load->view('main', $data);
    }

    function pelunasan_angsuran($kode_pinjaman)
    {
      $load_pinjaman = $this->mf->get_pinjaman_kode($kode_pinjaman);
      $load_angsuran = $this->mf->get_angsuran_kode($kode_pinjaman);
      $no_rekening = $load_pinjaman->no_rekening;
      $load_anggota = $this->mf->detail_anggota_simpanan($no_rekening);

      $data = array(
        'js'       => '',
        'title'    => 'Bayar Angsuran Anggota',
        'anggota'  => $load_anggota,
        'angsuran' => $load_angsuran,
        'pinjaman' => $load_pinjaman,
        'jenis'    => 2,
        'page'     => 'page/rekening/angsuran_pinjaman',
      );
      $this->load->view('main', $data);
    }

    function proses_angsuran($kode_pinjaman)
    {
      // IDEA: Uraikan Kedalam Variable Dari Function Global Kas
      $this->global_kas();
      $d_kas        = $this->kas;
      $d_piutang    = $this->total_piutang;
      // IDEA: Akhir Penguraian Dari Function Global kas

      $no_rekening = $this->input->post('no_rekening');
      $pinjaman    = $this->mf->get_pinjaman_kode($kode_pinjaman);
      $anggota     = $this->mf->get_detail_rekening($no_rekening);
      $last_update = date('Y-m-d');
      $kode_pinjaman = $this->input->post('kode_pinjaman');
      $angsuran_pokok = str_replace('.','',$this->input->post('pokok'));
      $angsuran_margin = str_replace('.','',$this->input->post('margin'));
      $tahun = date('Y');

      $data_margin = $this->mf->get_id_margin($no_rekening, $tahun);
      if ($data_margin == NULL) {
        $is_margin = array(
          'no_rekening' => $no_rekening,
          'margin_saving' => $angsuran_margin,
          'tahun' => $tahun,
          'last_update' => $last_update,
        );
        $this->mc->insert_margin($is_margin);
      }else {
        $id = $data_margin->id_margin;
        $is_margin = array(
          'margin_saving' => ($data_margin->margin_saving)+$angsuran_margin,
          'last_update' => $last_update,
        );
        $this->mc->update_margin($id, $is_margin);
      }

      if ($this->input->post('jenis') == 1) {
          $angsur_kode = 'A-'.time();
          $keterangan = 'Sudah melakukan pembayaran Angsuran Bulanan';
          $angsuran_ke = $pinjaman->angsuran_ke+1;
          $c_angsur = $pinjaman->angsuran_ke+1;
          $sisa_angsuran = ($pinjaman->sisa_angsuran-$angsuran_pokok);
          if ($c_angsur == $pinjaman->tenor) {
            $sts_pinjaman = 0;
            $status_pinjaman = array('sts_pinjaman' => $sts_pinjaman);
            $this->mc->update_status_rekening($no_rekening, $status_pinjaman);
          }else {
            $sts_pinjaman = 1;
          }
      }else {
          $angsur_kode = 'L-'.time();
          $keterangan = 'Sudah Berhasil melunaskan Angsuran';
          $angsuran_ke = 'Lunas';
          $c_angsur = $pinjaman->tenor;
          $sisa_angsuran = ($pinjaman->sisa_angsuran-$angsuran_pokok);
          $sts_pinjaman = 0;
          $status_pinjaman = array('sts_pinjaman' => $sts_pinjaman);
          $this->mc->update_status_rekening($no_rekening, $status_pinjaman);
      }

      $update_p = array(
        'kode_pinjaman' => $kode_pinjaman,
        'total_angsuran' => ($pinjaman->total_angsuran+$angsuran_pokok+$angsuran_margin),
        'angsuran_ke' => $c_angsur,
        'sisa_angsuran' => $sisa_angsuran,
        'last_update' => $last_update,
      );

      $data = array(
        'kode_angsuran'      => $angsur_kode,
        'kode_pinjaman'      => $kode_pinjaman,
        'no_anggota'         => $anggota->anggota_no,
        'angsuran_ke'        => $angsuran_ke,
        'angsuran_pokok'     => $angsuran_pokok,
        'angsuran_margin'    => $angsuran_margin,
        'keterangan'         => $keterangan,
        'status_pinjaman'    => $sts_pinjaman,
        'last_update'        => $last_update,
      );

      $brangkas['kas'] = $d_kas + $angsuran_pokok + $angsuran_margin;
      // $brangkas['total_hutang'] = $total_hutang+$angsuran_pokok;
      $brangkas['total_piutang'] = $d_piutang - $angsuran_pokok;
      $brangkas['last_update'] = $last_update;

      $this->mu->update_brangkas($brangkas);
      $this->mc->insert_angsuran($data);
      $this->mc->update_angsuran_bulanan($kode_pinjaman, $update_p);
      $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show">Berhasil melakukan angsuran, <a class="btn btn-info waves-effect waves-light" href="'.base_url('cetak/angsuran/').$angsur_kode.'" target="_blank"> Download Invoice</a> </div>');
      redirect('pinjaman');
    }

    function pinjaman()
    {
      $load = $this->mf->get_list_pinjaman();
      $data = array(
        'js'    => 'dataTables',
        'title' => 'Pinjaman Anggota',
        'pinjaman' => $load,
        'page'  => 'page/rekening/anggota_pinjaman',
      );
      $this->load->view('main', $data);
    }

    function angsuran_anggota()
    {
      $load = $this->mv->master_view_angsuran();
      $data = array(
        'js'    => 'dataTables',
        'title' => 'Angsuran Anggota',
        'angsuran' => $load->result(),
        'page'  => 'page/rekening/anggota_angsuran',
      );
      $this->load->view('main', $data);
    }

    function angsuran_tertunda()
    {
      $load = $this->mv->angsuran_tertunda()->result();
      $dateNow = date('m');
      $data = array(
        'js'    => 'dataTables',
        'title' => 'Angsuran Tertunda',
        'angsuran' => $load,
        'date' => $dateNow,
        'page'  => 'page/rekening/tertunda_angsuran',
      );
      $this->load->view('main', $data);
    }

    function meninggal($kode_pinjaman)
    {
      $load_pinjaman = $this->mf->get_pinjaman_kode($kode_pinjaman);
      $load_angsuran = $this->mf->get_angsuran_kode($kode_pinjaman);
      $no_rekening = $load_pinjaman->no_rekening;
      $load_anggota = $this->mf->detail_anggota_simpanan($no_rekening);

          $data = array(
            'js'       => '',
            'title'    => 'Tutup Angsuran Anggota Meninggal',
            'anggota'  => $load_anggota,
            'angsuran' => $load_angsuran,
            'pinjaman' => $load_pinjaman,
            'jenis'    => 3,
            'page'     => 'page/rekening/angsuran_pinjaman',
          );
        $this->load->view('main', $data);
    }

    function proses_tutup_dagoro($kode_pinjaman)
    {
      // IDEA: Uraikan Kedalam Variable Dari Function Global Kas
      $this->global_kas();
      $d_kas        = $this->kas;
      $d_piutang    = $this->total_piutang;
      // IDEA: Akhir Penguraian Dari Function Global kas

      $no_rekening      = $this->input->post('no_rekening');
      $pinjaman         = $this->mf->get_pinjaman_kode($kode_pinjaman);
      $anggota          = $this->mf->get_detail_rekening($no_rekening);
      $last_update      = date('Y-m-d');
      $kode_pinjaman    = $this->input->post('kode_pinjaman');
      $angsuran_pokok   = str_replace('.','',$this->input->post('pokok'));
      $angsuran_margin  = str_replace('.','',$this->input->post('margin'));

      if ($this->input->post('jenis') != 3) {
        $this->session->set_flashdata('message', '<div class="alert alert-warning alert-dismissible fade show">Parameter yang dikirimkan salah  </div>');
        redirect('angsuran_tertunda');
      }else {
        $angsur_kode    = 'C-'.time();
        $keterangan     = 'Sudah melakukan pembayaran Angsuran dari dana gotong royong';
        $angsuran_ke    = 'Lunas';
        $c_angsur       = $pinjaman->tenor;
        $sisa_angsuran  = ($pinjaman->sisa_angsuran-$angsuran_pokok);
        $sts_pinjaman   = 0;
        $status_pinjaman = array('sts_pinjaman' => $sts_pinjaman);
        $this->mc->update_status_rekening($no_rekening, $status_pinjaman);

        $update_p = array(
          'kode_pinjaman'   => $kode_pinjaman,
          'total_angsuran'  => ($pinjaman->total_angsuran+$angsuran_pokok+$angsuran_margin),
          'angsuran_ke'     => $c_angsur,
          'sisa_angsuran'   => $sisa_angsuran,
          'last_update'     => $last_update,
        );

        $data = array(
          'kode_angsuran'      => $angsur_kode,
          'kode_pinjaman'      => $kode_pinjaman,
          'no_anggota'         => $anggota->anggota_no,
          'angsuran_ke'        => $angsuran_ke,
          'angsuran_pokok'     => $angsuran_pokok,
          'angsuran_margin'    => $angsuran_margin,
          'keterangan'         => $keterangan,
          'status_pinjaman'    => $sts_pinjaman,
          'last_update'        => $last_update,
        );

        $u_anggota = array(
          'no_anggota' => $anggota->anggota_no,
          'status'     => 2,
        );

        $data_margin = $this->mf->get_id_margin($no_rekening, $tahun);
        if ($data_margin == NULL) {
          $is_margin = array(
            'no_rekening' => $no_rekening,
            'margin_saving' => $angsuran_margin,
            'tahun' => $tahun,
            'last_update' => $last_update,
          );
          $this->mc->insert_margin($is_margin);
        }else {
          $id = $data_margin->id_margin;
          $is_margin = array(
            'margin_saving' => ($data_margin->margin_saving)+$angsuran_margin,
            'last_update' => $last_update,
          );
          $this->mc->update_margin($id, $is_margin);
        }

        $brangkas['kas'] = $d_kas + $angsuran_pokok + $angsuran_margin;
        // $brangkas['total_hutang'] = $total_hutang+$angsuran_pokok;
        $brangkas['total_piutang'] = $d_piutang-$angsuran_pokok;
        $brangkas['last_update'] = $last_update;

        $this->mu->update_brangkas($brangkas);
        $this->mc->update_anggota($anggota->anggota_no, $u_anggota);
        $this->mc->insert_angsuran($data);
        $this->mc->update_angsuran_bulanan($kode_pinjaman, $update_p);
        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible fade show">'.$keterangan.' </div>');
        redirect('pinjaman');
      }
    }


  }
