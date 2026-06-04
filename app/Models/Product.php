<?php
namespace App\Models;

class Product {
    public int $id;
    public int $category_id;
    public int $brand_id;
    public string $name;
    public string $slug;
    public ?string $short_desc;
    public ?string $long_desc;
    public float $base_price;
    public int $view_count;
    public string $status;
    public float $rating_avg;
    public ?string $deleted_at;  
    public array $skus = [];   
    public array $images = [];  

    public function __construct(array $data = []) {
        $this->view_count = $data['view_count'] ?? 0;
        $this->id = $data['id'] ?? 0;
        $this->category_id = $data['category_id'] ?? 0;
        $this->brand_id = $data['brand_id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->short_desc = $data['short_description'] ?? null;
        $this->long_desc = $data['long_description'] ?? null;
        $this->base_price = (float)($data['base_price'] ?? 0);
        $this->status = $data['status'] ?? 'published';
        $this->rating_avg = (float)($data['rating_avg'] ?? 0);
        $this->deleted_at = $data['deleted_at'] ?? null; 
    }

    public function addSku(ProductSku $sku): void {
        $this->skus[] = $sku;
    }

    public function getDefaultSku(): ?ProductSku {
        foreach ($this->skus as $sku) {
            if ($sku->is_default) return $sku;
        }
        return $this->skus[0] ?? null; 
    }
}