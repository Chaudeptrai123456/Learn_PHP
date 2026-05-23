<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {

    public function index() {
        return $this->view('home/home');
    }
    public function lienhe(){
        return $this->view('public/pages/lienhe');
    }
    public function hotro(){
        return $this->view('public/pages/hotro');
    }
    public function baohanh(){
        return $this->view('public/pages/baohanh');
    }
}