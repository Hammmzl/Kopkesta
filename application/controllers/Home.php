<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Data_Entry/M_function' => 'amf',
      'Data_Entry/M_views' => 'amv',
      'Starter' => 'own',

    ));
    $this->load->library(array('Curency_indo_helper' => 'conv'));

  }

  function index()
  {
    $load = $this->amf->notif_pinjaman();
    $data = array(
        'js'  => 'notif',
        'title' => 'Dashboard',
        'notif' => $load,
        'jumlah_instansi' => $this->amf->jumlah_instansi(),
        'jumlah_anggota'  => $this->amf->jumlah_anggota(),
        'brangkas' => $this->own->get_brangkas()->row(),
        'page' => 'page/starter',
      );
      $this->load->view('main', $data);
  }

}
