<?php

use App\Core\Controller;

Controller::get('/', 'HomeController@index');
Controller::get('/404',"HomeController@notfound");
Controller::get('/lienhe',"HomeController@lienhe");
Controller::get('/hotro',"HomeController@hotro");
Controller::get('/baohanh',"HomeController@baohanh");
Controller::get('/product/{slug}', 'ProductController@detail');
Controller::get('/products', 'ProductController@index');
Controller::get('/login','AuthController@login');
Controller::get('/signup','AuthController@signup');