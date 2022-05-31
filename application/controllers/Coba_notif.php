<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Coba_notif extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    $this->load->model(array(
      'Data_Entry/M_function' => 'amf',
      'Data_Entry/M_views' => 'amv',
    ));
  }

  function index()
  {
    $load = $this->amf->notif_pinjaman();
    var_dump($load);
    die();
  }

}
