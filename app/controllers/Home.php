<?php

class Home extends Controller {
    public function index()
    {
        $data['judul'] = 'Halaman Home';
        
        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }

    public function verify_notice()
    {
        $data['judul'] = 'Verifikasi Email Dibutuhkan';
        
        $this->view('templates/header', $data);
        $this->view('home/verify_notice', $data);
        $this->view('templates/footer');
    }
}
