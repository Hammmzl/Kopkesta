<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends CI_Controller{

  public function __construct()
  {
    parent::__construct();
    //Codeigniter : Write Less Do More
    $this->load->model(array(
      'Data_Operasional/M_update' => 'mu',
      'Data_Operasional/M_function' => 'mf',
      'Starter' => 'own',
    ));
    if ($this->session->userdata('masuk') != TRUE && $this->session->userdata('id_lvl') != '1') {
      $this->session->set_flashdata('msg', 'Anda Tidak Memiliki Akses Manajer');
      redirect(base_url('logout'));
    }
  }

  function reset_kas()
  {
    $login = $this->session->userdata('id_lvl');
    if ($login == 1) {
    $last_update = date('Y-m-d');
    $brangkas = array(
      'kas' => 0,
      'total_hutang' => 0,
      'total_piutang' => 0,
      'last_update' => $last_update,
    );

    $this->mu->update_brangkas($brangkas);
    redirect('dashboard');
  }else {
    redirect('/');
  }
  }

  function error()
  {
    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show">Parameter yang di input tidak dikenal</div>');
    redirect('/');

  }
}
