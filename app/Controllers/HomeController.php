<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

class HomeController extends Controller {
    private ProductRepository $proRepo;
    private CategoryRepository $cateRepo;
    
    public function __construct() {
        $this->proRepo = new ProductRepository();
        $this->cateRepo = new CategoryRepository();
    }
    public function index() {
        $discount=$this->proRepo->discountProduct();
        $hotPro=$this->proRepo->hotProduct();
        $trend=$this->proRepo->getTrendProduct();        
        return $this->view('home/home',[
            'products' => $this->proRepo->getAll(),
            'categories' => $this->cateRepo->getAllCategory(),
            'dis_products'=>$discount,
            'hot_pro'=>$hotPro,
            'tren_pro'=>$trend
        ]);
    }
    public function notrole(){
        return $this->view('/public/pages/401');
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
    public function notfound(){
        return $this->view('public/pages/404');
    }
}