<?php
namespace App\DTOs;

class ProductSkuDTO {
    public function __construct(
        public readonly string $sku_code,
        public readonly float $price,
        public readonly ?float $old_price,
        public readonly int $stock_qty,
        public readonly bool $is_default = false
    ) {}

    /**
     * Factory method: Tạo DTO từ mảng dữ liệu (ví dụ từ $_POST)
     */
    public static function fromArray(array $data): self {
        return new self(
            sku_code:  $data['sku_code'],
            price:     (float)$data['price'],
            old_price: isset($data['old_price']) ? (float)$data['old_price'] : null,
            stock_qty: (int)$data['stock_qty'],
            is_default: (bool)($data['is_default'] ?? false)
        );
    }
}