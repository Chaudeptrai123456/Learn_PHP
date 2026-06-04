<?php

use App\Core\Controller;
Controller::get('/categories', 'ProductController@search');
Controller::get('/categories/search/{category}/{brand}/{price}', 'ProductController@search');
Controller::get('/categories/search/{category}/{brand}/{price}', 'ProductController@search');
Controller::get('/categories/search/{category}/{brand}/{price}', 'ProductController@search');

Controller::get('/', 'HomeController@index');
Controller::get('/404',"HomeController@notfound");
Controller::get('/lienhe',"HomeController@lienhe");
Controller::get('/hotro',"HomeController@hotro");
Controller::get('/baohanh',"HomeController@baohanh");
Controller::get('/product/{slug}', 'ProductController@detail');
Controller::get('/products', 'ProductController@index');
Controller::get('/login','AuthController@login');
Controller::get('/signup','AuthController@signup');

Controller::get('/categories','ProductController@search');

Controller::get('/test','ProductController@test');
Controller::get('/order','OrderController@index');
Controller::post('/addToCart','OrderController@addToCart');
Controller::post('/handleLogin','AuthController@handleLogin');
Controller::post('/handleSignup','AuthController@handleSignUp');
Controller::get('/product/search','ProductController@search');
// Controller::get('/search/products','ProductController@search');


// http://localhost/assignment/categories/all/samsung/all