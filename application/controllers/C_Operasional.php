<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Operasional extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->library('form_validation');
    $this->load->model(array(
      'Data_Operasional/M_update' => 'mu',
      'Data_Operasional/M_function' => 'mf',
    ));
    $this->load->library(array('Curency_indo_helper' => 'conv'));

  }

  function neraca()
  {
    $data = array(
      'js'      =>  '',
      'title'   =>  'Buat Neraca',
      'action'  =>  'proses_neraca',
      'page'    =>  'page/operasional/menu'
    );
    $this->load->view('main', $data);
  }

  function proses_neraca_tahunan()
  {
    $tahun = $this->input->post('tahun');

    $get_neraca = $this->mf->get_neraca_tahunan($tahun)->row();
    $invent = $this->mf->get_inventaris($tahun);


    if ($get_neraca == null) {
      require 'vendor/autoload.php';
      // $margin_saving = $this->mf->get_margin()->result();
      $akumulasi = $this->mf->sum_margin()->row()->total_margin;
      $atk = $this->mf->sum_atk()->row()->atk;
      $honor = $this->mf->sum_honor()->row()->honor;
      $rat = $this->mf->sum_rat()->row()->rat;
      $thr = $this->mf->sum_thr()->row()->thr;
      $penghapusan = $this->mf->sum_penghapusan()->row()->penghapusan;
      $brangkas = $this->mf->get_brangkas()->row();
      $last_update = date('Y-m-d');

      // IDEA: Rumus SHU
      $operasional = $atk + $honor + $rat + $thr + $penghapusan;
      $shu_sebelum_zakat = $akumulasi - $operasional;
      $zakat = $shu_sebelum_zakat * 0.025;
      $shu_sesudah_zakat = $shu_sebelum_zakat - $zakat;

      $x = $shu_sesudah_zakat;

      $bagian_usaha_anggota = array(
        'jasa_usaha' => $x * (25/100),
        'jasa_simpanan' => $x * (20/100),
        'dana_cadangan' => $x * (25/100),
        'dana_pengurus' => $x * (10/100),
        'dana_kesejahteraan' => $x * (5/100),
        'dana_pendidikan' => $x * (5/100),
        'dana_sosial' => $x * (5/100),
        'dana_audit' => $x * (2.5/100),
        'dana_pembangunan' => $x * (2.5/100),
        'tahun_neraca' => date('Y'),
        'keterangan' => 'Data Telah di Create pada tanggal '.date('d-m-Y'),
        'last_update' => $last_update,
      );

      $neraca_tahunan = array(
        'pendapatan_jasa' => $akumulasi,
        'pendapatan_lain' => '-',
        'biaya_atk' => $atk,
        'biaya_honor' => $honor,
        'biaya_rat' => $rat,
        'biaya_lebaran' => $thr,
        'biaya_penghapusan' => $penghapusan,
        'jumlah_biaya_adm' => $operasional,
        'shu_sebelum_zakat' => $shu_sebelum_zakat,
        'zakat' => $zakat,
        'shu_setelah_zakat' => $shu_sesudah_zakat,
        'tahun' => date('Y'),
        'last_update' => $last_update,
      );

      $detail_neraca = array(
        'kas' => $brangkas->kas,
        'dana_gotongroyong' => $brangkas->dana_gotongroyong,
        'dana_simpok' => $brangkas->dana_simpok,
        'dana_simwa' => $brangkas->dana_simwa,
        'dana_kusus' => $brangkas->dana_kusus,
        'dana_lainya' => $brangkas->dana_lainya,
        'total_hutang' => $brangkas->total_hutang,
        'total_piutang' => $brangkas->total_piutang,
        'last_update' => $brangkas->last_update,
      );

      $template = new \PhpOffice\PhpWord\TemplateProcessor('./assets/template/daftar-pembagian-shu.docx');

      // IDEA: Page 3

      $template->setValue('kas', $this->conv->convRupiah('0'));
      $template->setValue('piutang', $this->conv->convRupiah('0'));
      $template->setValue('piutang_lain', $this->conv->convRupiah('0'));
      $template->setValue('inventaris_total', $this->conv->convRupiah('0'));
      $template->setValue('gotong_royong', $this->conv->convRupiah('0'));
      $template->setValue('rupa_dana', $this->conv->convRupiah('0'));

      $template->setValue('t_dana_pengurus', $this->conv->convRupiah('0'));
      $template->setValue('t_dana_pendidikan', $this->conv->convRupiah('0'));
      $template->setValue('t_dana_kes_pegawai', $this->conv->convRupiah('0'));
      $template->setValue('t_dana_sosial', $this->conv->convRupiah('0'));
      $template->setValue('t_dana_audit', $this->conv->convRupiah('0'));
      $template->setValue('t_dana_pembangunan', $this->conv->convRupiah('0'));

      $template->setValue('dana_lainya', $this->conv->convRupiah('0'));
      $template->setValue('sisa_shu_anggota', $this->conv->convRupiah('0'));
      $template->setValue('simpok', $this->conv->convRupiah('0'));
      $template->setValue('simwa', $this->conv->convRupiah('0'));
      $template->setValue('simkusus', $this->conv->convRupiah('0'));
      $template->setValue('dana_cadangan', $this->conv->convRupiah('0'));
      $template->setValue('tahun_sebelum', $tahun -1);


      $d_in = $invent->result();
      $start = 0;
      if ($invent->num_rows() == 0) {
        $i = '';
      }else {
        foreach ($d_in as $data) {
            $i[$start++] = array(
              'harga_inventaris' => $this->conv->convRupiah($data->nominal),
              'inventaris_item' => $data->keterangan
            );
        }
      }

      $template->cloneBlock('inventaris_block', 0, true, false, $i);


      // IDEA: Page 2
      $template->setValue('total_adm', $this->conv->convRupiah('0'));
      $template->setValue('total_shu_bersih', $this->conv->convRupiah('0'));
      $template->setValue('total_shu_kotor', $this->conv->convRupiah('0'));
      $template->setValue('zakat', $this->conv->convRupiah('0'));
      $template->setValue('total_pendapatan_lain', $this->conv->convRupiah('0'));
      $template->setValue('total_pendapatan_jumlah', $this->conv->convRupiah('0'));
      $template->setValue('atk', $this->conv->convRupiah('0'));
      $template->setValue('honor', $this->conv->convRupiah('0'));
      $template->setValue('rat', $this->conv->convRupiah('0'));
      $template->setValue('thr', $this->conv->convRupiah('0'));
      $template->setValue('penghapusan', $this->conv->convRupiah('0'));
      $template->setValue('total_adm', $this->conv->convRupiah('0'));


      // IDEA: Page 1
      $template->setValue('jasa_usaha', $this->conv->convRupiah('0'));
      $template->setValue('jasa_simpanan', $this->conv->convRupiah('0'));
      $template->setValue('dana_cadangan', $this->conv->convRupiah('0'));
      $template->setValue('dana_pengurus', $this->conv->convRupiah('0'));
      $template->setValue('dana_kesejahteraan', $this->conv->convRupiah('0'));
      $template->setValue('dana_pendidikan', $this->conv->convRupiah('0'));
      $template->setValue('dana_sosial', $this->conv->convRupiah('0'));
      $template->setValue('dana_audit', $this->conv->convRupiah('0'));
      $template->setValue('dana_pembangunan', $this->conv->convRupiah('0'));
      $template->setValue('tahun_neraca', $tahun);
      $template->setValue('last_update', $last_update);
      $template->setValue('tanggal', date('d'));



      $filename = 'Daftar Pembagian SHU Tahun'. $tahun;

      header('Content-type: application/vnd.ms-word');
      header('Content-Disposition: attachment; filename="'. $filename .'.docx"');
    	header('Cache-Control: max-age=0');
      $template->saveAs('php://output');
    }elseif ($get_neraca != null) {
      if ($tahun == $get_neraca->tahun_neraca) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show">Neraca Untuk Tahun Ini sudah dibuat</div>');
        redirect('neraca_tahunan');
      }
    }



  }
}
