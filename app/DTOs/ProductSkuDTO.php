<?php
namespace App\DTOs;

class ProductSkuDTO {

    public string $sku_code;
    public float $price;
    public float $old_price;
    public int $stock_qty;
    public int $is_default;

    public static function fromArray(array $data): self {
        $sku = new self();

        $sku->sku_code   = $data['sku_code'] ?? '';
        $sku->price      = (float)($data['price'] ?? 0);
        $sku->old_price  = (float)($data['old_price'] ?? 0);
        $sku->stock_qty  = (int)($data['stock_qty'] ?? 0);
        $sku->is_default = isset($data['is_default']) ? 1 : 0;

        return $sku;
    }
}