<?php

namespace App\Controllers;

use App\Core\Controller;

class AuthController extends Controller {

    public function login() {
        return $this->view('public/pages/login');
    }
    public function signup(){
        return $this->view('public/pages/signup');
    }
 
}