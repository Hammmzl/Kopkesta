<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_Manager extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('form_validation');
    $this->load->model(
      array(
        'Data_Entry/M_views' => 'mv',
        'Data_Entry/M_create' => 'mc',
        'Data_Entry/M_function' => 'mf',
    ));
    $this->load->library(array(
      'Curency_indo_helper' => 'conv',
      'Parsing_bulan' => 'bulan'
    ));
  }

  function index()
  {
      $load = $this->mf->get_list_pinjaman();
      $data = array(
        'js'    => 'dataTables',
        'title' => 'Pinjaman Anggota',
        'respon' => $load,
        'page'  => 'page/respon/approval_manager',
      );
      $this->load->view('main', $data);
  }


  function persetujuan_pinjaman($kode_pinjaman)
    {
      $approv = $this->mf->s_approval($kode_pinjaman);
       $this->session->set_flashdata('msg', '<div class="alert alert-success">Data Nasabah sudah disetujui .</div>');
       redirect('C_Manager');
    }

  //
  function tolak_pinjaman($kode_pinjaman)
  {
     $approv = $this->mf->t_approval($kode_pinjaman);
     $this->session->set_flashdata('msg', '<div class="alert alert-success">Data Nasabah sudah ditolak .</div>');
     redirect('C_Manager');
  }
}
