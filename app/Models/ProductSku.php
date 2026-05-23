<?php
namespace App\Models;

class ProductSku {
    public int $id;
    public int $product_id;
    public string $sku_code;
    public float $price;
    public ?float $old_price;
    public int $stock_qty;
    public int $sold_qty;
    public ?string $image_url;
    public bool $is_default;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->product_id = $data['product_id'] ?? 0;
        $this->sku_code = $data['sku_code'] ?? '';
        $this->price = (float)($data['price'] ?? 0);
        $this->old_price = isset($data['old_price']) ? (float)$data['old_price'] : null;
        $this->stock_qty = $data['stock_qty'] ?? 0;
        $this->sold_qty = $data['sold_qty'] ?? 0;
        $this->image_url = $data['image_url'] ?? null;
        $this->is_default = (bool)($data['is_default'] ?? 0);
    }

    public function getSavingAmount(): float {
        return ($this->old_price && $this->old_price > $this->price) 
            ? ($this->old_price - $this->price) : 0;
    }

    public function getDiscountPercent(): int {
        if (!$this->old_price || $this->old_price <= $this->price) return 0;
        return (int)round((($this->old_price - $this->price) / $this->old_price) * 100);
    }
}