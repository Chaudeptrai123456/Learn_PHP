<?php

use App\Core\Controller;

Controller::get('/', 'HomeController@index');
Controller::get('/404', 'HomeController@notfound');
Controller::get('/401', 'HomeController@notrole');
Controller::get('/lienhe', 'HomeController@lienhe');
Controller::get('/hotro', 'HomeController@hotro');
Controller::get('/baohanh', 'HomeController@baohanh');

Controller::get('/products', 'ProductController@index');
Controller::get('/product/{slug}', 'ProductController@detail');
Controller::get('/product/search', 'ProductController@search');
Controller::get('/categories', 'ProductController@search');
Controller::get('/categories/search/{category}/{brand}/{price}', 'ProductController@search');
Controller::get('/test', 'ProductController@test');

Controller::get('/login', 'AuthController@login');
Controller::get('/signup', 'AuthController@signup');
Controller::post('/handleLogin', 'AuthController@handleLogin');
Controller::post('/handleSignup', 'AuthController@handleSignUp');
Controller::get('/account', 'AuthController@profile');

Controller::get('/order', 'OrderController@index');
Controller::post('/addToCart', 'OrderController@addToCart');
Controller::get('/order/history', 'OrderController@historyOrder');
Controller::post('/order/place', 'OrderController@placeOrder');  

Controller::get('/admin/dashboard', 'AuthController@adminDashboard');  

Controller::get('/admin/orders', 'AdminController@getOrders');
Controller::post('/admin/orders/update', 'AdminController@updateOrder');

Controller::get('/admin/products', 'AdminController@getProducts');
Controller::post('/admin/products/save', 'AdminController@saveProduct');      
Controller::post('/admin/products/delete', 'AdminController@deleteProduct'); 

Controller::get('/admin/users', 'AdminController@getUsers');