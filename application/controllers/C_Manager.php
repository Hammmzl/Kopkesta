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
      $approv = $this->mc->setujui_approval($kode_pinjaman);
       var_dump($approv);
       die();
       $this->session->set_flashdata('msg', '<div class="alert alert-success">Data Nasabah sudah disetujui oleh Manajer.</div>');
       redirect('C_Manager');
    }

  //
  function tolak_pinjaman($kode_pinjaman)
  {
    $sql = "UPDATE tb_pinjaman SET sts_approval = 2 WHERE kode_pinjaman='{$kode_pinjaman}'";
     $query_sql = $this->db->query($sql);
     $this->session->set_flashdata('msg', '<div class="alert alert-success">Data Nasabah sudah ditolak oleh Manajer.</div>');
     redirect('C_Manager');
  }
}
